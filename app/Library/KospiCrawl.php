<?php
namespace App\Library;

use \Goutte\Client;
use \App\Exceptions\Handler;
use \App\Library\Data;

class KospiCrawl extends \App\Library\Crawl{
	protected $baseUrl = 'http://finance.naver.com/sise/sise_market_sum.nhn';
	protected $currentDate = null;
	protected $validRow = [
		2,3,4,5,6,		10,11,12,13,14,		18,19,20,21,22,		26,27,28,29,30,		34,35,36,37,38,		39,40,41,42,43,		47,48,49,50,51,		52,53,54,55,56,		60,61,62,63,64,		68,69,70,71,72
	];

	public function __construct(){
		$this->currentPage = 1;
		$this->currentDate = date('ymd');

		$this->client = new Client();
		$this->processData = new \App\Library\KospiData();// 이거는 수정해야됨
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

	public function getUrl(){
		return $this->baseUrl . '?page=' . $this->currentPage;
	}

	public function getHtml($url = null){
		if(!$url){
			$url = $this->getUrl();	
		}
		$crawler = $this->client->request('GET',$url);
		
		$table = $crawler->filter('#contentarea table.type_2 tbody');

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
			$tds = $curTr->filter('td');

			/* TODO ORM모델로 하려다가 퍼포먼스가 일반 배열이 편할 것 같아서 plain object 로 할거냐 모델로 할거냐 배열로 할거냐.... 나중에 변경해도 괜찮을 듯 */
			// for db insert info
			$data['code'] = $this->currentCode;
			$data['codeIdx'] = $this->currentCodeIdx;

			if($this->checkValidRow($tds) === false){
				$this->chkDone = true;
				return false;
			}
			// ...ing
			// $code = 
			// $data['stCodeIdx'] = $this->processData->Row0($tds->eq(0)->text());

			$data['ksDate'] = $this->currentDate;
			$data['ksRank'] = $this->processData->Row0($tds->eq(0)->text());
			
			$data['ksHyunJaeGa'] = $this->processData->Row2($tds->eq(2)->text());
			$data['ksJulIlBi'] = $this->processData->Row3($tds->eq(3)->filter('span')->text(), $tds->eq(3)->filter('span')->attr('class'));
			$data['ksDeunRakPok'] = $this->processData->Row4($tds->eq(4)->text());
			$data['ksAekMyunGa'] = $this->processData->Row5($tds->eq(5)->text());
			$data['ksSiGaChongAek'] = $this->processData->Row6($tds->eq(6)->text());
			$data['ksSangJangJuSu'] = $this->processData->Row7($tds->eq(7)->text());
			$data['ksForBiYul'] = $this->processData->Row8($tds->eq(8)->text());
			$data['ksGaeRaeRyang'] = $this->processData->Row9($tds->eq(9)->text());
			$data['ksPER'] = $this->processData->Row10($tds->eq(10)->text());
			$data['ksROE'] = $this->processData->Row11($tds->eq(11)->text());
            
            ## 테스트
            $this->chkDone = true;
            ## 테스트
		});
		return $this->tempData;
	}	
}