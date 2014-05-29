<?php


/**
 * Abstract object handling communication with the server using specific adapters.
 */
class AcApi_Transport extends AcApi_Base
{

    /**
     * Adapter type to use.
     *
     * @var string
     */
    protected $_adapterType = '';

    /**
     * Adapter options.
     *
     * @var unknown_type
     */
    protected $_adapterOptions = array();

    /**
     * Adapter object.
     *
     * @var AcApi_Transport_Adapter_Abstract
     */
    protected $_adapter = NULL;


    /**
     * Constructor.
     *
     * @param string $adapterType
     * @param array $adapterOptions
     */
    public function __construct($adapterType, Array $adapterOptions = array())
    {
        $this->_adapterType = $adapterType;
        $this->_adapterOptions = $adapterOptions;
    }


    /**
     * Initializes the adapter.
     *
     * @return AcApi_Transport_Adapter_Abstract
     */
    protected function _initAdapter()
    {
        $adapterClass = 'AcApi_Transport_Adapter_' . ucfirst($this->_adapterType);
        if (! class_exists($adapterClass)) {
            throw new AcApi_Transport_Exception(sprintf("Non-existent transport adapter class '%s'", $adapterClass));
        }
        
        try {
            $adapter = new $adapterClass($this->_adapterOptions);
        } catch (Exception $e) {
            throw new AcApi_Transport_Exception(sprintf("Error initializing adapter: %s", $e->getMessage()));
        }
        
        return $adapter;
    }


    /**
     * Returns the current adapter.
     *
     * @return AcApi_Transport_Adapter_Abstract
     */
    public function getAdapter()
    {
        if (! ($this->_adapter instanceof AcApi_Transport_Adapter_Abstract)) {
            $this->_adapter = $this->_initAdapter();
        }
        
        return $this->_adapter;
    }


    /**
     * Sends a request to the server through the adapter and returns the response object.
     *
     * @param AcApi_Request $request
     * @return AcApi_Response
     */
    public function sendRequest(AcApi_Request $request)
    {
        return $this->getAdapter()->sendRequest($request);
    }


    /**
     * Returns the current session string (through the adapter).
     *
     * @return string
     */
    public function getSessionString()
    {
        return $this->getAdapter()->getSessionString();
    }
}