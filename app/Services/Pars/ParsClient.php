<?php

namespace App\Services\Pars;

use App\Services\Pars\Response\ResponseInterface;
use App\Services\Pars\Transport\GuzzleHttpTransport;
use App\Services\Pars\Transport\HtmlWebTransport;
use App\Services\Pars\Transport\ParsTransportInterface;

class ParsClient {
    private $parsTransport;

    public function __construct(ResponseInterface $response, string $transportType = "", string $baseUri="")
    {
        $parsTransport=null;

        switch($transportType) {
            case "guzzleHttp":
                $parsTransport = new  GuzzleHttpTransport($response, $baseUri);
                break;
            case "htmlweb":
                $parsTransport = new  HtmlWebTransport($response, $baseUri);
                break;
            default:
                $parsTransport = new  GuzzleHttpTransport($response, $baseUri);
                break;
        }

        $this->parsTransport = $parsTransport;
    }

    public function getInfo($query) {
        return new ParsContainer(
            $this,
            $query
        );
    }

    public function getTransport(): ParsTransportInterface {
        return $this->parsTransport;
    }

}