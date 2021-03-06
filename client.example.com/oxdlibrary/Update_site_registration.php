<?php
	
	/**
	 * Gluu-oxd-library
	 *
	 * An open source application library for PHP
	 *
	 *
	 * @copyright Copyright (c) 2017, Gluu Inc. (https://gluu.org/)
	 * @license	  MIT   License            : <http://opensource.org/licenses/MIT>
	 *
	 * @package	  Oxd Library by Gluu
	 * @category  Library, Api
	 * @version   3.0.1
	 *
	 * @author    Gluu Inc.          : <https://gluu.org>
	 * @link      Oxd site           : <https://oxd.gluu.org>
	 * @link      Documentation      : <https://gluu.org/docs/oxd/3.0.1/libraries/php/>
	 * @director  Mike Schwartz      : <mike@gluu.org>
	 * @support   Support email      : <support@gluu.org>
	 * @developer Volodya Karapetyan : <https://github.com/karapetyan88> <mr.karapetyan88@gmail.com>
	 *
	 
	 *
	 * This content is released under the MIT License (MIT)
	 *
	 * Copyright (c) 2017, Gluu inc, USA, Austin
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy
	 * of this software and associated documentation files (the "Software"), to deal
	 * in the Software without restriction, including without limitation the rights
	 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	 * copies of the Software, and to permit persons to whom the Software is
	 * furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in
	 * all copies or substantial portions of the Software.
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	 * THE SOFTWARE.
	 *
	 */

	/**
	 * Oxd client update site registration class
	 *
	 * Class is connecting to oXD-server via socket, and updating registered site data in gluu server.
	 *
	 * @package		  Gluu-oxd-library
	 * @subpackage	Libraries
	 * @category	  Relying Party (RP) and User Managed Access (UMA)
	 * @see	        Client_Socket_OXD_RP
	 * @see	        Client_OXD_RP
	 * @see	        Oxd_RP_config
	 */
	
	require_once 'Client_OXD_RP.php';
	
	class Update_site_registration extends Client_OXD_RP
	{
	    /**
	     * @var string $request_oxd_id                          This parameter you must get after registration site in gluu-server
	     */
	    private $request_oxd_id = null;
	    /**
	     * @var string $request_authorization_redirect_uri      Site authorization redirect uri
	     */
	    private $request_authorization_redirect_uri = null;
	    /**
	     * @var string $request_post_logout_redirect_uri             Site logout redirect uri
	     */
	    private $request_post_logout_redirect_uri = null;
	    /**
	     * @var string $request_client_name                     OpenID provider client name
	     */
	    private $request_client_name = null;
	    /**
	     * @var array $request_acr_values                       Gluu login acr type, can be basic, duo, u2f, gplus and etc.
	     */
	    private $request_acr_values = null;
	    /**
	     * @var string $request_client_jwks_uri
	     */
	    private $request_client_jwks_uri = null;
	    /**
	     * @var string $request_client_token_endpoint_auth_method
	     */
	    private $request_client_token_endpoint_auth_method = null;
	    /**
	     * @var array $request_client_request_uris
	     */
	    private $request_client_request_uris = null;
	    /**
	     * @var array $request_client_logout_uris
	     */
	    private $request_client_logout_uris = null;
	    /**
	     * @var array $request_contacts
	     */
	    private $request_contacts = null;
	    /**
	     * @var array $request_scope                            For getting needed scopes from gluu-server
	     */
	    private $request_scope = null;
	    /**
	     * @var array $request_grant_types                     OpenID Token Request type
	     */
	    private $request_grant_types = null;
	    /**
	     * @var array $request_ui_locales
	     */
	    private $request_ui_locales = null;
	    /**
	     * @var array $request_claims_locales
	     */
	    private $request_claims_locales = null;
	    /**
	     * @var array $request_grant_types                     OpenID Token Request type
	     */
	    private $request_client_sector_identifier_uri = null;
	    /**
	     * @var array $request_response_types                   OpenID Authentication response types
	     */
	    private $request_response_types = null;
	    /**
	     * Response parameter from oXD-server
	     * It is basic parameter for other protocols
	     *
	     * @var string $response_oxd_id
	     */
	    private $response_oxd_id;
	
	    /**
	     * Constructor
	     *
	     * @return	void
	     */
	    public function __construct($config = null)
	    {
                if(is_array($config)){
                    Client_Socket_OXD_RP::setUrl($config["host"].$config["update_site_registration"]);
                }
	        parent::__construct(); // TODO: Change the autogenerated stub
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestClientSectorIdentifierUri()
	    {
	        return $this->request_client_sector_identifier_uri;
	    }
	
	    /**
	     * @param array $request_client_sector_identifier_uri
	     */
	    public function setRequestClientSectorIdentifierUri($request_client_sector_identifier_uri)
	    {
	        $this->request_client_sector_identifier_uri = $request_client_sector_identifier_uri;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestClaimsLocales()
	    {
	        return $this->request_claims_locales;
	    }
	
	    /**
	     * @param array $request_claims_locales
	     */
	    public function setRequestClaimsLocales($request_claims_locales)
	    {
	        $this->request_claims_locales = $request_claims_locales;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestUiLocales()
	    {
	        return $this->request_ui_locales;
	    }
	
	    /**
	     * @param array $request_ui_locales
	     */
	    public function setRequestUiLocales($request_ui_locales)
	    {
	        $this->request_ui_locales = $request_ui_locales;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestClientLogoutUris()
	    {
	        return $this->request_client_logout_uris;
	    }
	
	    /**
	     * @param array $request_client_logout_uris
	     * @return void
	     */
	    public function setRequestClientLogoutUris($request_client_logout_uris)
	    {
	        $this->request_client_logout_uris = $request_client_logout_uris;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestResponseTypes()
	    {
	        return $this->request_response_types;
	    }
	
	    /**
	     * @param array $request_response_types
	     * @return void
	     */
	    public function setRequestResponseTypes($request_response_types)
	    {
	        $this->request_response_types = $request_response_types;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestGrantTypes()
	    {
	        return $this->request_grant_types;
	    }
	
	    /**
	     * @param array $request_grant_types
	     * @return void
	     */
	    public function setRequestGrantTypes($request_grant_types)
	    {
	        $this->request_grant_types = $request_grant_types;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestScope()
	    {
	        return $this->request_scope;
	    }
	
	    /**
	     * @param array $request_scope
	     * @return void
	     */
	    public function setRequestScope($request_scope)
	    {
	        $this->request_scope = $request_scope;
	    }
	
	    /**
	     * @return string
	     */
	    public function getRequestPostLogoutRedirectUri()
	    {
	        return $this->request_post_logout_redirect_uri;
	    }
	
	    /**
	     * @param string $request_post_logout_redirect_uri
	     * @return void
	     */
	    public function setRequestPostLogoutRedirectUri($request_post_logout_redirect_uri)
	    {
	        $this->request_post_logout_redirect_uri = $request_post_logout_redirect_uri;
	    }
	
	    /**
	     * @return string
	     */
	    public function getRequestClientJwksUri()
	    {
	        return $this->request_client_jwks_uri;
	    }
	
	    /**
	     * @param string $request_client_jwks_uri
	     * @return void
	     */
	    public function setRequestClientJwksUri($request_client_jwks_uri)
	    {
	        $this->request_client_jwks_uri = $request_client_jwks_uri;
	    }
	
	    /**
	     * @return string
	     */
	    public function getRequestClientTokenEndpointAuthMethod()
	    {
	        return $this->request_client_token_endpoint_auth_method;
	    }
	
	    /**
	     * @param string $request_client_token_endpoint_auth_method
	     * @return void
	     */
	    public function setRequestClientTokenEndpointAuthMethod($request_client_token_endpoint_auth_method)
	    {
	        $this->request_client_token_endpoint_auth_method = $request_client_token_endpoint_auth_method;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestClientRequestUris()
	    {
	        return $this->request_client_request_uris;
	    }
	
	    /**
	     * @param array $request_client_request_uris
	     * @return void
	     */
	    public function setRequestClientRequestUris($request_client_request_uris)
	    {
	        $this->request_client_request_uris = $request_client_request_uris;
	    }
	
	    /**
	     * @return string
	     */
	    public function getRequestAuthorizationRedirectUri()
	    {
	        return $this->request_authorization_redirect_uri;
	    }
	
	    /**
	     * @param string $request_authorization_redirect_uri
	     * @return void
	     */
	    public function setRequestAuthorizationRedirectUri($request_authorization_redirect_uri)
	    {
	        $this->request_authorization_redirect_uri = $request_authorization_redirect_uri;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestAcrValues()
	    {
	        return $this->request_acr_values;
	    }
	
	    /**
	     * @param array $request_acr_values
	     * @return void
	     */
	    public function setRequestAcrValues($request_acr_values = 'basic')
	    {
	        $this->request_acr_values = $request_acr_values;
	    }
	
	    /**
	     * @return array
	     */
	    public function getRequestContacts()
	    {
	        return $this->request_contacts;
	    }
	
	    /**
	     * @param array $request_contacts
	     * @return void
	     */
	    public function setRequestContacts($request_contacts)
	    {
	        $this->request_contacts = $request_contacts;
	    }
	
	    /**
	     * @return string
	     */
	    public function getResponseOxdId()
	    {
	        $this->response_oxd_id = $this->getResponseData()->oxd_id;
	        return $this->response_oxd_id;
	    }
	
	    /**
	     * @return string
	     */
	    public function getRequestClientName()
	    {
	        return $this->request_client_name;
	    }
	
	    /**
	     * @param string $request_client_name
	     */
	    public function setRequestClientName($request_client_name)
	    {
	        $this->request_client_name = $request_client_name;
	    }
	
	    /**
	     * @param string $response_oxd_id
	     * @return void
	     */
	    public function setResponseOxdId($response_oxd_id)
	    {
	        $this->response_oxd_id = $response_oxd_id;
	    }
	    /**
	     * @return string
	     */
	    public function getRequestOxdId()
	    {
	        return $this->request_oxd_id;
	    }
	
	    /**
	     * @param string $request_oxd_id
	     * @return void
	     */
	    public function setRequestOxdId($request_oxd_id)
	    {
	        $this->request_oxd_id = $request_oxd_id;
	    }
	
	    /**
	     * Protocol command to oXD server
	     * @return void
	     */
	    public function setCommand()
	    {
	        $this->command = 'update_site_registration';
	    }
	    /**
	     * Protocol parameter to oXD server
	     * @return void
	     */
	    public function setParams()
	    {
	        $this->params = array(
	            "oxd_id" => $this->getRequestOxdId(),
	            "authorization_redirect_uri" => $this->getRequestAuthorizationRedirectUri(),
	            "post_logout_redirect_uri" => $this->getRequestPostLogoutRedirectUri(),
	            "client_logout_uris"=> $this->getRequestClientLogoutUris(),
	            "response_types"=> $this->getRequestResponseTypes(),
	            "grant_types" => $this->getRequestGrantTypes(),
	            "scope" => $this->getRequestScope(),
	            "acr_values" => $this->getRequestAcrValues(),
	            "client_name" => $this->getRequestClientName(),
	            "client_secret_expires_at"=> 3080736637943,
	            "client_jwks_uri" => $this->getRequestClientJwksUri(),
	            "client_token_endpoint_auth_method" => $this->getRequestClientTokenEndpointAuthMethod(),
	            "client_request_uris" => $this->getRequestClientRequestUris(),
	            "client_sector_identifier_uri" => $this->getRequestClientSectorIdentifierUri(),
	            "contacts" => $this->getRequestContacts(),
	            "ui_locales"=> $this->getRequestUiLocales(),
	            "claims_locales"=> $this->getRequestClaimsLocales()
	        );
	    }
	
	}
