<?php

require __DIR__ . '/../vendor/autoload.php';

use Syme\Syme;

$messager = new Syme('http://localhost:8080/message', 'KVx6RU5Lc1w9VGVzdGU9Ki50aCJNJzcK', 'exchange-syme', 'Houer Syme');
$messager->toggleDebug();

try {

	$output = $messager->send("Lorem ipsum dolor sit amet, consectetur adipiscing elit.", '#', null, "User", "warning", null, array("test" => true));

	var_dump($output);

} catch (Exception $e) {

	var_dump($e);

}

?>
