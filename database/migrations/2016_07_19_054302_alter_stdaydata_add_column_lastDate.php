<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStdaydataAddColumnLastDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('stCode', function($table) {
            $table->string('cdLastUpdate', 6)->after('cdName')->default(''); 
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
        Schema::table('stCode', function($table) {
            $table->dropColumn('cdLastUpdate');
        });   
    }
}
