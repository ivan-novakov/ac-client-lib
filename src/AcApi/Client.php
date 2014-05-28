<?php
/**
 * This file is part of the AC PHP API Library.    
 *
 * The AC PHP API Library is free software: you can redistribute it and/or modify    
 * it under the terms of the GNU Lesser General Public License as published by    
 * the Free Software Foundation, either version 3 of the License, or    
 * (at your option) any later version.    
 * 
 * The AC PHP API Library is distributed in the hope that it will be useful,    
 * but WITHOUT ANY WARRANTY; without even the implied warranty of    
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    
 * GNU Lesser General Public License for more details.    
 * 
 * You should have received a copy of the GNU Lesser General Public License    
 * along with the AC PHP API Library.  If not, see <http://www.gnu.org/licenses/>. 
 * 
 * @author Ivan Novakov <ivan.novakov@debug.cz>
 * @copyright Copyright (c) 2009 CESNET, z. s. p. o. (http://www.ces.net/)
 * @license LGPL (http://www.gnu.org/licenses/lgpl.txt)
 * 
 */

/**
 * Provides basic API to the AC server, handles requests, remote calls and returns responses.
 *
 */
class AcApi_Client extends AcApi_Base
{
    /**
     * The transport object used for communication.
     *
     * @var AcApi_Transport
     */
    protected $_transport = NULL;
    
    /**
     * Stores the last request.
     *
     * @var AcApi_Request
     */
    protected $_lastRequest = NULL;


    /**
     * Constructor.
     * Sets the options and initializes loggin.
     *
     * @param array $options
     */
    public function __construct (Array $options)
    {
        $this->setOptions($options);
        
        $this->_log = $this->_initLog($this->getOption('log'));
    }


    /**
     * Returns the currently used transport object.
     *
     * @return AcApi_Transport
     */
    public function getTransport ()
    {
        if (! ($this->_transport instanceof AcApi_Transport)) {
            $this->_transport = $this->_initTransport();
        }
        
        return $this->_transport;
    }


    /**
     * Returns the last request object.
     *
     * @return AcApi_Request
     */
    public function getLastRequest ()
    {
        return $this->_lastRequest;
    }


    /**
     * Initializes the transport object based on the given options.
     *
     * @return AcApi_Transport
     */
    protected function _initTransport ()
    {
        $type = $this->getOption('type');
        if (! $type) {
            throw new AcApi_Exception('Transport type not set');
        }
        
        $uri = $this->getOption('uri');
        if (! $uri) {
            throw new AcApi_Exception('Server URI not set');
        }
        
        $transport = new AcApi_Transport($type, array(
            'server_uri' => $uri, 
            'cookie_name' => $this->getOption('cookie')
        ));
        
        return $transport;
    }


    /**
     * Performs a login to the server either with the credentials passed as options, 
     * or with credentials passed as method parameters. 
     *
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function login ($username = '', $password = '')
    {
        if (! $username) {
            $username = $this->getOption('username');
        }
        
        if (! $password) {
            $password = $this->getOption('password');
        }
        
        return $this->_apiCall('login', array(
            'login' => $username, 
            'password' => $password
        ));
    }


    /**
     * Performs a logout from the server.
     *
     * @return AcApi_Response
     */
    public function logout ()
    {
        return $this->_apiCall('logout');
    }


    /**
     * Public alias for _apiCall
     *
     * @param string $action
     * @param array $params
     * @return mixed
     */
    public function apiCall ($action, Array $params = array())
    {
        return $this->_apiCall($action, $params);
    }


    /**
     * Performs a remote API call.
     *
     * @param string $action The remote method name according to the API specification.
     * @param array $params Array of parameters for the API call.
     * @return mixed
     */
    protected function _apiCall ($action, Array $params = array())
    {
        
        $transport = $this->getTransport();
        if (! is_array($params)) {
            $request = new AcApi_Request($action);
        } else {
            $request = new AcApi_Request($action, $params);
        }
        
        $this->_lastRequest = $request;
        $response = $transport->sendRequest($request);
        
        return $response;
    }


    /**
     * A '__call' magic method implementation for convenience. 
     * Catches method calls in the format 'api_methodName' 
     * and transforms it into 'method_name' remote API call.
     * For example:
     * 
     * $client->api_principalList($params); 
     *
     * is transformed into:
     * 
     * $client->_apiCall('principal-list', $params);
     * 
     * @param unknown_type $method
     * @param unknown_type $args
     * @return unknown
     */
    public function __call ($method, $args)
    {
        if (! preg_match('/^api_(.+)$/', $method, $matches)) {
            throw new AcApi_Exception(sprintf("Unknown method '%s'.", $method));
        }
        
        $apiCall = $this->_camelcaseToDash($matches[1]);
        if (! $apiCall) {
            throw new AcApi_Exception(sprintf("Badly formed API call '%s' [%s].", $matches[1], $method));
        }
        
        if (is_array($args) && isset($args[0])) {
            $params = $args[0];
        } else {
            $params = array()

            ;
        }
        
        if (! empty($params) && ! is_array($params)) {
            throw new AcApi_Exception('Bad arguments\' format, associative array expected.');
        }
        
        return $this->_apiCall($apiCall, $params);
    }


    /**
     * Transforms a camel-case string into dash-delimited string.
     * For example:
     * principalList --> principal-list
     *
     * @param string $value
     * @return string
     */
    protected function _camelcaseToDash ($value)
    {
        if (! $splitString = preg_replace('/(?!^)[[:upper:]]/', ' \0', $value)) {
            return NULL;
        }
        
        return strtolower(implode('-', explode(' ', $splitString)));
    }


    /**
     * Debugging short-hand method.
     *
     * @param mixed $value
     */
    protected function _debug ($value)
    {
        $this->_log->debug(print_r($value, true));
    }

}