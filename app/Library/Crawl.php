<?php
namespace App\Library;

// use \anlutro\cURL\cURL;
use \Goutte\Client;

use \App\Exceptions\Handler;

interface CrawlInterface {
	public function getHtml($url);
	public function getUrl();
}

class Crawl {

	public $codeLists;
	public $currentCode;
	public $baseUrl;
	public $curl;

	public function __construct(){
		$this->client = new Client();
		// $this->curl = new \anlutro\cURL\cURL;
		$this->baseUrl = 'http://finance.naver.com/item/frgn.nhn?code=';

		$this->init();
	}

	public function getUrl(){
		return $this->baseUrl.$this->codeLists[0];
	}

	public function init(){
		$this->codeLists = ['051360'];
	}

	public function getHtml($url = null){
		$url = $this->getUrl();
		$crawler = $lists = $this->client->request('GET',$url);
		
		$table = $crawler->filter('.section.inner_sub table')->first();
		$table->filter('tr')->each(function($node){
			
		});


		// $crawler->filter('#aside')->each(function ($node) {
		//     print $node->text()."\n";
		// });

		// $response = $this->curl->get();
		// if($response->statusCode != '200'){
		// 	// throw new Exception('Division by zero.');
		// 	echo 'error';
		// }
		return '11';
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




