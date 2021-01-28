<?php
// src/Service/EsetAPI\endpoints\License.php
namespace App\Service\EsetAPI\endpoints;



class License
{           
    private $request;

    public function __construct($requestHandler)
    {   
        $this->request = $requestHandler;
    }
    
    public function get($id, $key) : array
    {
        $json = ['LicenseId' => $id, 'LicenseKey' => $key];
        $response = $this->request->do('POST', 'License', $json);
        return $response;
    }
}