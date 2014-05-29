<?php


/**
 * Implements the request object, containing the request information.
 * Converts the request information into XML readable by the server.
 */
class AcApi_Request extends AcApi_Base
{

    /**
     * The remote method name.
     *
     * @var string
     */
    protected $_action = '';

    /**
     * The parameters for the remote call.
     *
     * @var unknown_type
     */
    protected $_params = array()

    ;


    /**
     * Constructor.
     *
     * @param unknown_type $action
     * @param array $params
     */
    public function __construct($action, Array $params = array())
    {
        $this->set($action, $params);
    }


    /**
     * Convenience setter.
     *
     * @param string $action
     * @param array $params
     */
    public function set($action, Array $params = array())
    {
        $this->setAction($action);
        $this->setParams($params);
    }


    /**
     * Sets the remote method name.
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->_action = $action;
    }


    /**
     * Sets the parameters for the remote call.
     *
     * @param array $params
     */
    public function setParams(Array $params = array())
    {
        $this->_params = $params;
    }


    /**
     * Returns the remote method name.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }


    /**
     * Returns the parameters for the remote call.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /**
     * Generates a XML encoded API call readable by the AC server.
     *
     * @return string
     */
    public function toXml()
    {
        if (! $this->getAction()) {
            throw new AcApi_Exception('No action specified in request.');
        }
        
        $dom = new DomDocument('1.0', 'utf-8');
        $params = $dom->createElement('params');
        
        $dom->appendChild($params);
        
        // set action
        $param = $dom->createElement('param', $this->getAction());
        $param->setAttribute('name', 'action');
        $params->appendChild($param);
        
        // set params
        foreach ($this->getParams() as $paramName => $paramValue) {
            $param = $dom->createElement('param', $paramValue);
            $param->setAttribute('name', $paramName);
            
            $params->appendChild($param);
        }
        
        return $dom->saveXml();
    }
}