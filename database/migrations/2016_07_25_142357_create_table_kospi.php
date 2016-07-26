<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKospi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ksKospi', function($table) {
            $table->increments('id')->index(); // id INT AUTO_INCREMENT PRIMARY
            $table->string('stCodeIdx',10);
            $table->string('ksDate',6);
            $table->bigInteger('ksHyunJaeGa');
            $table->integer('ksJulIlBi');
            $table->float('ksDeunRakPok');
            $table->integer('ksAekMyunGa');
            $table->bigInteger('ksSiGaChongAek');
            $table->integer('ksSangJangJuSu');
            $table->float('ksForBiYul');
            $table->bigInteger('ksGaeRaeRyang');
            $table->float('ksPER');
            $table->float('ksROE');
            $table->timestamps();

            $table->unique(array('stCodeIdx','ksDate'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ksKospi'); // DROP TABLE posts
    }
}
