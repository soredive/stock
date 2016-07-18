<?php
namespace App\Library;

class Data {
	public function getPlusMinus($class = ''){
		$val = 1;
		if($class){
			if(strpos($class, 'red') !== false){
				
			}elseif(strpos($class, 'nv0') !== false){ // navy color => minus
				$val = -1;
			}
		}
		return $val;
	}
	public function toFloat($str){
		$str = trim($str);
		$str = str_replace(',', '', $str);
		$str = str_replace('%', '', $str);
		return floatval($str);
	}
	public function toInt($str){
		$str = trim($str);
		$str = str_replace(',', '', $str);
		return intval($str);
	}
	public function Row0($str){
		$str = trim($str);
		if(strlen($str) != 10){
			throw new Exception('data type error length');
		}
		$str = str_replace('.', '', $str);
		$str = substr($str, -6);
		return $str;
	}
	public function Row1($str){
		return $this->toInt($str);

	}
	public function Row2($str, $class = ''){
		$val = $this->toInt($str);
		$val *= $this->getPlusMinus($class);
		return $val;
	}
	public function Row3($str){
		return $this->toFloat($str);
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
	public function Row7($str){
		return $this->toInt($str);
	}
	public function Row8($str){
		return $this->toFloat($str);
	}
}




