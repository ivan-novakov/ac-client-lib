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
 * Abstract object handling communication with the server using specific adapters.
 *
 */
class AcApi_Transport extends AcApi_Base
{
    /**
     * Adapter type to use.
     *
     * @var string
     */
    protected $_adapterType = '';
    
    /**
     * Adapter options.
     *
     * @var unknown_type
     */
    protected $_adapterOptions = array(
        
    );
    
    /**
     * Adapter object.
     *
     * @var AcApi_Transport_Adapter_Abstract
     */
    protected $_adapter = NULL;


    /**
     * Constructor.
     *
     * @param string $adapterType
     * @param array $adapterOptions
     */
    public function __construct ($adapterType, Array $adapterOptions = array())
    {
        $this->_adapterType = $adapterType;
        $this->_adapterOptions = $adapterOptions;
    }


    /**
     * Initializes the adapter.
     *
     * @return AcApi_Transport_Adapter_Abstract
     */
    protected function _initAdapter ()
    {
        $adapterClass = 'AcApi_Transport_Adapter_' . ucfirst($this->_adapterType);
        try {
            Zend_Loader::loadClass($adapterClass);
            $adapter = new $adapterClass($this->_adapterOptions);
        } catch (Exception $e) {
            throw new AcApi_Transport_Exception(sprintf("Error initializing adapter: %s", $e->getMessage()));
        }
        
        return $adapter;
    }


    /**
     * Returns the current adapter.
     *
     * @return AcApi_Transport_Adapter_Abstract
     */
    public function getAdapter ()
    {
        if (! ($this->_adapter instanceof AcApi_Transport_Adapter_Abstract)) {
            $this->_adapter = $this->_initAdapter();
        }
        
        return $this->_adapter;
    }


    /**
     * Sends a request to the server through the adapter and returns the response object.
     *
     * @param AcApi_Request $request
     * @return AcApi_Response
     */
    public function sendRequest (AcApi_Request $request)
    {
        return $this->getAdapter()->sendRequest($request);
    }
    
    
    /**
     * Returns the current session string (through the adapter).
     *
     * @return string
     */
    public function getSessionString()
    {
    	return $this->getAdapter()->getSessionString();
    }

}