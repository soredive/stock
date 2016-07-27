<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Input;

class SiseController extends Controller
{
    public function postCrawl()
    {
        $crawl = new \App\Library\SiseCrawl();
        $list = \App\Code::all();
        foreach($list as $code){
        	$crawl->reset();
        	$result = $crawl->getCodeData($code);
        	$code->saveUpdateDateSise($crawl->oldestDate, $crawl->lastestDate);

	        foreach ($result as $key => $value) {
                $exist = \App\Sise::whereRaw('stCodeIdx = ? and ssDate = ?',array($value['codeIdx'],$value['ssDate']))->count();
                if($exist < 1){
                    \App\Sise::create(array(
                        'stCodeIdx'=>$value['codeIdx']
                        ,'ssDate'=>$value['ssDate']
                        ,'ssJongGa'=>$value['ssJongGa']
                        ,'ssJulIlBi'=>$value['ssJulIlBi']
                        ,'ssSiGa'=>$value['ssSiGa']
                        ,'ssGoGa'=>$value['ssGoGa']
                        ,'ssJeoGa'=>$value['ssJeoGa']
                        ,'ssGeRaeRyang'=>$value['ssGeRaeRyang']
                    )); 
                }
	        }
        }
        return \App\Code::all();
    }

    public function postData(){
        $codeId = \Illuminate\Support\Facades\Input::get('codeId');
        if(!$codeId){
            throw new \Exception('code number is not exist');
        }
    	return \App\Code::where('id','=',$codeId)->get()[0]->sise;
    }
}
