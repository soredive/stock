<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\Library\Crawl;
use \App\Library\Data;
use \App\Code;
use \App\DayData;

use \App\Kospi;

class KospiTest extends TestCase
{
	public function testGogogo(){
        // $this->markTestSkipped('get kospi spec is ok');
		DB::table('stDayData')->truncate();
		$crawl = new Crawl();
        foreach(Code::all() as $code){
        	$crawl->reset();
        	$result = $crawl->getCodeData($code);
        	$code->saveUpdateDate($crawl->oldestDate, $crawl->lastestDate);

	        foreach ($result as $key => $value) {
	        	$exist = \App\DayData::whereRaw('stCodeIdx = ? and ddDate = ?',array($value['codeIdx'],$value['ddDate']))->count();
	        	if($exist < 1){
	        		DayData::create(array(
	        		    'stCodeIdx'=>$value['codeIdx']
	        		    ,'ddDate'=>$value['ddDate']
	        		    ,'ddJongGa'=>$value['ddJongGa']
	        		    ,'ddJulIlBi'=>$value['ddJulIlBi']
	        		    ,'ddDeunRakPok'=>$value['ddDeunRakPok']
	        		    ,'ddGeRaeRyang'=>$value['ddGeRaeRyang']
	        		    ,'ddSunMaeMae'=>$value['ddSunMaeMae']
	        		    ,'ddForSunMaeMae'=>$value['ddForSunMaeMae']
	        		    ,'ddForBoYuJuSu'=>$value['ddForBoYuJuSu']
	        		    ,'ddforBoYuYul'=>$value['ddforBoYuYul']
	        		));	
	        	}
	        }
        }
	}
	public function testCode(){
		$this->markTestSkipped('get kospi spec is ok');
		DB::table('stCode')->truncate();
		DB::table('stDayData')->truncate();
		DB::table('ksKospi')->truncate();

        $k = new App\Library\KospiCrawl;
        $code = new \App\Code;

        $list = $k->getTodayData();

        foreach($list as $item){
        	$codeIdx = Code::addIfNotExist($item['ksCode'],$item['ksTempCompanyName'],$item['ksRank']);
			Kospi::create(array(
	        	'ksDate' => $item['ksDate']
	        	,'stCodeIdx' => $codeIdx
				,'ksCode' => $item['ksCode']
				,'ksTempCompanyName' => $item['ksTempCompanyName']
				,'ksRank' => $item['ksRank']
				,'ksHyunJaeGa' => $item['ksHyunJaeGa']
				,'ksJulIlBi' => $item['ksJulIlBi']
				,'ksDeunRakPok' => $item['ksDeunRakPok']
				,'ksAekMyunGa' => $item['ksAekMyunGa']
				,'ksSiGaChongAek' => $item['ksSiGaChongAek']
				,'ksSangJangJuSu' => $item['ksSangJangJuSu']
				,'ksForBiYul' => $item['ksForBiYul']
				,'ksGaeRaeRyang' => $item['ksGaeRaeRyang']
				,'ksPER' => $item['ksPER']
				,'ksROE' => $item['ksROE']
			));
        }
        $cnt = Kospi::count();
		$this->assertEquals(true,$cnt > 0);
	}

	public function testCodeInsert(){
		$this->markTestSkipped('get kospi spec is ok');

		$c1 = '001800'; // 오리온
		$c2 = '005930'; // 삼성

		$this->assertEquals(false,Code::addIfNotExist('001800','오리온'));
		$this->assertEquals(true,Code::addIfNotExist('005930','삼성'));
	}


}
