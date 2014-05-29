<?php


/**
 * Special case of error object holding AC specific errors.
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
    public static function initFromResponseXml(AcApi_Response_Xml $xmlResponse)
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