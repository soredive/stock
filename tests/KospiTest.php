<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\Library\Crawl;
use \App\Library\Data;
use \App\Code;
use \App\DayData;

class KospiTest extends TestCase
{

	public function testCode(){
		// $this->markTestSkipped('code spec is ok');

        $k = new App\Library\KospiCrawl;
        var_dump($k->getHtml());

		$this->assertEquals(1,1);
	}
}
