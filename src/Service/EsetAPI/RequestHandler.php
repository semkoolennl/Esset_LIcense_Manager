<?php
// src/Service/EsetAPI\RequestHandler.php
namespace App\Service\EsetAPI;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class RequestHandler
{   
    private $http;
    private $base_url;
    private $credentials;

    public function __construct($base_url, HttpClientInterface $http)
    {   
        $this->http = $http;
        $this->base_url = $base_url;
    }

    public function do($method, $endpoint, $json): array
    {   
        $credentials = $this->credentials;
        // $signature = hash('sha256', $json . $credentials['ESET_GUID'] . $credentials['ESET_KEY']);
        $signature = 'test';
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-ESET-Guid' => $credentials['ESET_GUID'],
            'X-ESET-Signature' => $signature,
        ];

        $url = $this->base_url . $endpoint;
        // $response = $this->http->request($method, $url, [
        //     'headers' => $headers,
        //     'body' => json_encode($json),
        // ]);
        $request = [
            'method'  => $method,
            'url'     => $url,
            'headers' => $headers,
            'body'    => $json,
        ];

        return $request;
    }

    public function setCredentials($guid, $key)
    {
        $this->credentials = [
            'ESET_GUID' => $guid,
            'ESET_KEY'  => $key,
        ];
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

}