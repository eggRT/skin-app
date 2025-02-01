<?php

namespace App\Services\Pars\Query;

class ItemsByCollectionQuery implements QueryInterface {
    private $options = [
        'px1'       => 'market',
        'px2'       => 'search',
        'px3'       => 'render'
    ];

    private $optionsQuery = [
        'norender' => '1',      // not html
        'start' => 0,
        'count' => 20,          // parsing items count
        'appid' => 730,         // game id
        'category_730_ItemSet%5B%5D' => 'tag_set_office'    // collection tag        
    ];

    private string $method = "GET";

    public function __construct(array $options)
    {
        $this->setOptionsQuery($options);
    }

    private function setOptionsQuery($newOptions)
    {
        foreach($newOptions as $key => $val){
            if(array_key_exists($key, $this->optionsQuery)) {
                $this->optionsQuery[$key] = $val;
            }
        }
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