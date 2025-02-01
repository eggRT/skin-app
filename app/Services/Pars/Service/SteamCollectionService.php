<?php

namespace App\Services\Pars\Service;

use App\Models\Collection;
use App\Models\ParsCollection;
use App\Services\Pars\ParsClient;
use App\Services\Pars\Query\SteamCollectionQuery;
use App\Services\Pars\Response\SteamCollectionResponse;
use Carbon\Carbon;

class SteamCollectionService {
    private ParsClient $client;

    private $response;

    public function __construct()
    {
        $this->client = new ParsClient(new SteamCollectionResponse, 'htmlweb');
        $inf = $this->client->getInfo(new SteamCollectionQuery([]));
        $this->response = $inf->getPage();
    }

    /**
     * Parsing collections
     * 
     */
    private function saveColections()
    {
        foreach($this->response as $key => $collectionInf) {
            $data = [
                "collection_tag"    => 'tag_' . $key,
                "name"              => $collectionInf["localized_name"],
                "count_items"       => 0
            ];

            $collection = Collection::where('name', $collectionInf["localized_name"])->first();

            if(is_null($collection))
            {
                $collection = Collection::create($data);

                ParsCollection::create([
                    "collection_id" => $collection->id,
                    "last_period" => Carbon::now('Europe/Moscow')
                                        ->subDay()
                                        ->toDateTimeString()
                ]);
            }

            print_r($key . "\n");
        }
    }

    /**
     * Get all collections name
     * 
     */
    public function responseHandler()
    {
        if(!isset($this->response)) {
            print_r("Error parsing collections");
            return false;
        }

        $this->saveColections();
    }
}