<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PageController extends Controller
{
    public function container()
    {
        $items = Item::all()->groupBy('name');
        return view('index', compact('items'));
    }
}
