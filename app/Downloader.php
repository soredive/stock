<?php
namespace App;

use Illuminate\Support\Facades\File;

class Downloader
{
    public $randName = '';
    public $folername = '';
    public $basepath = '';
    public $stPath = '';
    public $folderPath = '';
    public $pathCode = '';
    public $pathKospi = '';
    public $pathSise = '';
    public $targetDate = '';
    public $pathZipfile = '';
    public $deleteTime = '';
    public $sep = '|';
    public $sep2 = "\r\n"; // for windows

    public function __construct(){
        $this->stPath = storage_path(); // exist
        $this->targetDate = $this->getTargetDate();
        $this->deleteTime = date('Y-m-d H:i:s' , strtotime('-1 hour'));
        $this->setRandName();
        $this->prefarePath();
    }

    public function clearFolderFile($folder='',$file=''){
        if($folder && File::exists($folder) && File::isDirectory($folder)){
            File::deleteDirectory($folder, $preserve = false);
        }
        if($file && File::exists($file) && File::isFile($file)){
            File::delete($file);
        }
    }

    public function deleteDownHistory(){
        $list = DownloadHistory::where('created_at','<',$this->deleteTime)->get();
        foreach($list as $item){
            $folder = $item->dhFolder;
            $file = $item->dhFileName;
            $this->clearFolderFile($folder,$file);
            $item->delete();
        }
    }    

    public function saveDownHistory($folder='',$file=''){
        $this->deleteDownHistory();

        $dh = new DownloadHistory;
        $dh->dhFolder = $folder;
        $dh->dhFileName = $file;
        $dh->save();
    }

    public function getTargetDate(){
        return date("ymd", strtotime('-6 month'));
    }

    public function setRandName(){
        if($this->randName == ''){
            $this->randName = date('ymdGis').'_'.rand(1,100);
            # testing
            // $this->randName = 'hehehe';
        }
        return $this->randName;
    }

    public function formatSise($item){
        return 
        $item->ssDate . $this->sep .
        $item->ssJongGa . $this->sep .
        $item->ssJulIlBi . $this->sep .
        $item->ssSiGa . $this->sep .
        $item->ssGoGa . $this->sep .
        $item->ssJeoGa . $this->sep .
        $item->ssGeRaeRyang;
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

    public function doDownload(){
        $this->createFolder();
        $this->saveDownHistory($this->folderPath, $this->pathZipfile);

        $this->prepareCode();
        $this->prepareKospi();
        $this->prepareSise();
        $this->createZip();

        list($dummy, $url) = explode('/public', $this->pathZipfile);
        return $url;
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
                    $contentsArr[] = $this->formatDayData($day);
                    $iter->next();
                }
            }
            $contentsStr = implode($this->sep2, $contentsArr);
            \File::put($this->pathCode.'/'.$code->cdNumber.'.txt', $contentsStr);
        }
    }

    public function prepareSise(){
        $list = \App\Code::all();
        foreach($list as $key => $code){
            $contentsArr = [];
            $dayDatas = $code->sise()->where('ssDate','>',$this->targetDate);
            if($dayDatas->count() > 0){
                $dayLists = $dayDatas->getResults();
                $iter = $dayLists->getIterator();
                while($iter->valid()){
                    $day = $iter->current();
                    $contentsArr[] = $this->formatSise($day);
                    $iter->next();
                }
            }
            $contentsStr = implode($this->sep2, $contentsArr);
            \File::put($this->pathSise.'/'.$code->cdNumber.'.txt', $contentsStr);
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
        }
        if(! \File::isWritable($fullpath)){
            $oldmask = umask(0);
            chmod($fullpath,0777);
            umask($oldmask);    
        }
    }

    public function createFolder(){
        $this->makeFolder($this->basepath);
        $this->makeFolder($this->folderPath);
        $this->makeFolder($this->pathCode);
        $this->makeFolder($this->pathKospi);
        $this->makeFolder($this->pathSise);
        $this->makeFolder(base_path().'/public/zips');
        
    }

    public function prefarePath(){
        $this->basepath = $this->stPath.'/filedown';
        $this->folderPath = $this->basepath.'/'.$this->getFolderName();
        $this->pathCode = $this->folderPath.'/Who';
        $this->pathKospi = $this->folderPath.'/Kospi';
        $this->pathSise = $this->folderPath.'/Sise';
        $this->pathZipfile = base_path().'/public/zips/'.$this->setRandName().'.zip';
    }

    public function getFolderName(){
        return $this->setRandName();
    }
}
