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
 * Special case of error object holding AC specific errors.
 *
 */
class AcApi_Response_Error_Ac extends AcApi_Response_Error
{

    /**
     * Constructor.
     * Parses the response XML object and initializes the AC error object.
     *
     * @param AcApi_Response_Xml $xmlResponse
     * @return AcApi_Response_Error_Ac
     */
    public static function initFromResponseXml (AcApi_Response_Xml $xmlResponse)
    {
        $code = $xmlResponse->getStatusCode();
        
        $message = sprintf("Adobe Connect Error [%s]: ", $code);
        switch ($code) {
            case 'no-access':
                $message .= $xmlResponse->status['subcode'];
                break;
            
            case 'invalid':
                $invalid = $xmlResponse->status[0]->invalid[0];
                $message .= sprintf("subcode: %s, field: %s, type: %s", $invalid['subcode'], $invalid['field'], $invalid['type']);
                break;
            
            default:
                if ($xmlResponse->status['subcode']) {
                    $message .= $xmlResponse->status['subcode'];
                }
                $message .= $code;
                break;
        }
        
        return new self($code, $message, AcApi_Response_Error::ERROR_TYPE_AC);
    }

}