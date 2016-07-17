<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDayData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stDayData', function($table) {
            $table->increments('id')->index(); // id INT AUTO_INCREMENT PRIMARY
            $table->integer('stCodeIdx'); // title VARCHAR(100) 160719 KEY
            $table->string('ddDate', 6); // title VARCHAR(100) 160719
            $table->integer('ddJongGa'); // title VARCHAR(100)
            $table->integer('ddJulIlBi'); // title VARCHAR(100)
            $table->float('ddDeunRakPok', 20); // title VARCHAR(100)
            $table->integer('ddGeRaeRyang'); // title VARCHAR(100)
            $table->integer('ddSunMaeMae'); // title VARCHAR(100)
            $table->integer('ddForSunMaeMae'); // title VARCHAR(100)
            $table->integer('ddForBoYuJuSu'); // title VARCHAR(100)
            $table->float('ddforBoYuYul', 20); // title VARCHAR(100)

            // $table->text('body'); // body TEXT
            $table->timestamps(); // created_at TIMESTAMP, updated_at TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stDayData'); // DROP TABLE posts
    }
}
