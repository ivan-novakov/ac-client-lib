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
 * Base class with some universal methods and properties.
 *
 */
class AcApi_Base
{
    /**
     * Stores options
     *
     * @var ArrayObject | NULL
     */
    protected $_options = NULL;
    
    // ??
    protected $_log = NULL;

    /**
     * Sets the given options.
     *
     * @param array $options Array of options, keys = option name, value = option value.
     * @param bool $reset If set, the existing options are deleted before the new ones are set.
     */
    public function setOptions (Array $options, $reset = false)
    {
        if ($reset || ! ($this->_options instanceof ArrayObject)) {
            $this->_options = new ArrayObject($options);
            return;
        }
        
        foreach ($options as $key => $value) {
            $this->_options->offsetSet($key, $value);
        }
    }

    /**
     * Returns required option value
     *
     * @param string $optionName
     * @return mixed
     */
    public function getOption ($optionName)
    {
        if (($this->_options instanceof ArrayObject) && $this->_options->offsetExists($optionName)) {
            return $this->_options->offsetGet($optionName);
        }
        
        return NULL;
    }
    
    
    /**
     * Initiates a Zend_Log object.
     *
     * @param unknown_type $stream The stream to be used with Zend_Log
     * @return Zend_Log
     */
    protected function _initLog($stream)
    {
    	if (!$stream) {
    		$stream = 'php://output';
    	}
    	$writer = new Zend_Log_Writer_Stream($stream);
    	$log = new Zend_Log($writer);
    	
    	return $log; 
    }
    
    
    /**
     * For debugging purposes.
     *
     * @return string
     */
    public function __toString()
    {
    	return print_r($this, true);
    }
}