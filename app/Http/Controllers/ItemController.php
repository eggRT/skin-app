<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $item = Item::where('name', "Souvenir XM1014 | Blue Spruce (Field-Tested)")->first();

        dd($item);
    }
}
