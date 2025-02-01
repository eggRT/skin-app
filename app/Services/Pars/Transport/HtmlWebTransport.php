<?php

namespace App\Services\Pars\Transport;

use App\Services\Pars\Response\ResponseInterface;

use simplehtmldom\HtmlWeb;

class HtmlWebTransport implements ParsTransportInterface {

    private HtmlWeb $client;

    private $response;

    private $baseUri = "https://steamcommunity.com";

    public function __construct(ResponseInterface $response, string $baseUri = "") {
        if($baseUri != "") $this->baseUri = $baseUri;
            
        $client = new HtmlWeb();

        $this->client = $client;

        $this->response = $response;
    }

    public function execute(string $method, string $uri, array $options, string $responseParam) {
        try {
            $uri = $this->baseUri . $uri;

            $uri = str_replace(" ", "%20", $uri);

            $result = $this->client->load($uri);

            return $this->response->setData($result)->parsJson($responseParam);
        } catch (\Exception $e) {
            // Pass exception on if it's not 403
            print_r($e->getMessage());

            throw $e;
        }
    }
}