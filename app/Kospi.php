<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kospi extends Model
{
    protected $table = 'ksKospi';
	protected $fillable = [
		'stCodeIdx'
		,'ksDate'
		,'ksCode'
		,'ksTempCompanyName'
		,'ksRank'
		,'ksHyunJaeGa'
		,'ksJulIlBi'
		,'ksDeunRakPok'
		,'ksAekMyunGa'
		,'ksGaeRaeRyang'
		,'ksGaeRaeDaeGueam'
		,'ksJunIlGaeRaeRyang'
		,'ksSiGa'
		,'ksGoGa'
		,'ksJeoGa'
		,'ksMaeSuHoGa'
		,'ksMaeDoHoGa'
		,'ksMaeSuChongJanRyang'
		,'ksMaeDoChongJanRyang'
		,'ksSangJangJuSu'
		,'ksSiGaChongAek'
		,'ksMaeChulAek'
		,'ksJaSanChongGae'
		,'ksBuChaeChongGae'
		,'ksYungUpEaIk'
		,'ksDangGiSunEaIk'
		,'ksJuDangSunEaIk'
		,'ksBoTongJuBaeDangGuem'
		,'ksMaeCulAekJungGaYul'
		,'ksYoungUpEaIkJungGaYul'
		,'ksForBiYul'
		,'ksPER'
		,'ksROE'
		,'ksROA'
		,'ksPBR'
		,'ksYuBoYul'
	];
	protected $guarded = array('id');
	protected $casts = [
	    // 'is_admin' => 'boolean',
	];

	public function code(){
    	return $this->belongsTo('App\Code');
    }
}
