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
 * Abstract class implementing the common features for all adapters.
 *
 */
class AcApi_Transport_Adapter_Abstract extends AcApi_Base
{
    /**
     * The raw response.
     *
     * @var string
     */
    protected $_rawResponse = NULL;


    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct (Array $options)
    {
        $this->setOptions($options);
        $this->_init();
    }


    /**
     * Returns the raw response from the server.
     *
     * @return unknown
     */
    public function getRawResponse ()
    {
        return $this->_rawResponse;
    }


    /**
     * Reserved for additional initializations.
     *
     */
    protected function _init ()
    {}


    /**
     * Builds the request to be sent (in XML).
     *
     * @param AcApi_Request $request
     * @return string
     */
    protected function _buildRequest (AcApi_Request $request)
    {
        return $request->toXml();
    }


    /**
     * Initializes the response object based on the XML returned from the server.
     *
     * @param string $xml
     * @param array $responseMetadata
     * @return AcApi_Response
     */
    protected function _createResponse ($xml, $responseMetadata = array())
    {
        $response = AcApi_Response::initFromXml($xml);
        $response->setMetadata($responseMetadata);
        
        return $response;
    }


    /**
     * Initializes and returns a transport error object.
     *
     * @param string $message
     * @return AcApi_Response_Error
     */
    protected function _transportErrorResponse ($message)
    {
        return $this->_errorResponse('transport', $message, AcApi_Response_Error::ERROR_TYPE_TRANSPORT);
    }


    /**
     * Initializes and returns an error object.
     *
     * @param int $code
     * @param string $message
     * @param string $type
     * @return AcApi_Response_Error
     */
    protected function _errorResponse ($code, $message, $type)
    {
        $error = new AcApi_Response_Error($code, $message, $type);
        $response = new AcApi_Response();
        $response->setError($error);
        return $response;
    }

}