<?php

namespace App\Classes;

use Log;
use SoapClient;

class SoapAdapter {

    protected $connection;
    // private const url = 'http://pitstapp.komfort.local:51600/RESTAdapter/GM';
    private const url = 'https://www.dataaccess.com/webservicesserver/numberconversion.wso?WSDL';

    public function __construct()
    {
        $this->connection = new SoapClient(self::url);
    }
    
    public function sendData($arr)
    {
        $response = $this->connection->__soapCall('some_method', $arr);
        return $response;
    }
}


?>