<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableKospiDropAddKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ksKospi', function($table) {
            $table->dropUnique('kskospi_stcodeidx_ksdate_unique'); 
            $table->unique(array('ksDate','ksCode')); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ksKospi', function($table) {
            $table->dropUnique('kskospi_ksdate_kscode_unique'); 
        });
    }
}
