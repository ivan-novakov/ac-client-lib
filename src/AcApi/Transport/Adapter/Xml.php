<?php


/**
 * XML transport adapter implementation 
 * - reads and handles XML responses from the AC server through HTTP communication.
 */
class AcApi_Transport_Adapter_Xml extends AcApi_Transport_Adapter_Abstract implements AcApi_Transport_Adapter_Interface
{

    /**
     * The HTTP client object
     *
     * @var Zend_Http_Client
     */
    protected $_client = NULL;


    /**
     * Reserved for additional initializations.
     *
     */
    protected function _init()
    {}


    /**
     * Returns the HTTP client object.
     *
     * @return Zend_Http_Client
     */
    public function getClient()
    {
        if (! ($this->_client instanceof Zend_Http_Client)) {
            $this->_client = $this->_initHttpClient();
        }
        return $this->_client;
    }


    /**
     * Send a XML request over HTTP, reads the response and returns
     * the response data as a response object.
     *
     * @param AcApi_Request $request
     * @return mixed
     */
    public function sendRequest(AcApi_Request $request)
    {
        $xml = $this->_buildRequest($request);
        
        try {
            $httpClient = $this->getClient();
            $httpClient->setRawData($xml, 'text/xml');
            $httpResponse = $httpClient->request('POST');
        } catch (Exception $e) {
            return $this->_transportErrorResponse(sprintf("Exception '%s': %s", get_class($e), $e->getMessage()));
        }
        
        $this->_rawResponse = $httpResponse;
        
        if (! $httpResponse->isSuccessful()) {
            return $this->_transportErrorResponse(sprintf("[%d] %s", $httpResponse->getStatus(), $httpResponse->getMessage()));
        }
        
        $cookieName = $this->getOption('cookie_name');
        $uriParts = parse_url($this->getOption('server_uri'));
        
        $cookieDomain = $uriParts['scheme'] . '://' . $uriParts['host'];
        $cookie = $httpClient->getCookieJar()->getCookie($cookieDomain, $cookieName);
        $responseMetadata = array(
            'cookie' => $cookie
        );
        return $this->_createResponse($httpResponse->getBody(), $responseMetadata);
    }


    /**
     * Initializes the HTTP client object.
     *
     * @return Zend_Http_Client
     */
    protected function _initHttpClient()
    {
        $serverUri = $this->getOption('server_uri');
        if (! $serverUri) {
            throw new AcApi_Transport_Adapter_Exception('No server URI set.');
        }
        
        try {
            $client = new Zend_Http_Client($serverUri);
        } catch (Zend_Uri_Exception $e) {
            throw new AcApi_Transport_Exception(sprintf("%s '%s'", $e->getMessage(), $serverUri));
        }
        $client->setCookieJar();
        
        return $client;
    }
}