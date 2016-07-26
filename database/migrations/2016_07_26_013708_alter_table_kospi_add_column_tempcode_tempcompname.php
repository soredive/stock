<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableKospiAddColumnTempcodeTempcompname extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ksKospi', function($table) {
            $table->string('ksTempCompanyName',255)->after('ksDate'); 
            $table->string('ksCode',7)->after('ksDate'); 

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
            $table->dropColumn('ksTempCompanyName');
            $table->dropColumn('ksCode');
        });
    }

}
