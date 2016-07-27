<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stSise', function($table) {
            $table->increments('id')->index(); // id INT AUTO_INCREMENT PRIMARY
            $table->integer('stCodeIdx'); // title VARCHAR(100) 160719 KEY
            $table->string('ssDate', 6); // title VARCHAR(100) 160719
            $table->integer('ssJongGa'); // title VARCHAR(100)
            $table->integer('ssJulIlBi'); // title VARCHAR(100)
            $table->integer('ssSiGa'); // title VARCHAR(100)
            $table->integer('ssGoGa'); // title VARCHAR(100)
            $table->integer('ssJeoGa'); // title VARCHAR(100)
            $table->integer('ssGeRaeRyang'); // title VARCHAR(100)

            // $table->text('body'); // body TEXT
            $table->timestamps(); // created_at TIMESTAMP, updated_at TIMESTAMP

            $table->unique(array('stCodeIdx','ssDate')); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stSise'); // DROP TABLE posts
    }
}
