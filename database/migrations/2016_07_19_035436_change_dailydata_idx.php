<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDailydataIdx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 유니크 키 추가
        Schema::table('stDayData', function($table) {
           $table->unique(array('stCodeIdx','ddDate'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('stDayData', function($table) {
           $table->dropUnique('stdaydata_stcodeidx_dddate_unique');
        });
    }
}
