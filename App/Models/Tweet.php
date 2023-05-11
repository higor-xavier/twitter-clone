<?php 

	namespace App\Models;
	use MF\Model\Model;

	class Tweet extends Model{
		private $id;
		private $id_usuario;
		private $tweet;
		private $data;

		public function __get($atributo){
			return $this->$atributo;
		}

		public function __set($atributo, $valor){
			$this->$atributo = $valor;
		}

		//salvar
		public function salvar(){
			$query = "INSERT INTO tweets(id_usuario, tweet) VALUES (:id_usuario, :tweet)";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
			$stmt->bindValue(':tweet', $this->__get('tweet'));
			$stmt->execute();

			return $this;
		}

		//recuperar
		public function getAll(){

			$query = " 
						SELECT t.id, t.id_usuario, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') AS data, u.nome 
						FROM tweets AS t
						LEFT JOIN usuarios AS u
						ON t.id_usuario = u.id
						WHERE id_usuario = :id_usuario
						ORDER BY t.data DESC 
					 ";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
			$stmt->execute();

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

		//validar
		public function validarCadastro(){
			$valido = true;

			if (strlen($this->__get('nome')) < 3){
				$valido = false;
			}

			if (strlen($this->__get('email')) < 3){
				$valido = false;
			}

			if (strlen($this->__get('senha')) < 3){
				$valido = false;
			}


			return $valido;
		}

		//autenticação do usuário
		public function autenticar(){
			$query = "
						SELECT id, nome, email
						FROM usuarios
						WHERE email = :email AND senha = :senha
					 ";
			$stmt = $this->db->prepare($query);
			$stmt->bindValue('email', $this->__get('email'));
			$stmt->bindValue('senha', $this->__get('senha'));
			$stmt->execute();

			$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

			if($usuario['id'] != '' && $usuario['nome'] != '') {
				$this->__set('id', $usuario['id']);
				$this->__set('nome', $usuario['nome']);
			}
			return $this;
		}
	}

 ?>