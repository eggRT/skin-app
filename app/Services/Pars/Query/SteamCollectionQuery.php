<?php

namespace App\Services\Pars\Query;

class SteamCollectionQuery implements QueryInterface {
    private $options = [
        'px1'          => 'market',
        'px2'          => 'search',
    ];

    private $optionsQuery = [
        'q' => '&category_730_ItemSet%5B%5D'
    ];

    private string $method = "GET";

    public function __construct(array $options)
    {
        
    }

    public function getOptions() : array
    {
        return $this->options;
    }

    public function getOptionsQuery() : array
    {
        return $this->optionsQuery;
    }

    public function getQueryParams() : array
    {
        return [];
    }

    public function getResponseParam() : string
    {
        return '';
    }

    public function getMethod() : string
    {
        return $this->method;
    }
}