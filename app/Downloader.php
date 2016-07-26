<?php
namespace App;

use \App\Library\Crawl;
use \App\Library\Data;
use \App\Code;
use \App\DayData;
use \App\Kospi;
use \App\Downloader;

class Downloader
{
    public $folername = '';
    public $basepath = '';
    public $stPath = '';
    public $folderPath = '';
    public $pathCode = '';
    public $pathKospi = '';
    public $pathBuyer = '';
    public $sep = '|';
    public $sep2 = '\r\n';

    public function __construct(){
        $this->stPath = storage_path(); // exist

        $this->prefarePath();
    }

    public function formatKospi($item){
        return 
        $item->ksRank . $this->sep .
        $item->ksDate . $this->sep .
        $item->ksTempCompanyName . $this->sep .
        $item->ksCode . $this->sep .
        $item->ksHyunJaeGa . $this->sep .
        $item->ksJulIlBi . $this->sep .
        $item->ksDeunRakPok . $this->sep .
        $item->ksAekMyunGa . $this->sep .
        $item->ksSiGaChongAek . $this->sep .
        $item->ksSangJangJuSu . $this->sep .
        $item->ksForBiYul . $this->sep .
        $item->ksGaeRaeRyang . $this->sep .
        $item->ksPER . $this->sep . 
        $item->ksROE . $this->sep2;
    }

    public function prepareKospi(){
        $lastDate = \App\Kospi::orderBy('ksDate','desc')->limit(1)->first()->ksDate;
        if(!$lastDate){
            throw new \Execption('no last date');
        }
        $list = \App\Kospi::where('ksDate','=',$lastDate)->get();
        $arr = [];
        foreach($list as $item){
            $arr[] = $this->formatKospi($item);
        }
        \File::put($this->pathKospi.'/'.$lastDate.'.txt', implode('', $arr));
    }

    public function makeFolder($fullpath){
        if(! \File::exists($fullpath)){
            $oldmask = umask(0);
            mkdir($fullpath,0777,true);
            umask($oldmask);    
        }elseif(! \File::isWritable($fullpath)){
            $oldmask = umask(0);
            chmod($fullpath,0777);
            umask($oldmask);    
        }
    }

    public function prefarePath(){
        $this->basepath = $this->stPath.'/filedown';
        $this->makeFolder($this->basepath);

        $this->folderPath = $this->basepath.'/'.$this->getFolderName();
        $this->makeFolder($this->folderPath);

        $this->pathCode = $this->folderPath.'/Code';
        $this->makeFolder($this->pathCode);

        $this->pathKospi = $this->folderPath.'/Kospi';
        $this->makeFolder($this->pathKospi);

        $this->pathBuyer = $this->folderPath.'/Buyer';
        $this->makeFolder($this->pathBuyer);
    }

    public function getFolderName(){
        // return date('ymdGis').'_'.rand(1,100);
        return 'hehehe';
    }
}
