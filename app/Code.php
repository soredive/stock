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
	protected $fillable = array('cdNumber','cdName','cdLastUpdate','cdOldUpdate');
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

    public function dayData(){
    	return $this->hasMany('App\DayData', 'stCodeIdx', 'id');
    }

    public function kospi(){
    	return $this->hasMany('App\Kospi', 'stCodeIdx', 'id');
    }

	public static function add($code,$codeName=''){
		if($codeName == ''){
			$codeName = Code::getCompanyName($code);
		}
		self::firstOrCreate([
            "cdNumber"=>$code,
            "cdName"=>$codeName
        ]);
	}
}
