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

$app->get('/admin/users',function(){ // listar os usuarios
	
	User::verifyLogin(); // verificando se o usuario esta logado e se é admin

	$users = User::listAll(); // metodo que vai listar os usuarios
	//var_dump($users);die;
	$page = new PageAdmin();
	$page->setTpl("users", array(

		"users"=>$users
	));

});

$app->get('/admin/users/create',function(){ // adicionar usuarios
	
	User::verifyLogin(); 
	$page = new PageAdmin();
	$page->setTpl("users-create");

});

$app->get("/admin/users/:iduser/delete",function($iduser){ // apagar um usuario
	
	User::verifyLogin();
	$user = new User();
	$user->get($iduser);
	$user->delete();
	header("Location: /admin/users");
	exit;
	
});


$app->get('/admin/users/:iduser', function($iduser){ // editar usuarios
	
	User::verifyLogin(); 
	$page = new PageAdmin();
	$user = new User();
	$user->get((int)$iduser); 
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));
	
});

$app->post('/admin/users/create',function(){ // rota para salvar no momento de adicionar um usuario

	User::verifyLogin();

	$user = new User();
	$_POST["inadmin"] =(isset($_POST["inadmin"]))?1:0; // verificando se é administrador
	$user->setData($_POST);
	$user->save(); // vai executar o insert dentro do banco

	header("Location: /admin/users");
	exit;
});

$app->post('/admin/users/:iduser', function($iduser){ // rota para salvar no momento de editar um usuario

	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$_POST["inadmin"] =(isset($_POST["inadmin"]))?1:0; 
	$user->setData($_POST);
	$user->update();

	header("Location: /admin/users");
	exit;
});




$app->run();

 ?>