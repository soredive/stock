<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterKospiAddColumnRank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ksKospi', function($table) {
            $table->integer('ksRank')->after('ksDate'); 
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
            $table->dropColumn('ksRank');
        });
    }
}
