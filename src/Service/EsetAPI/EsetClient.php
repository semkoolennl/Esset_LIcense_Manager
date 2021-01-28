<?php
// src/Service/EsetAPI\EsetClient.php
namespace App\Service\EsetAPI;

use App\Service\EsetAPI\endpoints\License;
use App\Service\EsetAPI\RequestHandler;

class EsetClient
{   
    private $credentials;
    public  $license;
    private $requestHandler;
    
    public function __construct($eset_guid, $eset_key, RequestHandler $requestHandler)
    {   
        $this->requestHandler = $requestHandler;
        $this->setCredentials($eset_guid, $eset_key);
        $this->license = new License($requestHandler);
    }

    public function setCredentials($guid, $key)
    {
        $this->requestHandler->setCredentials($guid, $key);
    }

    public function getCredentials()
    {
        return $this->requestHandler->getCredentials();
    }
}