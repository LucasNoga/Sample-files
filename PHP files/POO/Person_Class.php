<?php
class Person
{
	public $name = '';
	public $firstname = '';
	public $age = 0;
	const ENFANT = 1;
	const ADO = 2;
	const ADULTE = 3; 

	function __construct($name, $firstname, $age)
	{
		$this->name = $name;
		$this->firstname = $firstname;
		$this->age = $age;
	}

	function __destruct(){}

	function __tostring()
	{
		return ">>personne (
			$this->name, $this->firstname, $this->age) <br/>\n";
	}

	function vote()
	{
		// Code
	}

	function talk($message)
	{
		echo $message .''. self::ENFANT . '<br/>';
	}
}

?>