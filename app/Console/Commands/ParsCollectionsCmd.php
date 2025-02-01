<?php

namespace App\Console\Commands;

use App\Services\Pars\Service\SteamCollectionService;
use Illuminate\Console\Command;

class ParsCollectionsCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pars:collections';

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
        $steamCollectionsParsService = new SteamCollectionService();
        $steamCollectionsParsService->responseHandler();

        return Command::SUCCESS;
    }
}
