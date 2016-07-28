<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadhistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stDownloadHistory', function($table) {
            $table->increments('id')->index(); // id INT AUTO_INCREMENT PRIMARY
            $table->string('dhFolder',255); // title VARCHAR(100)
            $table->string('dhFileName',255); // title VARCHAR(100)
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
        Schema::dropIfExists('stDownloadHistory'); // DROP TABLE posts
    }
}
