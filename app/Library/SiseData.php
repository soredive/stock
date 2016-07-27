<?php
namespace App\Library;

class SiseData extends Data {
	public function Row0($str){
		$str = trim($str);
		if(strlen($str) != 10){
			throw new \Exception('data type error length'.$str);
		}
		$str = str_replace('.', '', $str);
		$str = substr($str, -6);
		return $str;
	}
	public function Row1($str){
		$val = $this->toInt($str);
		return $val;
	}
	public function Row2($str, $class = ''){
		$val = $this->toInt($str);
		return $val;
	}
	public function Row3($str){
		$val = $this->toInt($str);
		return $val;
	}
	public function Row4($str){
		return $this->toInt($str);
	}
	public function Row5($str){
		return $this->toInt($str);
	}
	public function Row6($str){
		return $this->toInt($str);
	}
}




