<?php

namespace App\Http\Controllers;

use \App\DayData;
use \App\Code;
use \App\Library\Crawl;
use \App\Library\Data;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Input;



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
    public function postCreate()
    {
        $codeNumStr = Input::get('codeNumStr');
        $codeName = Input::get('codeName','');
    	if(!$codeNumStr){
    		throw new \Exception('code number is not exist');
    	}
        \App\Code::add($codeNumStr, $codeName);
        return \App\Code::all();
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
    		throw new \Exception('code number is not exist');
    	}
        return \App\Code::where('cdNumber','=',$codeNumStr)->firstOrFail();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDestroy()
    {
        $codeId = Input::get('codeId');
        if(!$codeId){
    		throw new \Exception('code number is not exist');
    	}
        \App\Code::where('id','=',$codeId)->delete();
        return \App\Code::all();
    }

    public function postZip(){
        $d = new \App\Downloader;
        $path = $d->doDownload();
        return 'success:'.$path;
    }

    public function postCrawl()
    {
        $crawl = new Crawl();
        foreach(Code::all() as $code){
        	$crawl->reset();
        	$result = $crawl->getCodeData($code);
        	$code->saveUpdateDate($crawl->oldestDate, $crawl->lastestDate);

	        foreach ($result as $key => $value) {
                $exist = \App\DayData::whereRaw('stCodeIdx = ? and ddDate = ?',array($value['codeIdx'],$value['ddDate']))->count();
                if($exist < 1){
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
        }
        return \App\Code::all();
    }

    public function postData(){
        $codeId = Input::get('codeId');
        if(!$codeId){
            throw new \Exception('code number is not exist');
        }
    	return Code::where('id','=',$codeId)->get()[0]->dayData;
    }
}


