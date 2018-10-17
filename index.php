<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() { //rota para o site
	
	$page = new Page();
	$page->setTpl("index");	

});

$app->get('/admin', function() { //rota para a área de administração
	
	$page = new PageAdmin();
	$page->setTpl("index");	

});


$app->run();

 ?>