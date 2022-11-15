<?php
	class Example {
		//klassi juurde pannakse kirja: 
		//muutujad - klassis nimetatakse omadused ehk properties
		//privaatsed ja avalikud 
		private $secret_value;
		public $known_value = 7;
		private $received_value;
		
		//funktsioonid - klassis nimetatakse meetodid ehk methods
		
		//eriline funktsioon/meetod on konstruktor, mis käivitub klassi kasutuselevõtul kohe ja üks kord
		function __construct($value){
			echo "Klass käivitus. <br>";
			//klassi enda muutuajtele funktsioonis viitamine
			$this->secret_value = mt_rand(1,10);
			echo "Salajane väärtus on: " .$this->secret_value ."<br>";
			$this->received_value = $value;
			$this->multiply();
		}
		
		//destruktor, mis käivitub kui objekt tühistatakse
		function __destruct(){
			echo "klass lõpetas, ongi kõik.<br>";
		}
		
		private function multiply(){
			echo "Privaatsete väärtuste korrutis on: " .$this->secret_value * $this->received_value ."<br>";
		}
		
		public function add(){
			echo "Privaatsete väärtuste summa on: " .$this->secret_value + $this->received_value ."<br>";
		}
	}//class lõppeb