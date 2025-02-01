<?php

namespace App\Services\Pars\Response;

interface ResponseInterface {

    public function setData($stream);

    public function parsJson(string $response = "");
}