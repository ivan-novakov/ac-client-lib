<?php


/**
 * Transport adapter interface.
 */
interface AcApi_Transport_Adapter_Interface
{


    /**
     * Sends a request to the server and returns a response object.
     *
     * @param AcApi_Request $request
     * @return AcApi_Response
     */
    public function sendRequest(AcApi_Request $request);
}