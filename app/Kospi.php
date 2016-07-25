<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kospi extends Model
{
    protected $table = 'stDayData';
	protected $fillable = array(
		'stCodeIdx'
		,'ksDate'
		,'ksRank'
		,'ksHyunJaeGa'
		,'ksJulIlBi'
		,'ksDeunRakPok'
		,'ksAekMyunGa'
		,'ksSiGaChongAek'
		,'ksSangJangJuSu'
		,'ksForBiYul'
		,'ksGaeRaeRyang'
		,'ksPER'
		,'ksROE'
	);
	protected $guarded = array('id');
	protected $casts = [
	    // 'is_admin' => 'boolean',
	];

	public function code(){
    	return $this->belongsTo('App\Code');
    }
}
