<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AterDaildataAddColumnCdoldupdate extends Migration
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
            $table->string('cdOldUpdate', 6)->after('cdLastUpdate')->default(''); 
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
            $table->dropColumn('cdOldUpdate', 6)->after('cdLastUpdate')->default(''); 
        });
    }
}
