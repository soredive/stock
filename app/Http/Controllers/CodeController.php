<?php

namespace App\Http\Controllers;

use \App\DayData;
use \App\Code;
use \App\Library\Crawl;
use \App\Library\Data;

use Illuminate\Http\Request;

use App\Http\Requests;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return \App\Code::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate($codeNumStr,$codeName='')
    {
    	if(!$codeNumStr){
    		throw new Exception('code number is not exist');
    	}
        \App\Code::add($codeNumStr, $codeName);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getShow($codeNumStr)
    {
    	if(!$codeNumStr){
    		throw new Exception('code number is not exist');
    	}
        return \App\Code::where('cdNumber','=',$codeNumStr)->firstOrFail();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDestroy($codeNumStr)
    {
        if(!$codeNumStr){
    		throw new Exception('code number is not exist');
    	}
        \App\Code::where('cdNumber','=',$codeNumStr)->delete();
    }

    public function getCrawl()
    {
        $crawl = new Crawl();
        foreach(Code::all() as $code){
        	$crawl->reset();
        	$result = $crawl->getCodeData($code);
        	$code->saveUpdateDate($crawl->oldestDate, $crawl->lastestDate);

	        foreach ($result as $key => $value) {
	            DayData::create(array(
	                'stCodeIdx'=>$value['codeIdx']
	                ,'ddDate'=>$value['ddDate']
	                ,'ddJongGa'=>$value['ddJongGa']
	                ,'ddJulIlBi'=>$value['ddJulIlBi']
	                ,'ddDeunRakPok'=>$value['ddDeunRakPok']
	                ,'ddGeRaeRyang'=>$value['ddGeRaeRyang']
	                ,'ddSunMaeMae'=>$value['ddSunMaeMae']
	                ,'ddForSunMaeMae'=>$value['ddForSunMaeMae']
	                ,'ddForBoYuJuSu'=>$value['ddForBoYuJuSu']
	                ,'ddforBoYuYul'=>$value['ddforBoYuYul']
	            ));
	        }
        }
        $cnt = DayData::all()->count();
        // return 'updated: '.$cnt.' <==';
    }

    public function getData($codeNumStr){
    	return Code::where('cdNumber','=',$codeNumStr)->get()[0]->dayData;
    }

    public function getOne()
    {
        //
        return 'one';
    }

    public function getAll()
    {
        //
        return 'all';
    }
}


