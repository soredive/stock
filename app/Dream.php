<?php

namespace App;

class Dream
{
	public $lists = [];
	public $who = '';
	public $phone = '';
	public $type = '';
	public $imgPath = '';

	public $url = 'https://www.munjanara.co.kr/MSG/send/web_admin_send.htm';

	public $toReal = '01073473652,01053663650,01035403652,01037510691,01028105270,01082223650';

	public $toTest = '01028105270';

	public $datas = [
		'userid' => 'revel216',
		'passwd' => 'deae934jd89sf3',
		'sender' => '01028105270',
		'receiver' => '',
		'message' => '',
	];

	public function __construct($who='',$phone='',$type='')
	{
		$this->datas['receiver'] = $this->toReal;
		// $this->datas['receiver'] = $this->toTest;
		$this->imgPath = base_path().'/resources/img.png';

		$this->who=$who;
		$this->phone=$phone;
		$this->type=$type;
	}

	public function send(){
		$this->makeMsg();
		$this->toeuc();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->datas);
		curl_exec($ch);
	}

	public function getTime(){
		$a=['일','월','화','수','목','금','토'];
		$b=['am'=>'오전','pm'=>'오후'];
		return date('n월j일('.$a[date('w')].') '.$b[date('a')].'g시i분');
	}

	public function makeMsg()
	{
		$ret = [];
		$ret[] = $this->type == 1 ? '[전화요청]' : '[견적요청]';
		$ret[] = $this->who ." 님(".$this->phone.")이 ".($this->type == 1 ? '전화' : '견적')."요청했습니다 ".$this->getTime();
		$msg = implode('', $ret);
		$this->datas['message'] = $msg;
		return $this->datas['message'];
	}

	public function toeuc($str=''){
		if($str=='') $str = $this->makeMsg();
		$this->datas['message'] = iconv('UTF-8', 'EUC-KR', $this->datas['message']);
	}
}
