<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class KospiController extends Controller
{
    //
    public function getIndex(){
    	return 'list';
    }

    public function postCrawl(){
    	return 'go crawl';
    }

}
