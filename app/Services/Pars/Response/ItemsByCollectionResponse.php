<?php

namespace App\Services\Pars\Response;

class ItemsByCollectionResponse implements ResponseInterface{
    private $data;

    /**
     * set html response
     * @param string $stream
     */
    public function setData($stream) {
        $this->data = json_decode($stream, true);
        return $this;
    }

    /**
     * Get all items for this collection
     */
    public function parsJson(string $response = "")
    {
        return $this->data;
    }
}