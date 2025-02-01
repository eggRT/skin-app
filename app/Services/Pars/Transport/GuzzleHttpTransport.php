<?php

namespace App\Services\Pars\Transport;

use App\Services\Pars\Response\ResponseInterface;

use GuzzleHttp\Client;

class GuzzleHttpTransport implements ParsTransportInterface {

    private Client $client;

    private $response;

    public function __construct(ResponseInterface $response, string $baseUri = "") {
        if($baseUri=="") $baseUri = 'https://steamcommunity.com';
            
        $client = new Client([
            'base_uri' => $baseUri
        ]);

        $this->client = $client;

        $this->response = $response;
    }

    public function execute(string $method, string $uri, array $options, string $responseParam){
        try {
            if($method == "GET") {
                print_r($uri . "\n");

                $result = $this->client->get($uri, $options);
            }
            else if($method == "POST")
                $result = $this->client->post($uri, $options);

            $html = $result->getBody()->getContents();

            return $this->response->setData($html)->parsJson($responseParam);
        } catch (\Exception $e) {
            // Pass exception on if it's not 403
            throw $e;
        }
    }
}