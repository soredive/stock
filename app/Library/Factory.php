<?php
namespace \App\Library;

use \App\Library\Crawl;
use \App\Library\Data;
use \App\Code;
use \App\DayData;

interface StockFactoryInterface{
	public function setSeperator($str);

	public function addCode($codeStr);
	public function listCode();
	public function deleteCode($codeStr);

	public function crawlCode($codeStr);
	public function crawlAllCode();
	
	public function crawlCode($codeStr);
	public function crawlAllCode();

	public function downloadCode($codeStr);
	public function downloadAllCode($codeStr);
}

class StockFactory implements StockFactory{
	private $crawl;
	private $code;

	public function __construct(){
		$crawl = new \App\Library\Crawl();
		$code = new \App\Code();
	}

	public function addCode($codeStr){
		$code = new Code();
		$code->cdNumber = $codeStr;
		$code->save();
	}
	public function listCode(){
		return Code::all()->toArray();
	}
	public function deleteCode($codeStr){
		Code::where('cdNumber','=',$codeStr)->delete();
	}

	public function crawlCode($codeStr){

	}
	public function crawlAllCode(){

	}

	public function setSeperator($str){

	}
	public function crawlCode($codeStr){

	}
	public function crawlAllCode(){

	}

	public function downloadCode($codeStr){

	}
	public function downloadAllCode($codeStr){

	}
}