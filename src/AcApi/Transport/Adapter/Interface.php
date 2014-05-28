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
 * Transport adapter interface.
 *
 */
interface AcApi_Transport_Adapter_Interface
{
    /**
     * Sends a request to the server and returns a response object.
     *
     * @param AcApi_Request $request
     * @return AcApi_Response
     */
    public function sendRequest (AcApi_Request $request);
    
    
    /**
     * Returns the current sesssion string.
     * 
     *@return string
     */
    /*
    public function getSessionString();
    */
}