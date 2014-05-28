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
 * Implements the request object, containing the request information.
 * Converts the request information into XML readable by the server.
 *
 */
class AcApi_Request extends AcApi_Base
{
    /**
     * The remote method name.
     *
     * @var string
     */
    protected $_action = '';
    
    /**
     * The parameters for the remote call.
     *
     * @var unknown_type
     */
    protected $_params = array(
        
    );


    /**
     * Constructor.
     *
     * @param unknown_type $action
     * @param array $params
     */
    public function __construct ($action, Array $params = array())
    {
        $this->set($action, $params);
    }


    /**
     * Convenience setter.
     *
     * @param string $action
     * @param array $params
     */
    public function set ($action, Array $params = array())
    {
        $this->setAction($action);
        $this->setParams($params);
    }


    /**
     * Sets the remote method name.
     *
     * @param string $action
     */
    public function setAction ($action)
    {
        $this->_action = $action;
    }


    /**
     * Sets the parameters for the remote call.
     *
     * @param array $params
     */
    public function setParams (Array $params = array())
    {
        $this->_params = $params;
    }


    /**
     * Returns the remote method name.
     *
     * @return string
     */
    public function getAction ()
    {
        return $this->_action;
    }


    /**
     * Returns the parameters for the remote call.
     *
     * @return array
     */
    public function getParams ()
    {
        return $this->_params;
    }


    /**
     * Generates a XML encoded API call readable by the AC server.
     *
     * @return string
     */
    public function toXml ()
    {
        if (! $this->getAction()) {
            throw new AcApi_Exception('No action specified in request.');
        }
        
        $dom = new DomDocument('1.0', 'utf-8');
        $params = $dom->createElement('params');
        
        $dom->appendChild($params);
        
        // set action
        $param = $dom->createElement('param', $this->getAction());
        $param->setAttribute('name', 'action');
        $params->appendChild($param);
        
        // set params
        foreach ($this->getParams() as $paramName => $paramValue) {
            $param = $dom->createElement('param', $paramValue);
            $param->setAttribute('name', $paramName);
            
            $params->appendChild($param);
        }
        
        return $dom->saveXml();
    }
}