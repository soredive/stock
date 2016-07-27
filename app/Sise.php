<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sise extends Model
{
    protected $table = 'stSise';
	protected $fillable = array(
		'stCodeIdx'
		,'ssDate'
		,'ssJongGa'
		,'ssJulIlBi'
		,'ssSiGa'
		,'ssGoGa'
		,'ssJeoGa'
		,'ssGeRaeRyang'
	);
	protected $guarded = array('id');
	protected $casts = [
	    // 'is_admin' => 'boolean',
	];

	public function code(){
		return $this->belongsTo('App\Code');
	}
}
