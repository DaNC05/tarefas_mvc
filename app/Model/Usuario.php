<?php
require_once '../app/config/Conexao.php';

class Usuario
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }
    public function cadastrarUsuario($email, $password)
    {
        $senha_criptografada = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (email,senha) VALUES (:email, :senha)");
        $stmt->execute(['email' => $email, 'senha' => $senha_criptografada]);
        $id_novo_usuario = $this->pdo->lastInsertId();
        $stmt_lista = $this->pdo->prepare("INSERT INTO listas (nome, criador_id) VALUES (:nome, :criador_id)");
        $stmt_lista->execute(['nome' => 'Minhas tarefas', 'criador_id' => $id_novo_usuario]);
        return true;
    }
    public function verificarCredenciais($email, $senha)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        if ($stmt->rowCount() > 0 && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }
    public function buscarUsuarioPorEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
