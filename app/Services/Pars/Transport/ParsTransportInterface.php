<?php

namespace App\Services\Pars\Transport;

interface ParsTransportInterface {
    /**
     * Get response from request.
     */
    public function execute(string $method, string $uri, array $options, string $responseParam);
}