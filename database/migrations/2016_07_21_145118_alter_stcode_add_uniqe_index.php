<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStcodeAddUniqeIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stCode', function($table) {
            $table->unique('cdNumber'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stCode', function($table) {
            $table->dropUnique('stcode_cdnumber_unique'); 
        });
    }
}
