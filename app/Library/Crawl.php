<?php
namespace App\Library;

// use \anlutro\cURL\cURL;
use \Goutte\Client;
use \App\Exceptions\Handler;
use \App\Library\Data;

interface CrawlInterface {
	public function getHtml($url);
	public function getUrl();
	
	public function rowCheck($idx);
}

class Crawl implements CrawlInterface{

	public $codeLists;
	public $baseUrl;
	public $curl;

	public $currentCode;
	public $currentPage;

	public $processData;

	private $validRow = [3,4,5,6,7,11,12,13,14,15,19,20,21,22,23,27,28,29,30,31];

	public $targetDate = '';
	public $tryCnt = 0;

	public $data;
	private $tempData;
	public $chkDone = false;
	public $going = true;
	public $goCnt = 0;
	
	public function __construct($code = null, $page = 1){
		if(isset($code)) $this->currentCode = $code;
		if(isset($page)) $this->currentPage = $page;

		$this->client = new Client();
		$this->processData = new \App\Library\Data();
		$this->data = [];
		$this->targetDate = $this->getMonthDate();
		$this->baseUrl = 'http://finance.naver.com/item/frgn.nhn';
		$this->chkDone = false;

		$this->init();
	}

	public function getCodeData($code = null, $page = null){
		if($code) 
			$this->currentCode = $code;
		if($page) 
			$this->currentPage = $page;
		else 
			$page = 1;
		$this->goCnt = 0;
		$result = [];
		$this->chkDone = false;

		while($this->chkDone == false && $this->goCnt < 20){
			$url = $this->getUrl();
			$pageData = $this->getHtml();
			$result = array_merge($result, $pageData);
			$this->goCnt++;
			$this->currentPage++;
		}
		return $result;
	}

	public function checkDone($date){
		if(strlen($date) != 6 || strlen($this->targetDate) != 6){
			throw new \Exception('date calc is failed');
		}
		return $date < $this->targetDate;
	}

	public function getMonthDate(){
		return date("ymd", strtotime('-6 month'));
	}

	public function rowCheck($idx){
		return in_array($idx, $this->validRow);
	}

	public function getUrl(){
		if(!$this->currentCode){
			throw new \Exception('no code');
		}
		return $this->baseUrl . '?code=' .$this->currentCode . '&page=' . $this->currentPage;
	}

	public function init(){
		// $this->codeLists = ['051360'];
	}

	public function getHtml($url = null){

		$url = $this->getUrl();
		$crawler = $lists = $this->client->request('GET',$url);
		
		$table = $crawler->filter('.section.inner_sub table')->eq(1);
		// var_dump($table);
		$str = '';
		$tr = $table->filter('tr')->reduce(function(\Symfony\Component\DomCrawler\Crawler $node, $idx){
			return $this->rowCheck($idx);
		});
		$this->tempData = [];
		$this->chkDone = false;
		$tr->each(function(\Symfony\Component\DomCrawler\Crawler $curTr, $idx){
			$spans = $curTr->filter('span');

			$data['code'] = $this->currentCode;
            $data['ddDate'] = $this->processData->Row0($spans->eq(0)->text());
            $data['ddJongGa'] = $this->processData->Row1($spans->eq(1)->text());
            $data['ddJulIlBi'] = $this->processData->Row2($spans->eq(2)->text(), $spans->eq(2)->attr('class'));
            $data['ddDeunRakPok'] = $this->processData->Row3($spans->eq(3)->text());
            $data['ddGeRaeRyang'] = $this->processData->Row4($spans->eq(4)->text());
            $data['ddSunMaeMae'] = $this->processData->Row5($spans->eq(5)->text());
            $data['ddForSunMaeMae'] = $this->processData->Row6($spans->eq(6)->text());
            $data['ddForBoYuJuSu'] = $this->processData->Row7($spans->eq(7)->text());
            $data['ddforBoYuYul'] = $this->processData->Row8($spans->eq(8)->text());

            

            if($this->checkDone($data['ddDate'])){
            	$this->chkDone = true;
            	echo 'Done!!!'.$data['ddDate'].'<--';
            }else{
            	$this->tempData[] = $data;	
            }
		});
		return $this->tempData;
	}

	public function add(){
		
	}

	public function getDate(){

	}

	public function delete(){

	}

	public function insert(){

	}

	public function get(){
		return 'abc';
	}
}




