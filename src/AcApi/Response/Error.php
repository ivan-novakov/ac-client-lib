<?php


/**
 * Error response object.
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