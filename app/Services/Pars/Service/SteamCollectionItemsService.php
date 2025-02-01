<?php

namespace App\Services\Pars\Service;

use App\Models\Collection;
use App\Models\Item;
use App\Models\Period;
use App\Services\Pars\ParsClient;
use App\Services\Pars\Query\ItemsByCollectionQuery;
use App\Services\Pars\Response\ItemsByCollectionResponse;

class SteamCollectionItemsService {

    private ParsClient $client;

    private $response;

    // limit items
    private int $cntItems       = 100;

    // pages
    private int $startPage      = 0;
    private int $cntPages       = 0;

    private $lastPeriod;

    private string $collectionTag;
    private int $collectionId;

    private $parsColletion;

    public function __construct($parsColletion, $lastPeriod)
    {
        $this->collectionTag = $parsColletion->collection->collection_tag;
        $this->collectionId = $parsColletion->collection->id;

        $this->startPage    = $parsColletion->start_page;
        $this->cntPages     = $parsColletion->count_page;

        $this->parsColletion    = $parsColletion;

        $this->lastPeriod       = $lastPeriod;

        $this->client = new ParsClient(new ItemsByCollectionResponse);
    }

    public function requestPage(int $start)
    {
        $this->response = $this->client->getInfo(new ItemsByCollectionQuery(
            [
                'start'     => $start,
                'count'     => $this->cntItems,                    
                'category_730_ItemSet%5B%5D' => $this->collectionTag,
            ]
        ))->getPage();
    }

    private function saveItem(array $infItem)
    {
        $itemFloat = Item::where('name', $infItem["name"])->first();

        if( is_null($itemFloat) ) { 
            $itemFloat = Item::create($infItem); 
        }

        return $itemFloat;
    }

    private function savePeriod(array $periodData)
    {
        Period::create($periodData);
    }

    private function updateParsCollection($lastPeriod = null)
    {
        $this->parsColletion->start_page = $this->startPage;
        $this->parsColletion->count_page = $this->cntPages;

        if(!is_null($lastPeriod)) {
            $this->parsColletion->last_period = $lastPeriod;
        }

        $this->parsColletion->save();
    }

    /**
     * Parsing json data
     * 
     */
    private function parsingResults()
    {
        $items = $this->response["results"];
        $imgUrl = "https://community.fastly.steamstatic.com/economy/image/";

        $currentDate = new \DateTime('now');

        foreach ($items as $item) {
            $itemName   = $item["hash_name"];
            $itemImg    = $imgUrl . $item["asset_description"]["icon_url"];
            $itemPrice  = str_replace(",", "", explode("$", $item["sell_price_text"])[1]);
            $sellListings   = $item["sell_listings"];

            $data = [
                "name" => $itemName,
                "image" => $itemImg, 
                "price" => $itemPrice,
                "collection_id" => $this->collectionId
            ];

            $itemObj = $this->saveItem($data);

            $periodData = [
                "created_at" => $currentDate,
                "price"     => $data["price"],
                "volume"    => $sellListings,
                "item_id"   => $itemObj->id
            ];

            $this->savePeriod($periodData);

            print_r($itemObj->name . "\n");
        }
    }

    /**
     * Parsing first items page
     * 
     * return bool (true - sucessful)
     */
    private function firstPageProcessing() : bool
    {
        if(!isset($this->response)) {
            print_r("Error parsing first page ItemsByCollectionService");
            return false;
        }

        $cntCollectionItems = $this->response["total_count"];
        $this->cntPages = ceil($cntCollectionItems / $this->cntItems);

        print_r("Pages: " . $this->cntPages . "\n");

        $this->parsingResults();

        $this->startPage = 1;   // page for next request

        return true;
    }

    /**
     * Parsing other items page
     * 
     * return bool (true - sucessful)
     */
    private function otherPageProcessing() : bool
    {
        if(!isset($this->response)) {
            print_r("Error parsing other page ItemsByCollectionService");
            return false;
        }

        $this->parsingResults();

        $this->startPage += 1;   // page for next request

        return true;
    }

    public function responseHandler()
    {
        if($this->startPage == $this->cntPages) {

            $this->requestPage(0);

            $this->firstPageProcessing();

        } else if($this->startPage < $this->cntPages) {

            $this->requestPage($this->startPage * $this->cntItems);

            $this->otherPageProcessing();

        }
        
        print_r("start: " . $this->startPage . "  cnt: " . $this->cntPages . "\n");

        $this->updateParsCollection(($this->startPage < $this->cntPages) ? null : $this->lastPeriod);
    }
}