<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Input;

class CalledController extends Controller
{
	// http://localhost/called/sangdam?who=김범석&phone=0101234-5678&type=1&dummy=111

	public function getSangdam(){
    	$who = Input::get('who','');
    	$phone = Input::get('phone','');
		$type = Input::get('type','');
		$d = new \App\Dream($who,$phone,$type);
		$d->send();
		$header = [
			'Cache-Control'=>'nocache, no-store, max-age=0, must-revalidate',
		    'Pragma'=>'no-cache',
		    'Expires'=>'Fri, 01 Jan 1990 00:00:00 GMT',
		];
		return response()->download($d->imgPath, 'a.png', $header);
	}

	public function getMunja(){
    	$who = Input::get('who','');
    	$phone = Input::get('phone','');
		$type = Input::get('type','');
		$d = new \App\Dream($who,$phone,$type);
		$d->send();
		return "1";
	}
}
