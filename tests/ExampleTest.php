<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

	public function testDateUpdate(){
		$this->markTestSkipped('geting code spec is ok');

        DB::table('stCode')->truncate();
        DB::table('stDayData')->truncate();

        $code = new Code;
        $code->cdNumber = '051360';
        $code->cdName = '토비스';
        $code->cdOldUpdate = '160101';
        $code->cdLastUpdate = '160701';
        $old = '160119';
        $last = '160719';

        $ret = $code->saveUpdateDate($old,$last);
        $this->assertEquals(true,$ret);

        $code = Code::find(1);
		$this->assertEquals($code->cdOldUpdate,$code->cdOldUpdate);
		$this->assertEquals($last,$code->cdLastUpdate);

		$code->cdOldUpdate = '160101';
        $code->cdLastUpdate = '160719';
        $code->save();
        $old = '160119';
        $last = '160719';

        $ret = $code->saveUpdateDate($old,$last);
        $this->assertEquals(false,$ret);
        $code = Code::find(1);
		$this->assertEquals('160101',$code->cdOldUpdate);
		$this->assertEquals('160719',$code->cdLastUpdate);

		$code->cdOldUpdate = '';
        $code->cdLastUpdate = '';
        $code->save();
        $old = '160119';
        $last = '160719';

        $ret = $code->saveUpdateDate($old,$last);
        $this->assertEquals(true,$ret);
        $code = Code::find(1);
		$this->assertEquals('160119',$code->cdOldUpdate);
		$this->assertEquals('160719',$code->cdLastUpdate);
	}

	public function testUpdateCodeName(){
		$this->markTestSkipped('geting code spec is ok');

		DB::table('stCode')->truncate();
		$code1 = new Code;
        $code1->cdNumber = '051360';
        $code1->save();

        $code1->saveUpdateName('이런헐헐');

        $code = Code::find(1);
        $this->assertEquals('이런헐헐',$code->cdName);
	}

	# 멀티 코드 업데이트(전체)
	public function testMultiCode(){
        // $this->markTestSkipped('geting code spec is ok');

        $crawl = new \App\Library\Crawl();

        DB::table('stCode')->truncate();
        DB::table('stDayData')->truncate();

        $code1 = new Code;
        $code1->cdNumber = '051360';
        $code1->cdName = '토비스';
        $code1->save();

        $code2 = new Code;
        $code2->cdNumber = '005930';
        $code2->cdName = '삼성전자';
        $code2->save();

        $code3 = new Code;
        $code3->cdNumber = '000660';
        $code3->cdName = 'SK하이닉스';
        $code3->save();

        $code4 = new Code;
        $code4->cdNumber = '066830';
        $code4->cdName = '제노텍';
        $code4->save();

        $crawl = new Crawl();
        foreach(Code::all() as $code){
        	$crawl->reset();
        	$result = $crawl->getCodeData($code);
        	$code->saveUpdateDate($crawl->oldestDate, $crawl->lastestDate);

	        foreach ($result as $key => $value) {
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
        $cnt = DayData::all()->count();
        $this->assertEquals(true,$cnt > 0);
    }

    # 싱글 코드 업데이트
    public function testInsertDayilyData(){
        $this->markTestSkipped('geting code spec is ok');

        $crawl = new \App\Library\Crawl();

        DB::table('stCode')->truncate();
        DB::table('stDayData')->truncate();

        $code = new Code;
        $code->cdNumber = '051360';
        $code->cdName = '토비스';
        // $code->cdLastUpdate = '160716';
        $code->save();

        $code = Code::find(1);
        $crawl = new Crawl($code);
        $result = $crawl->getCodeData();
        $code->saveUpdateDate($crawl->oldestDate, $crawl->lastestDate);
        
        foreach ($result as $key => $value) {
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
        $cnt = DayData::all()->count();
        $this->assertEquals(true,$cnt > 0);
    }

    public function testCheckDup(){
        $this->markTestSkipped('geting code spec is ok');
        $crawl = new \App\Library\Crawl();

        DB::table('stCode')->truncate();
        DB::table('stDayData')->truncate();

        $code1 = new Code;
        $code1->cdNumber = '051360';
        $code1->cdName = '토비스';
        $code1->cdLastUpdate = '160701';
        $code1->save();

        $code2 = new Code;
        $code2->cdNumber = '005930';
        $code2->cdName = '삼성전자';
        $code2->cdLastUpdate = '160801';
        $code2->save();

        $code3 = new Code;
        $code3->cdNumber = '000660';
        $code3->cdName = 'SK하이닉스';
        $code3->cdLastUpdate = '';
        $code3->save();

        $code4 = new Code;
        $code4->cdNumber = '066830';
        $code4->cdName = '제노텍';
        $code4->cdLastUpdate = '140101';
        $code4->save();

        $lists = Code::all()->toArray();
        $this->assertEquals(4,count($lists));

        DB::table('stCode')->truncate();
        DB::table('stDayData')->truncate();

        $code1 = new Code;
        $code1->cdNumber = '051360';
        $code1->cdName = '토비스';
        $code1->cdLastUpdate = '160712';
        $code1->save();

        $code = Code::find(1);
        $this->assertEquals(true,is_object($code));
        $this->assertEquals('App\\Code',get_class($code));
        
        $crawl = new Crawl($code);
        $this->assertEquals($code1->cdNumber,$crawl->currentCode);
        $this->assertEquals($code->id,$crawl->currentCodeIdx);
        $this->assertEquals($code1->cdName,$crawl->currentName);
        $this->assertEquals($code1->cdLastUpdate,$crawl->currentLastUpdate);

        $result = $crawl->getCodeData();
        print_r($result);
        $lastestDate = $crawl->lastestDate;
        print_r($code->cdLastUpdate.' => '.$crawl->lastestDate.' ~ '.$crawl->oldestDate);
        $this->assertEquals(true,$crawl->lastestDate > $code->cdLastUpdate);
        $this->assertEquals(true,$crawl->oldestDate > $code->cdLastUpdate);
        $code->cdLastUpdate = $crawl->lastestDate;
        $code->save();

        $code = Code::find(1);
        $this->assertEquals(true,$code->cdLastUpdate == $crawl->lastestDate);
    }

	public function testGetCode(){
		$this->markTestSkipped('geting code spec is ok');

		$crawl = new \App\Library\Crawl();

        # Code::where('id','>','0')->delete();
        // DB::table('stCode')->truncate();
        // $code1 = new Code;
        // $code1->cdNumber = '051360';
        // $code1->cdName = '토비스';
        // $code1->save();

        // $code2 = new Code;
        // $code2->cdNumber = '005930';
        // $code2->cdName = '삼성전자';
        // $code2->save();
        $lists = Code::all()->toArray();
        // print_r($lists);
        $this->assertEquals(2,count($lists));
        // echo count($lists).'<==';

        $results = [];
        foreach($lists as $item){
            $crawl->currentCode = $item['cdNumber'];
            $crawl->currentPage = 1;
            $crawl->currentCodeIdx = $item['id'];
            $results[] = $crawl->getCodeData();
        }
        // print_r($results);
        $this->assertEquals(2,count($results));

        #DB::table('stCode')->truncate();
        // Code::where('id','>','0')->delete();
        // $lists = Code::all()->toArray();
        // $this->assertEquals(0,count($lists));
		


	}
}
