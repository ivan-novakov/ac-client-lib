<?php


/**
 * Holds the response data from the AC server, after a remote API call
 * has been performed.
 */
class AcApi_Response extends AcApi_Base
{

    /**
     * The error object.
     *
     * @var AcApi_Response_Error
     */
    protected $_error = NULL;

    /**
     * Raw XML received from the server.
     *
     * @var SimpleXmlElement
     */
    protected $_rawXml = '';

    /**
     * Raw response data received from the server (without the status code).
     *
     * @var mixed
     */
    protected $_rawData = NULL;

    /**
     * Response metadata - structured data associated with the remote call.
     *
     * @var unknown_type
     */
    protected $_metadata = NULL;


    /**
     * Constructor - initializes the response object.
     *
     * @param string $xml Raw XML response.
     * @return AcApi_Response
     */
    public static function initFromXml($xml)
    {
        $response = new self();
        $response->parseXml($xml);
        
        return $response;
    }


    /**
     * Parses the raw XML response, checks the status code and extracts the data.
     *
     * @param string $xml Raw XML response.
     */
    public function parseXml($xml)
    {
        $this->setXml($xml);
        
        try {
            $parsedXml = @ new AcApi_Response_Xml($xml);
        } catch (Exception $e) {
            $error = new AcApi_Response_Error('xml', $e->getMessage(), AcApi_Response_Error::ERROR_TYPE_XML);
            $this->setError($error);
            return;
        }
        
        $code = $parsedXml->getStatusCode();
        
        if ('ok' != $code) {
            $error = AcApi_Response_Error_Ac::initFromResponseXml($parsedXml);
            $this->setError($error);
            return;
        }
        
        $data = $parsedXml->getData();
        if ($data) {
            $this->setRawData($data);
        }
    }


    /**
     * Sets the raw XML response.
     *
     * @param SimpleXmlElement $xml
     */
    public function setXml($xml)
    {
        $this->_rawXml = $xml;
    }


    /**
     * Returns the raw XML response.
     *
     * @return SimpleXmlElement
     */
    public function getXml()
    {
        return $this->_rawXml;
    }


    /**
     * Sets the error object.
     *
     * @param AcApi_Response_Error $error
     */
    public function setError(AcApi_Response_Error $error)
    {
        $this->_error = $error;
    }


    /**
     * Returns the current error object associated with the response.
     *
     * @return AcApi_Response_Error
     */
    public function getError()
    {
        return $this->_error;
    }


    /**
     * Checks if there has been an error.
     *
     * @return bool
     */
    public function isError()
    {
        return ($this->_error instanceof AcApi_Response_Error);
    }


    /**
     * Convenience method, see isError().
     *
     * @return bool
     */
    public function isOk()
    {
        return (! $this->isError());
    }
    
    /*
    public function setData (Array $data)
    {
        $this->_data = $data;
    }


    public function getData ()
    {
        return $this->_data;
    }

	*/
    
    /**
     * Sets the raw data object extracted from the response.
     *
     * @param AcApi_Response_Xml $rawData
     */
    public function setRawData(AcApi_Response_Xml $rawData)
    {
        $this->_rawData = $rawData;
    }


    /**
     * Returns the raw data object.
     *
     * @return AcApi_Response_Xml
     */
    public function getRawData()
    {
        return $this->_rawData;
    }


    /**
     * Sets the response metadata.
     *
     * @param array $metadata
     */
    public function setMetadata(Array $metadata = array())
    {
        $this->_metadata = new ArrayObject($metadata);
    }


    /**
     * Returns the response metadata.
     *
     * @return array
     */
    public function getMetadata()
    {
        if (! ($this->_metadata instanceof ArrayObject)) {
            $this->_metadata = new ArrayObject();
        }
        
        return $this->_metadata;
    }


    /**
     * Returns the cookie associated with the remote call.
     *
     * @return string
     */
    public function getCookie()
    {
        if ($this->getMetadata()->offsetExists('cookie')) {
            return $this->getMetadata()->offsetGet('cookie');
        }
        
        return NULL;
    }


    /**
     * Returns the current session string.
     *
     * @return string
     */
    public function getSessionString()
    {
        if (($cookie = $this->getCookie()) instanceof Zend_Http_Cookie) {
            return $cookie->getValue();
        }
        
        return '';
    }
}