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
	public function testTTT(){
		// $this->markTestSkipped('not need this');
		$crawl = new \App\Library\SiseCrawl();
        $list = \App\Code::all();
        foreach($list as $no => $code){
        	$crawl->reset();
        	$result = $crawl->getCodeData($code);
        	$code->saveUpdateDateSise($crawl->oldestDate, $crawl->lastestDate);

	        foreach ($result as $key => $value) {
                $exist = \App\Sise::whereRaw('stCodeIdx = ? and ssDate = ?',array($value['codeIdx'],$value['ssDate']))->count();
                if($exist < 1){
                    \App\Sise::create([
                        'stCodeIdx'=>$value['codeIdx']
                        ,'ssDate'=>$value['ssDate']
                        ,'ssJongGa'=>$value['ssJongGa']
                        ,'ssJulIlBi'=>$value['ssJulIlBi']
                        ,'ssSiGa'=>$value['ssSiGa']
                        ,'ssGoGa'=>$value['ssGoGa']
                        ,'ssJeoGa'=>$value['ssJeoGa']
                        ,'ssGeRaeRyang'=>$value['ssGeRaeRyang']
                    ]); 
                }
	        }
            echo ($no+1).PHP_EOL;
        }
	}
	public function testDelete(){
		$this->markTestSkipped('not need this');

		DB::table('stDownloadHistory')->truncate();

		$d = new Downloader;
		// $d->saveDownHistory('kbs','jjang');

		// $dh = DownloadHistory::find(1);

		// $dh->created_at = '2016-07-28 09:42:48';
		// $dh->save();
		$d->createFolder();

		$d->clearFolderFile($d->folderPath,$d->pathZipfile);

		//$d->deleteDownHistory();

		$this->assertEquals(0,DownloadHistory::count());
	}

	public function testDonwloadHis(){
		$this->markTestSkipped('not need this');

		DB::table('stDownloadHistory')->truncate();

		$d = new Downloader;
		$d->saveDownHistory('kbs','jjang');
		$d->saveDownHistory('kbs2','jjang2');
		$d->saveDownHistory('kbs3','jjang3');
		$d->saveDownHistory('kbs4','jjang4');

		$dh = DownloadHistory::find(1);
		$dh2 = DownloadHistory::find(2);
		$dh3 = DownloadHistory::find(3);
		$dh4 = DownloadHistory::find(4);

		$dh2->created_at = '2016-07-28 09:42:48';
		$dh2->save();

		$dh3->created_at = '2016-07-27 09:42:48';
		$dh3->save();

		$dh4->created_at = '2016-07-28 19:42:48';
		$dh4->save();

		$this->assertEquals(4,DownloadHistory::count());

		$d->deleteDownHistory();

		$this->assertEquals(2,DownloadHistory::count());
	}

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
