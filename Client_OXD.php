<?php

abstract class Client_oxd
{
    protected $ip;
    protected $port;
    protected static $socket = null;
    private $command_types = array(
        'register_client', 'client_read', 'obtain_pat', 'obtain_aat',
        'obtain_rpt', 'authorize_rpt', 'register_resource', 'rpt_status',
        'id_token_status', 'access_token_status', 'register_ticket', 'discovery',
        'authorization_code_flow', 'get_authorization_url', 'get_tokens_by_code',
        'get_user_info', 'register_site',
    );
    protected $data = array();
    protected $command;
    protected $params = array();
    protected $response_json;
    protected $response_object;
    private   $response_status;
    protected $response_data = array();


    /**
     * abstract Client constructor.
     * @param $ip ='127.0.0.1', $port=8099
     */
    public function __construct($ip = '127.0.0.1', $port = 8099)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $this->setIp($ip);
        } else {
            $this->error_message("$ip is not a valid IP address");
        }


        if(is_int($port) && $port>=0 && $port<=65535){
            $this->setPort($port);
        }else{
            $this->error_message("$port is not a valid port for socket. Port must be integer and between from 0 to 65535.");
        }
        $this->setCommand();
        $exist = false;
        for ($i = 0; $i < count($this->command_types); $i++) {

            if ($this->command_types[$i] == $this->getCommand()) {
                $exist = true;
                break;
            }
        }

        if (!$exist) {
            $this->error_message('Command: ' . $this->getCommand() . ' is not exist!');
        }


    }

    /**
     * send function sends the command to the oxD server.
     * Args:
     * command (dict) - Dict representation of the JSON command string
     **/
    public function request()
    {
        $this->setParams();
        if (!self::$socket = stream_socket_client( $this->getIp() . ':' . $this->getPort(), $errno, $errstr, STREAM_CLIENT_PERSISTENT)) {
            die($errno);
        }

        /*foreach ($this->getParams() as $key => $val) {

            if (is_array($val)) {
                if (empty($val)) {
                    $this->error_message('Params: ' . $key . ' can not be empty!');
                }
            }
            if ($val == null || $val == '') {
                $this->error_message('Params: ' . $key . ' can not be null or empty!');
            }
        }*/

        $jsondata = json_encode($this->getData());

        if(!$this->is_JSON($jsondata)){
            $this->error_message('Sending parameters must be JSON.');
        }
        $lenght = strlen($jsondata);
        if($lenght<=0){
            $this->error_message("Length must be more than zero.");
        }else{
            $lenght = $lenght <= 999 ? "0" . $lenght : $lenght;
        }

        fwrite(self::$socket, utf8_encode($lenght . $jsondata));

        $this->response_json = fread(self::$socket, 8192);

        $this->response_json = str_replace(substr($this->response_json, 0, 4), "", $this->response_json);
        if(!$this->is_JSON($this->response_json)){
            $this->error_message('Reading parameter is not JSON.');
        }
        if ($this->response_json) {
            $object = json_decode($this->response_json);
            if ($object->status == 'error') {
                $this->error_message($object->data->error . ' : ' . $object->data->error_description);
            } elseif ($object->status == 'ok') {
                $this->response_object = json_decode($this->response_json);
            }
        } else {
            $this->error_message('Response is empty...');
        }
    }

    /**
     * @return mixed
     */
    public function getResponseStatus()
    {
        return $this->response_status;
    }

    /**
     * @param mixed $response_status
     */
    public function setResponseStatus()
    {
        $this->response_status = $this->getResponseObject()->status;
    }

    /**
     * @return mixed
     */
    public function getResponseData()
    {
        if (!$this->getResponseObject()) {
            $this->response_data = 'Data is empty';
            $this->error_message($this->response_data);
        } else {
            $this->response_data = $this->getResponseObject()->data;
        }
        return $this->response_data;
    }

    /**
     * @return null
     */
    public function getSocket()
    {
        return self::$socket;
    }

    /**
     * @param null $socket
     */
    public function setSocket($socket)
    {
        self::$socket = $socket;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->data = array('command' => $this->getCommand(), 'params' => $this->getParams());
        return $this->data;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    abstract function setCommand();

    /**
     * getResult function geting result from oxD server.
     * Return: response_object - The JSON response parsing to object
     **/
    public function getResponseObject()
    {
        return $this->response_object;
    }

    /**
     * function getting result from oxD server.
     * return: response_json - The JSON response from the oxD Server
     **/
    public function getResponseJSON()
    {
        return $this->response_json;
    }

    /**
     * function closing socket connection.
     **/
    public function disconnect()
    {
        fclose(self::$socket);
    }

    /**
     * @param array $params
     */
    abstract function setParams();

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * showing errors and exit.
     **/
    public function error_message($error)
    {
        die($error);
    }

    /**
     * chacking format string.
     * @param  string  $string
     * @return bool
     **/
    public function is_JSON($string){
        return is_string($string) && is_object(json_decode($string)) ? true : false;
    }

    /*
     * sending and geting data via curl.
     * @param  array   $dataArray
     * @param  string  $requestType
     * @param  string  $url
     * @param  int     $port
     * @return string
    */
    public function curl_oxd_request($requestType ='POST', $url = 'https://ce.gluu.info',$port = 443){

        if(empty($this->getData())){
            $this->error_message('Data can not be empty.');
        }
        if(!is_array($this->getData())){
            $this->error_message('Data must be array.');
        }
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $this->error_message("$url is not a valid url address");
        }
        if(is_int($port) && $port>=0 && $port<=65535){
        }else{
            $this->error_message("$port is not a valid port . Port must be integer and between from 0 to 65535.");
        }

        $data_json = str_replace("\\/", "/", $this->getData());
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PORT, $port);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Content-Length: ' . strlen($data_json))
        );
        $result = curl_exec($ch);
        return $result;
    }
}