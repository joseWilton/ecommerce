<?php 

session_start();

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() { //rota para o site
	
	$page = new Page();
	$page->setTpl("index");	

});

$app->get('/admin', function() { //rota para a área de administração
	
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("index");	

});

$app->get('/admin/login',function(){ //rota para pagina de login


	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);
	$page->setTpl("login");

});

$app->post('/admin/login',function(){ //validar o login

	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;	
});


$app->get('/admin/logout', function(){ // deslogar

	User::logout();
	header("Location: /admin/login");
	exit;
});

$app->run();

 ?>