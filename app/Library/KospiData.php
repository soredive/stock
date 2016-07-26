<?php
namespace App\Library;

class KospiData extends Data {
	public function Row0($str){
		return $this->toInt($str);
		
	}
	public function Row1($str){
		$val = trim($str);
		return $val;
	}
	public function Row2($str, $class = ''){
		$val = $this->toInt($str);
		return $val;
	}
	public function Row3($str, $class = ''){
		$val = $this->toInt($str);
		$val *= $this->getPlusMinus($class);
		return $val;
	}
	public function Row4($str){
		return $this->toFloat($str);
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
	public function Row9($str){
		return $this->toInt($str);
	}
	public function Row10($str){
		return $this->toFloat($str);
	}
	public function Row11($str){
		return $this->toFloat($str);
	}
}




