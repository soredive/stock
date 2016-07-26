<?php
namespace App\Library;

// use \anlutro\cURL\cURL;
use \Goutte\Client;
use \App\Exceptions\Handler;
use \App\Library\Data;

interface CrawlInterface {
	
	const maxTryCnt = 20;

	public function getHtml($url);
	public function getUrl();
	public function rowCheck($idx);
}

class Crawl implements CrawlInterface{
	protected $baseUrl = 'http://finance.naver.com/item/frgn.nhn';
	protected $validRow = [3,4,5,6,7,11,12,13,14,15,19,20,21,22,23,27,28,29,30,31];

	protected $client;
	protected $processData;
	protected $tempData;
	protected $targetDate = '';
	
	public $currentCode;
	public $currentCodeIdx;
	public $currentName;
	public $currentLastUpdate = '';
	public $currentPage;

	public $lastestDate;
	public $oldestDate;

	public $tryCnt = 0;

	public $chkDone = false;
	public $going = true;
	public $goCnt = 0;
	
	
	public function __construct($code = null, $page = 1){
		if(isset($code)){
			if(is_object($code) && 'App\\Code' == get_class($code)){
				$this->fromCode($code);
			}else{
				$this->currentCode = $code;		
			}
		} 
		if(isset($page)) $this->currentPage = $page;

		$this->client = new Client();
		$this->processData = new \App\Library\Data();
		$this->targetDate = $this->getMonthDate();
		
		$this->chkDone = false;
	}

	public function reset(){
		$this->currentCode = null;
		$this->currentCodeIdx = null;
		$this->currentName = null;
		$this->currentLastUpdate = '';
		$this->currentPage = null;

		$this->lastestDate = null;
		$this->oldestDate = null;
		$this->tryCnt = 0;

		$this->chkDone = false;
		$this->going = true;
		$this->goCnt = 0;
	}

	public function fromCode($code){
		$this->currentCode = $code->cdNumber;
		$this->currentCodeIdx = $code->id;
		$this->currentName = $code->cdName;
		$this->currentLastUpdate = $code->cdLastUpdate;
	}

	public function getCodeData($code = null, $page = null){
		if($code) {
			if(is_object($code) && 'App\\Code' == get_class($code)){
				$this->fromCode($code);
			}else{
				$this->currentCode = $code;		
			}
		}
		if($page) 
			$this->currentPage = $page;
		else 
			$this->currentPage = 1;
		$this->goCnt = 0;
		$result = [];
		$this->chkDone = false;

		while($this->chkDone == false && $this->goCnt < Crawl::maxTryCnt){
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
		if($this->currentLastUpdate && $date <= $this->currentLastUpdate){
			return true; // 날자가 마지막 수정일보다 작거나 같으면 끝내라
		}
		return $date < $this->targetDate; // true=> 이제 끝내라
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

	public function checkValidRow($node){
		return $node->count() > 0; // nodes must have 1 more spans
	}

	public function getHtml($url = null){

		$url = $this->getUrl();
		$crawler = $this->client->request('GET',$url);
		
		$table = $crawler->filter('.section.inner_sub table')->eq(1);

		$str = '';
		$tr = $table->filter('tr')->reduce(function(\Symfony\Component\DomCrawler\Crawler $node, $idx){
			return $this->rowCheck($idx);
		});
		$this->tempData = [];
		$this->chkDone = false;

		$rowError = false;
		$tr->each(function(\Symfony\Component\DomCrawler\Crawler $curTr, $idx){
			if($this->chkDone){
				return false;
			}
			$spans = $curTr->filter('span');

			/* TODO ORM모델로 하려다가 퍼포먼스가 일반 배열이 편할 것 같아서 plain object 로 할거냐 모델로 할거냐 배열로 할거냐.... 나중에 변경해도 괜찮을 듯 */
			// for db insert info
			$data['code'] = $this->currentCode;
			$data['codeIdx'] = $this->currentCodeIdx;

			if($this->checkValidRow($spans) === false){
				$this->chkDone = true;
				return false;
			}

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
            	// echo 'Done!!!'.$data['ddDate'].'<--';
            }else{
            	if($this->lastestDate < $data['ddDate']){
            		$this->lastestDate = $data['ddDate'];
            	}
           		$this->oldestDate = $data['ddDate'];
            	$this->tempData[] = $data;	
            }
		});
		return $this->tempData;
	}	
}




