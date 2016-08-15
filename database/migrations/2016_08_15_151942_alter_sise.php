<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('ksKospi', function($table) {
            $table->bigInteger('ksGaeRaeDaeGueam');
            $table->bigInteger('ksJunIlGaeRaeRyang');
            $table->bigInteger('ksSiGa');
            $table->bigInteger('ksGoGa');
            $table->bigInteger('ksJeoGa');
            $table->bigInteger('ksMaeSuHoGa');
            $table->bigInteger('ksMaeDoHoGa');
            $table->bigInteger('ksMaeSuChongJanRyang');
            $table->bigInteger('ksMaeDoChongJanRyang');
            $table->bigInteger('ksMaeChulAek');
            $table->bigInteger('ksJaSanChongGae');
            $table->bigInteger('ksBuChaeChongGae');
            $table->bigInteger('ksYungUpEaIk');
            $table->bigInteger('ksDangGiSunEaIk');
            $table->bigInteger('ksJuDangSunEaIk');
            $table->bigInteger('ksBoTongJuBaeDangGuem');
            $table->float('ksMaeCulAekJungGaYul');
            $table->float('ksYoungUpEaIkJungGaYul');
            $table->float('ksROA');
            $table->float('ksPBR');
            $table->float('ksYuBoYul');
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
        Schema::table('ksKospi', function($table) {
            $table->dropColumn('ksGaeRaeDaeGueam');
            $table->dropColumn('ksJunIlGaeRaeRyang');
            $table->dropColumn('ksSiGa');
            $table->dropColumn('ksGoGa');
            $table->dropColumn('ksJeoGa');
            $table->dropColumn('ksMaeSuHoGa');
            $table->dropColumn('ksMaeDoHoGa');
            $table->dropColumn('ksMaeSuChongJanRyang');
            $table->dropColumn('ksMaeDoChongJanRyang');
            $table->dropColumn('ksMaeChulAek');
            $table->dropColumn('ksJaSanChongGae');
            $table->dropColumn('ksBuChaeChongGae');
            $table->dropColumn('ksYungUpEaIk');
            $table->dropColumn('ksDangGiSunEaIk');
            $table->dropColumn('ksJuDangSunEaIk');
            $table->dropColumn('ksBoTongJuBaeDangGuem');
            $table->dropColumn('ksMaeCulAekJungGaYul');
            $table->dropColumn('ksYoungUpEaIkJungGaYul');
            $table->dropColumn('ksROA');
            $table->dropColumn('ksPBR');
            $table->dropColumn('ksYuBoYul');
            
        });
    }
}
