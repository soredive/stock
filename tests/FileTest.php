<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

// use \App\Library\Crawl;
// use \App\Library\Data;
// use \App\Code;
// use \App\DayData;
// use \App\Kospi;
// use \App\Downloader;

class FileTest extends TestCase
{
	public function testSise(){
		$this->markTestSkipped('not need this');
		$response = $this->action('POST', 'SiseController@postCrawl');
        $view = $response->original;
        var_dump($view);
        // $this->assertEquals('authentication.login', $view['name']);
		$this->assertEquals(true,true);
	}
	public function testGogogo(){
        $this->markTestSkipped('get kospi spec is ok');
		$d = new Downloader;
		$d->prefarePath();
		var_dump($d);
		$this->assertEquals(true,true);
	}

	public function testKospi(){
        $this->markTestSkipped('get kospi spec is ok');
		$d = new Downloader;
		$d->prepareCode();
		$d->prepareKospi();
		$d->prepareSise();
		$d->createZip();
		
		$this->assertEquals(true,true);
	}
}
