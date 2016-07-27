<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCodeAddColumnSise2 extends Migration
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
            $table->string('cdOldUpdateSise', 6)->after('cdOldUpdate')->default(''); 
            $table->string('cdLastUpdateSise', 6)->after('cdOldUpdate')->default(''); 
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
            $table->dropColumn('cdOldUpdateSise'); 
            $table->dropColumn('cdLastUpdateSise'); 
        });
    }
}
