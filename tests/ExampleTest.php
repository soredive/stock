<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\Library\Stock;
use \App\Library\Crawl;
use \App\Library\Data;
use \App\Code;
use \App\DayData;

class ExampleTest extends TestCase
{
	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	// public function testBasicExample()
	// {
	//     $this->visit('/')
	//          ->see('Laravel 5');
	// }
	public $code;
	public $stock;
	public $crawl;

	public function codeTest(){
		$this->markTestSkipped('code spec is ok');

		Code::firstOrCreate([
			"cdNumber"=>'123456'
		]);
		$list = Code::get()->lists('cdNumber')->toArray();
		$this->assertEquals(['123456'],$list);
		
		$list = Code::all()->toArray();
		$key = $list[0]['id'];
		Code::where('id','=',$key)->delete();
		$list = Code::all()->toArray();
		$this->assertEquals([],$list);

		Code::add('456789');
		$list = Code::get()->lists('cdNumber')->toArray();
		$this->assertEquals(['456789'],$list);

		Code::remove('456789');
		$list = Code::all()->toArray();
		$this->assertEquals([],$list);

		Code::add('123');
		Code::add('456');
		Code::add('789');
		$list = array_values(Code::lists());
		
		$this->assertEquals(3,count($list));
		Code::remove('123');
		Code::remove('456');
		Code::remove('789');

		// print_r($list);
	}

	public function testMonth(){
		$this->markTestSkipped('month calc spec is ok');
		$crawl = new \App\Library\Crawl('051360');
		echo $crawl->targetDate;
		$this->assertEquals(false,$crawl->checkDone('160712'));
		$this->assertEquals(false,$crawl->checkDone('160612'));
		$this->assertEquals(false,$crawl->checkDone('160512'));
		$this->assertEquals(false,$crawl->checkDone('160412'));
		$this->assertEquals(false,$crawl->checkDone('160312'));
		$this->assertEquals(false,$crawl->checkDone('160212'));
		$this->assertEquals(true,$crawl->checkDone('160112'));
		$this->assertEquals(false,$crawl->checkDone('180612'));
		$this->assertEquals(true,$crawl->checkDone('11022612'));
	}

	public function testDataProcess(){
		$this->markTestSkipped('dataprocess spec is ok');

		$date = '    2016.07.18     ';
		$date1 = '    2016.07.18';
		$date2 = '2016.07.18   ';
		$date3 = '2016.07.18';
		$process = new \App\Library\Data();
		$this->assertEquals('160718',$process->Row0($date));
		$this->assertEquals('160718',$process->Row0($date1));
		$this->assertEquals('160718',$process->Row0($date2));
		$this->assertEquals('160718',$process->Row0($date3));

		$row1_1 = '122,323';
		$row1_2 = '1212,122,323';
		$row1_3 = '0';
		$this->assertEquals(122323,$process->Row1($row1_1));
		$this->assertEquals(1212122323,$process->Row1($row1_2));
		$this->assertEquals(0,$process->Row1($row1_3));

		$this->assertEquals(-123456789,$process->Row2('123,456,789','tah p11 nv01'));
		$this->assertEquals(123456789,$process->Row2('123,456,789','tah p11 red02'));
		$this->assertEquals(0,$process->Row2('0','tah p11 nv01'));		
		$this->assertEquals(0,$process->Row2('0','tah p11 red02'));

		$this->assertEquals(2.44,$process->Row3('+2.44%'));
		$this->assertEquals(-0.91,$process->Row3('-0.91%'));
		$this->assertEquals(0.65,$process->Row3('+0.65%'));

		$this->assertEquals(283613,$process->Row4('283,613'));
		$this->assertEquals(884040,$process->Row4('884,040'));
		$this->assertEquals(1295338,$process->Row4('1,295,338'));

		$this->assertEquals(18225,$process->Row5('+18,225'));
		$this->assertEquals(41114,$process->Row5('+41,114'));
		$this->assertEquals(-2819,$process->Row5('-2,819	'));

		$this->assertEquals(70385,$process->Row6(' +70,385 '));
		$this->assertEquals(-874,$process->Row6('   -874   '));
		$this->assertEquals(87883,$process->Row6('     +87,883'));

		$this->assertEquals(3989766,$process->Row7(' 3,989,766'));
		$this->assertEquals(3890392,$process->Row7('   3,890,392  '));

		$this->assertEquals(23.87,$process->Row8(' 23.87% '));
		$this->assertEquals(23.87,$process->Row8('   23.87%  '));
		$this->assertEquals(20.94,$process->Row8('     20.94%'));
	}

	public function testCrawl(){
		$this->markTestSkipped('crawl spec is ok');

		$crawl = new \App\Library\Crawl('051360');
		$url = $crawl->getUrl();
		$this->assertEquals($url,'http://finance.naver.com/item/frgn.nhn?code=051360&page=1');

		$this->assertEquals(false,$crawl->rowCheck(0));
		$this->assertEquals(false,$crawl->rowCheck(1));
		$this->assertEquals(true,$crawl->rowCheck(3));
		$this->assertEquals(true,$crawl->rowCheck(11));
		$this->assertEquals(true,$crawl->rowCheck(22));
		$this->assertEquals(false,$crawl->rowCheck(35));

		$data = $crawl->getHtml($url);
		// print_r($data);
		echo 'end';
	}

	public function testGetCode(){
		$this->markTestSkipped('geting code spec is ok');
		$crawl = new \App\Library\Crawl('051360');
		// echo $crawl->targetDate; //160118
		$data = $crawl->getCodeData();
		// print_r($data);
	}
}
