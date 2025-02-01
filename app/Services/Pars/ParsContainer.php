<?php

namespace App\Services\Pars;

class ParsContainer {
    private $query;
    private $client;

    public function __construct(ParsClient $client, $query)
    {
        $this->query = $query;
        $this->client = $client;
    }

    /**
     * Will return response
     *
     * @return null|InventoryResponseInterface
     */
    public function getPage() {
        $uri = $this->buildUriRequest();

        try {
            return $this->client
                    ->getTransport()
                    ->execute(
                        $this->query->getMethod(),
                        $uri, 
                        $this->query->getQueryParams(),
                        $this->query->getResponseParam()
                    );
        } catch (\Exception $e){
            print_r("Exception request {$uri}");
        }

        return null;
    }

    private function buildUriRequest() {
        $uri = "";

        foreach ($this->query->getOptions() as $option) $uri .= "/". $option;

        if($this->query->getOptionsQuery() != []) {
            $uri .=  "?";

            foreach ($this->query->getOptionsQuery() as $key => $value) {
                $uri .= $key . "=" . $value . "&";
            }

            $uri = rtrim($uri, '&');
        }

        return $uri;
    }


}