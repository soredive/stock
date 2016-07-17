<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

interface CodeInterface {
	public function add($code);
	public function remove($code);
	public function lists();
	public function edit($idx,$code);
}

class Code extends Model //implements CodeInterface 
{
	protected $table = 'stCode';
	protected $fillable = array('cdNumber','cdName');
	protected $guarded = array('id');
	protected $casts = [
	    // 'is_admin' => 'boolean',
	];

    //
    public $allCode = [];

    public function dayData(){
    	return $this->hasMany('App\DayData', 'stCodeIdx', 'id');
    }

	public static function add($code){
		self::firstOrCreate([
            "cdNumber"=>$code
        ]);
	}
	public static function remove($code){
		self::where('cdNumber','=',$code)->delete();
	}
	public static function lists(){
		return self::get()->toArray();
	}
	public function edit($idx,$code){
		
	}
}
