<?php
// src/Service/Handlers\EmailHandler.php
namespace App\Service\Handlers;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class EmailHandler
{   
    private $http;
    private $base_url;
    private $api_key;
    private $authorization;

    public function __construct($base_url, $api_key, HttpClientInterface $http)
    {   
        $this->http = $http;
        $this->base_url = $base_url;
        $this->api_key = $api_key;
        $this->header = ['Authorization' => 'Bearer '.$this->api_key];
    }

    public function validateEmail($email_address): array
    {   
        $method = 'POST';
        $header = $this->header;
        $json   = ['email' => $email_address];
        $url = $this->base_url . 'validations/email';
        // try {
        //     $response = $this->http->request($method, $url, [
        //         'headers' => $this->header,
        //         'json' => $json,
        //     ]);
        // } catch (exception $e) {
        //     $response = 'Error: '. $e;
        // }
        $response = 'Error';
        $request = [
            'method'  => 'POST',
            'url'     => $url,
            'headers' => $header,
            'body'    => $json,
        ];

        return [$request, $response];
    }

}