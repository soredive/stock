<?php
namespace App\Library;

use \Goutte\Client;
use \App\Exceptions\Handler;

class KospiCrawl extends \App\Library\Crawl{
	protected $maxTryCnt = 50; // 코스피는 25페이지까지 있음 현재는

	protected $baseUrl = 'http://finance.naver.com/sise/sise_market_sum.nhn';
	protected $currentDate = null;
	protected $validRow = [
		1,2,3,4,5,			9,10,11,12,13,		
		17,18,19,20,21,		25,26,27,28,29,		
		33,34,35,36,37,		41,42,43,44,45,		
		49,50,51,52,53,		57,58,59,60,61,	
		65,66,67,68,69,		73,74,75,76,77
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

	public function getCodeFromLink($link = ''){
		list($dummy, $code) = explode('main.nhn?code=', $link);
		return trim($code);
	}

	public function getTodayData($page = null){
		if($page) 
			$this->currentPage = $page;
		else 
			$this->currentPage = 1;
		$this->goCnt = 0;
		$result = [];
		$this->chkDone = false;

		while($this->chkDone == false && $this->goCnt < $this->maxTryCnt){
			$url = $this->getUrl();
			$pageData = $this->getHtml();
			$result = array_merge($result, $pageData);
			$this->goCnt++;
			$this->currentPage++;
		}
		return $result;
	}

	public function checkValidRow($node){
		return $node->count() > 1; // nodes must have 2 more tds
	}

	public function getHtml($url = null){
		if(!$url){
			$url = $this->getUrl();	
		}
		$crawler = $this->client->request('GET',$url,[],[],[
            'HTTP_COOKIE'=>'field_list=12|07ffffff'
        ]);
		
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

			if($this->checkValidRow($tds) === false){
				$this->chkDone = true;
				return false;
			}



			# code index will update later...
			# $data['codeIdx'] = $this->currentCodeIdx;

			# saving temp data for update later
			$data['ksTempCompanyName'] = $this->processData->Row1($tds->eq(1)->text());
			$data['ksCode'] = $this->getCodeFromLink($this->processData->Row1($tds->eq(1)->filter('a')->attr('href')));

			$data['ksDate'] = $this->currentDate;
			$data['ksRank'] = $this->processData->Row0($tds->eq(0)->text());
			
			$data['ksHyunJaeGa'] = $this->processData->Row2($tds->eq(2)->text());
			$data['ksJulIlBi'] = $this->processData->Row3($tds->eq(3)->filter('span')->text(), $tds->eq(3)->filter('span')->attr('class'));
			$data['ksDeunRakPok'] = $this->processData->Row4($tds->eq(4)->text());
			$data['ksAekMyunGa'] = $this->processData->Row5($tds->eq(5)->text());
			$data['ksGaeRaeRyang'] = $this->processData->Row9($tds->eq(6)->text());

			$data['ksGaeRaeDaeGueam'] = $this->processData->toInt($tds->eq(7)->text());
            $data['ksJunIlGaeRaeRyang'] = $this->processData->toInt($tds->eq(8)->text());
            $data['ksSiGa'] = $this->processData->toInt($tds->eq(9)->text());
            $data['ksGoGa'] = $this->processData->toInt($tds->eq(10)->text());
            $data['ksJeoGa'] = $this->processData->toInt($tds->eq(11)->text());
            $data['ksMaeSuHoGa'] = $this->processData->toInt($tds->eq(12)->text());
            $data['ksMaeDoHoGa'] = $this->processData->toInt($tds->eq(13)->text());
            $data['ksMaeSuChongJanRyang'] = $this->processData->toInt($tds->eq(14)->text());
            $data['ksMaeDoChongJanRyang'] = $this->processData->toInt($tds->eq(15)->text());
            $data['ksMaeChulAek'] = $this->processData->toInt($tds->eq(18)->text());
            $data['ksJaSanChongGae'] = $this->processData->toInt($tds->eq(19)->text());
            $data['ksBuChaeChongGae'] = $this->processData->toInt($tds->eq(20)->text());
            $data['ksYungUpEaIk'] = $this->processData->toInt($tds->eq(21)->text());
            $data['ksDangGiSunEaIk'] = $this->processData->toInt($tds->eq(22)->text());
            $data['ksJuDangSunEaIk'] = $this->processData->toInt($tds->eq(23)->text());
            $data['ksBoTongJuBaeDangGuem'] = $this->processData->toInt($tds->eq(24)->text());
            $data['ksMaeCulAekJungGaYul'] = $this->processData->toFloat($tds->eq(25)->text());
            $data['ksYoungUpEaIkJungGaYul'] = $this->processData->toFloat($tds->eq(26)->text());
            $data['ksROA'] = $this->processData->toFloat($tds->eq(30)->text());
            $data['ksPBR'] = $this->processData->toFloat($tds->eq(31)->text());
            $data['ksYuBoYul'] = $this->processData->toFloat($tds->eq(32)->text());

			$data['ksSangJangJuSu'] = $this->processData->Row7($tds->eq(16)->text());
			$data['ksSiGaChongAek'] = $this->processData->Row6($tds->eq(17)->text());

			$data['ksForBiYul'] = $this->processData->Row8($tds->eq(27)->text());
			$data['ksPER'] = $this->processData->Row10($tds->eq(28)->text());
			$data['ksROE'] = $this->processData->Row11($tds->eq(29)->text());

			if($idx == 0 || $idx == 1){
				$testingDuplicate = \App\Kospi::whereRaw('ksCode = ? and ksDate = ?',array($data['ksCode'], $data['ksDate']))->count();
				if($testingDuplicate > 0){
					$this->chkDone = true;
					return false;
				}
			}

			$this->tempData[] = $data;
		});
		return $this->tempData;
	}	
}