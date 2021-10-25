<?php
// struktura testovaci databaze je k dispozici v souboru testing_structures.sql

setlocale(LC_NUMERIC,"C"); // we need to display float like 123.456

function &dbmole_connection($dbmole){
	static $connections = array();

	if($dbmole->getDatabaseType()=="postgresql"){
		if(!isset($connections["postgresql"])){
			$connections["postgresql"] = pg_connect("dbname=test user=test password=test host=127.0.0.1");
		}
		return $connections["postgresql"];
	}

	if($dbmole->getDatabaseType()=="mysql"){
		if(!isset($connections["mysql"])){
			$connections["mysql"] = mysqli_connect("127.0.0.1","test","test");
			$connections["mysql"]->select_db("test");
		}
		return $connections["mysql"];
	}
}

// Creating testing structures
$GLOBALS["dbmole"] = PgMole::GetInstance();
$GLOBALS["dbmole"]->doQuery(file_get_contents(__DIR__."/structures.postgresql.sql"));

// === Creating testing table in mysql
$my = MysqlMole::GetInstance();
$script = file_get_contents(__DIR__."/structures.mysql.sql");
// dropping table
preg_match('/\n(DROP TABLE.*?);/s',$script,$matches);
$my->doQuery($matches[1]);
// creating table
preg_match('/\n(CREATE TABLE.*?);/s',$script,$matches);
$my->doQuery($matches[1]);
