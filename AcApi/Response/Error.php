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
 * Error response object.
 *
 */
class AcApi_Response_Error extends AcApi_Base
{

    /**
     * Transport error type.
     *
     */
    const ERROR_TYPE_TRANSPORT = 'transport';
    
    /**
     * XML error type.
     *
     */
    const ERROR_TYPE_XML = 'xml';
    
    /**
     * AC error type.
     *
     */
    const ERROR_TYPE_AC = 'aconnect';
    
    
    /**
     * Constructor.
     *
     * @param int $code
     * @param string $message
     * @param string $type
     */
    public function __construct($code, $message, $type = self::ERROR_TYPE_AC)
    {
        $this->_code = $code;
        $this->_message = $message;
        $this->_type = $type;
    }
    
    
    /**
     * Returns the error code.
     *
     * @return int
     */
    public function getCode()
    {
    	return $this->_code;
    }
    
    
    /**
     * Returns the error message.
     *
     * @return string
     */
    public function getMessage()
    {
    	return $this->_message;
    }
    
    
    /**
     * Returns the error type.
     *
     * @return string
     */
    public function getType()
    {
    	return $this->_type;
    }

}