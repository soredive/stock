<?php

namespace App;
use \Goutte\Client;
use Illuminate\Database\Eloquent\Model;

interface CodeInterface {
	public function add($code);
}

class Code extends Model
{
	protected $table = 'stCode';
	protected $fillable = array('cdNumber','cdName','cdRank','cdLastUpdate','cdOldUpdate','cdLastUpdateSise','cdOldUpdateSise'
		);
	// 할당 차단 필드
	protected $guarded = array('id');
	protected $casts = [
	    // 'is_admin' => 'boolean',
	];

	public static function getCompanyName($code){
		$client = new \Goutte\Client();
		$html = $client->request('GET','http://finance.naver.com/item/main.nhn?code='.$code);
		$title = $html->filter('title');
		$txt = $title->text();
		list($name) = explode(':', $txt);
		return trim($name);
	}

	// dates 실데이터와 비교하여 업데이트
	public function resetDatesToRight(){
		$cnt = \App\Code::count();
		for($i=1;$i<=$cnt;$i++){
		    $code = \App\Code::find($i);
		    if($code->dayData()->count()){
		        $old = $code->dayData()->orderBy('ddDate','asc')->limit(1)->first()->ddDate;
		        $last = $code->dayData()->orderBy('ddDate','desc')->limit(1)->first()->ddDate;
		        $code->cdLastUpdate = $last;
		        $code->cdOldUpdate = $old;
		        $code->save();
		    }
		}
	}

	public function saveUpdateName($name){
		$this->cdName = $name;
		$this->save();
	}

	public function saveUpdateDate($oldDate=null, $lastDate=null){
		$changed = false;
		if($oldDate){
			if(!$this->cdOldUpdate || $oldDate < $this->cdOldUpdate){
				$this->cdOldUpdate = $oldDate;
				$changed = true;
			}
		}
		if($lastDate){
			if(!$this->cdLastUpdate || $lastDate > $this->cdLastUpdate){
				$this->cdLastUpdate = $lastDate;
				$changed = true;
			}
		}
		if($changed){
			$this->save();
			return true;
		}
		return false;
	}

	public function saveUpdateDateSise($oldDate=null, $lastDate=null){
		$changed = false;
		if($oldDate){
			if(!$this->cdOldUpdateSise || $oldDate < $this->cdOldUpdateSise){
				$this->cdOldUpdateSise = $oldDate;
				$changed = true;
			}
		}
		if($lastDate){
			if(!$this->cdLastUpdateSise || $lastDate > $this->cdLastUpdateSise){
				$this->cdLastUpdateSise = $lastDate;
				$changed = true;
			}
		}
		if($changed){
			$this->save();
			return true;
		}
		return false;
	}

    public function dayData(){
    	return $this->hasMany('App\DayData', 'stCodeIdx', 'id');
    }

    public function sise(){
    	return $this->hasMany('App\Sise', 'stCodeIdx', 'id');
    }

    public function kospi(){
    	return $this->hasMany('App\Kospi', 'stCodeIdx', 'id');
    }

    public static function addIfNotExist($code, $codeName='',$rank=''){
    	$codeIdx = null;
    	$list = \App\Code::where('cdNumber','=',$code)->get();
    	if($list->count() > 0 && isset($list['0']->id)){
    		$codeIdx = $list['0']->id;
    	}else{
    		$codeIdx = self::add($code, $codeName, $rank);
    	}
    	return $codeIdx;
    }

	public static function add($code,$codeName='',$rank=''){
		if($codeName == ''){
			$codeName = Code::getCompanyName($code);
		}
		$codeIdx = self::firstOrCreate([
            "cdNumber"=>$code,
            "cdName"=>$codeName,
            "cdRank"=>$rank
        ])->id;
        return $codeIdx;
	}
}
