<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayData extends Model
{
	protected $table = 'stDayData';
	protected $fillable = array('cdNumber','cdName');
	protected $guarded = array('id');
	protected $casts = [
	    // 'is_admin' => 'boolean',
	];

	public function code(){
		return $this->belongsTo('App\Code');
	}
    //


    //11
}
