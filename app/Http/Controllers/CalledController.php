<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Input;

class CalledController extends Controller
{
	public function getSangdam(){
    	$who = Input::get('who','');
    	$phone = Input::get('phone','');
		$type = Input::get('type','');
		$d = new \App\Dream($who,$phone,$type);
		$d->send();
		return response()->download($d->imgPath, 'a.png', []);
	}
}
