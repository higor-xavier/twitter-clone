<?php

	namespace App\Controllers;

	//os recursos do miniframework
	use MF\Controller\Action;
	use MF\Model\Container;

	class AppController extends Action {

		public function timeline(){
			$this->validarAutenticacao();
			//recuperação dos tweets
			$tweet = Container::getModel('Tweet');
			$tweet->__set('id_usuario', $_SESSION['id']);
			$tweets = $tweet->getAll();

			$this->view->tweets = $tweets;

			$this->render('timeline');
		}

		public function tweet(){
			$this->validarAutenticacao();

			$tweet = Container::getModel('Tweet');
			$tweet->__set('tweet', $_POST['tweet']);
			$tweet->__set('id_usuario', $_SESSION['id']);

			//salvar tweet no BD
			$tweet->salvar();

			header('Location: /timeline');
		}

		public function quem_seguir(){
			$this->validarAutenticacao();
			$pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

			$usuarios = array();

			if($pesquisarPor != ''){
				$usuario = Container::getModel('Usuario');
				$usuario->__set('nome', $pesquisarPor);
				$usuarios = $usuario->getAll();
			}

			$this->view->usuarios = $usuarios;

			$this->render('quemSeguir');
		}

		public function validarAutenticacao(){
			session_start();
			if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
				header('Location: /?login=erro');
			}
		}

	}
?>