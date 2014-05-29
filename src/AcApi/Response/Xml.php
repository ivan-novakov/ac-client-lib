<?php


/**
 * Subclass of the SimpleXmlElement
 * - extracts the status code and the data from the response XML. 
 */
class AcApi_Response_Xml extends SimpleXmlElement
{


    /**
     * Returns the status code of the response.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return (string) $this->status[0]['code'];
    }


    /**
	 * Returns the response data.
	 *
	 * @return SimpleXmlElement
	 */
    public function getData()
    {
        return $this;
    }
}