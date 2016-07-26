<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class KospiController extends Controller
{
    //
    public function getIndex(){
    	// return \App\Kospi::all();
    	return \App\Kospi::where('ksDate','=',date('ymd'))->get();
    }

    public function postCrawl(){
    	$k = new \App\Library\KospiCrawl;
        $list = $k->getTodayData();

        foreach($list as $item){
        	$codeIdx = Code::addIfNotExist($item['ksCode'],$item['ksTempCompanyName'],$item['ksRank']);
			Kospi::create(array(
	        	'ksDate' => $item['ksDate']
	        	,'stCodeIdx' => $codeIdx
				,'ksCode' => $item['ksCode']
				,'ksTempCompanyName' => $item['ksTempCompanyName']
				,'ksRank' => $item['ksRank']
				,'ksHyunJaeGa' => $item['ksHyunJaeGa']
				,'ksJulIlBi' => $item['ksJulIlBi']
				,'ksDeunRakPok' => $item['ksDeunRakPok']
				,'ksAekMyunGa' => $item['ksAekMyunGa']
				,'ksSiGaChongAek' => $item['ksSiGaChongAek']
				,'ksSangJangJuSu' => $item['ksSangJangJuSu']
				,'ksForBiYul' => $item['ksForBiYul']
				,'ksGaeRaeRyang' => $item['ksGaeRaeRyang']
				,'ksPER' => $item['ksPER']
				,'ksROE' => $item['ksROE']
			));
        }
        return \App\Kospi::where('ksDate','=',date('ymd'))->get();
    }

}
