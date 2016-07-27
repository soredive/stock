<?php
namespace App;

class Downloader
{
    public $randName = '';
    public $folername = '';
    public $basepath = '';
    public $stPath = '';
    public $folderPath = '';
    public $pathCode = '';
    public $pathKospi = '';
    public $pathBuyer = '';
    public $targetDate = '';
    public $pathZipfile = '';
    public $sep = '|';
    public $sep2 = "\r\n"; // for windows

    public function __construct(){
        $this->stPath = storage_path(); // exist
        $this->targetDate = $this->getTargetDate();
        $this->setRandName();
        $this->prefarePath();
    }

    public function getTargetDate(){
        return date("ymd", strtotime('-6 month'));
    }

    public function setRandName(){
        if($this->randName == ''){
            # $this->randName = date('ymdGis').'_'.rand(1,100);
            # testing
            $this->randName = 'hehehe';
        }
        return $this->randName;
    }

    public function formatDayData($item){
        return 
        $item->ddDate . $this->sep .
        $item->ddJongGa . $this->sep .
        $item->ddJulIlBi . $this->sep .
        $item->ddDeunRakPok . $this->sep .
        $item->ddGeRaeRyang . $this->sep .
        $item->ddSunMaeMae . $this->sep .
        $item->ddForSunMaeMae . $this->sep .
        $item->ddForBoYuJuSu . $this->sep .
        $item->ddforBoYuYul;
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
        $item->ksROE;
    }

    public function createZip(){
        $zip = new \Chumper\Zipper\Zipper;
        $zip->make($this->pathZipfile)->add($this->folderPath);
    }

    public function prepareCode(){
        $list = \App\Code::all();
        foreach($list as $key => $code){
            $contentsArr = [];
            $dayDatas = $code->dayData()->where('ddDate','>',$this->targetDate);
            if($dayDatas->count() > 0){
                $dayLists = $dayDatas->getResults();
                $iter = $dayLists->getIterator();
                while($iter->valid()){
                    $day = $iter->current();
                    // 여기
                    $contentsArr[] = $this->formatDayData($day);
                    $iter->next();
                }
            }
            $contentsStr = implode($this->sep2, $contentsArr);
            \File::put($this->pathCode.'/'.$code->cdNumber.'.txt', $contentsStr);
            # for testing
            # break;
        }

    }

    public function prepareKospi(){
        $lastDate = \App\Kospi::orderBy('ksDate','desc')->limit(1)->first()->ksDate;
        if(!$lastDate){
            throw new \Execption('no last date');
        }
        $list = \App\Kospi::where('ksDate','=',$lastDate)->orderBy('ksRank','asc')->get();
        $arr = [];
        foreach($list as $item){
            $arr[] = $this->formatKospi($item);
        }
        \File::put($this->pathKospi.'/'.$lastDate.'.txt', implode($this->sep2, $arr));
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

        $this->pathZipfile = base_path().'/public/zips/'.$this->setRandName().'.zip';
    }

    public function getFolderName(){
        return $this->setRandName();
    }
}
