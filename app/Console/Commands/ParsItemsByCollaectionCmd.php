<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\Item;
use App\Models\ParsCollection;
use App\Services\Pars\ParsClient;
use App\Services\Pars\Query\ItemsByCollectionQuery;
use App\Services\Pars\Response\ItemsByCollectionResponse;
use App\Services\Pars\Service\SteamCollectionItemsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ParsItemsByCollaectionCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pars:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDate = Carbon::now('Europe/Moscow');

        $parsCollection = ParsCollection::with('collection')
                                ->whereDay('last_period', '!=', $currentDate->day)
                                ->first();

        if(!is_null($parsCollection)) {
            $itemsByCollectionService = new SteamCollectionItemsService($parsCollection, $currentDate);
            $itemsByCollectionService->responseHandler();
        }


        /*$start = 0;
        $cntItems = 100;
        $collectionTag = "tag_set_train";

        $collectionId = Collection::where("collection_tag", $collectionTag)->first()->id;

        $client = new ParsClient(new ItemsByCollectionResponse);

        $response = $client->getInfo(new ItemsByCollectionQuery(
            [
                'start'     => $start,
                'count'     => $cntItems,                    
                'category_730_ItemSet%5B%5D' => $collectionTag,
            ]
        ))->getPage();

        $cntCollectionItems = $response["total_count"];
        $cntPages           = ceil($cntCollectionItems / $cntItems);

        print_r("Pages: " . $cntPages . "\n");

        $items = $response["results"];

        foreach ($items as $item) {
            $itemName       = $item["hash_name"];
            $sellListings   = $item["sell_listings"];

            $itemObj = Item::where("name", $itemName)->first();

            if(is_null($itemObj))
            {
                $data = [
                    "name" => $itemName,
                    "image" => '',
                    "alltime_percent" => 0,
                    "collection_id" => $collectionId
                ];
    
                Item::create($data);
            }

            print_r($itemName . "\n");
            print_r($sellListings . "\n");
        }*/

        return Command::SUCCESS;
    }
}
