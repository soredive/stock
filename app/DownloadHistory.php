<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadHistory extends Model
{
    protected $table = 'stDownloadHistory';
	protected $fillable = array(
		'dhFolder', // dhFolder VARCHAR(255)
        'dhFileName' // dhFileName VARCHAR(255)
	);
	protected $guarded = array('id');
	protected $casts = [
	    // 'is_admin' => 'boolean',
	];
}
