<?php

class Conexion{

	public function conectar(){

		$link = new PDO("mysql:host=localhost;dbname=estancia","root","depurines");		
		 $link->query("SET NAMES 'utf8'");

         //set the PDO error mode to exception
         $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $link;
	}

}

?>