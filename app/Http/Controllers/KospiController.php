<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class KospiController extends Controller
{
    //
    public function getIndex(){
    	// return \App\Kospi::all();
    	$lastDate = \App\Kospi::orderBy('ksDate','desc')->limit(1)->first()->ksDate;
        if(!$lastDate){
            throw new \Execption('no last date');
        }
        return \App\Kospi::where('ksDate','=',$lastDate)->orderBy('ksRank','asc')->get();
    }

    public function postCrawl(){
    	$k = new \App\Library\KospiCrawl;
        $list = $k->getTodayData();

        foreach($list as $item){
        	$codeIdx = \App\Code::addIfNotExist($item['ksCode'],$item['ksTempCompanyName'],$item['ksRank']);
			\App\Kospi::create(array(
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
				,'ksGaeRaeDaeGueam' => $item['ksGaeRaeDaeGueam']
				,'ksJunIlGaeRaeRyang' => $item['ksJunIlGaeRaeRyang']
				,'ksSiGa' => $item['ksSiGa']
				,'ksGoGa' => $item['ksGoGa']
				,'ksJeoGa' => $item['ksJeoGa']
				,'ksMaeSuHoGa' => $item['ksMaeSuHoGa']
				,'ksMaeDoHoGa' => $item['ksMaeDoHoGa']
				,'ksMaeSuChongJanRyang' => $item['ksMaeSuChongJanRyang']
				,'ksMaeDoChongJanRyang' => $item['ksMaeDoChongJanRyang']
				,'ksMaeChulAek' => $item['ksMaeChulAek']
				,'ksJaSanChongGae' => $item['ksJaSanChongGae']
				,'ksBuChaeChongGae' => $item['ksBuChaeChongGae']
				,'ksYungUpEaIk' => $item['ksYungUpEaIk']
				,'ksDangGiSunEaIk' => $item['ksDangGiSunEaIk']
				,'ksJuDangSunEaIk' => $item['ksJuDangSunEaIk']
				,'ksBoTongJuBaeDangGuem' => $item['ksBoTongJuBaeDangGuem']
				,'ksMaeCulAekJungGaYul' => $item['ksMaeCulAekJungGaYul']
				,'ksYoungUpEaIkJungGaYul' => $item['ksYoungUpEaIkJungGaYul']
				,'ksROA' => $item['ksROA']
				,'ksPBR' => $item['ksPBR']
				,'ksYuBoYul' => $item['ksYuBoYul']
			));
        }
        return \App\Kospi::where('ksDate','=',date('ymd'))->get();
    }
}
