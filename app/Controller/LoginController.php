<?php
require_once '../app/Model/Usuario.php';
class LoginController
{
    public function mostrarPaginaLogin()
    {
        require '../app/View/login_view.php';
    }
    public function autenticarUsuario()
    {
        require_once '../app/Model/Usuario.php';

        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (empty($_POST['email']) && empty($_POST['password'])) {
                $dadosErr = "Preencha os dados corretamente.";
            } elseif (empty($_POST['password'])) {
                $passwordErr = "A senha é obrigatório.";
            } elseif (empty($_POST['email'])) {
                $emailErr = "E-mail é obrigatório.";
            } else {
                $loginUsuario = new Usuario;
                $email = htmlspecialchars($_POST['email']);
                $password = htmlspecialchars($_POST['password']);
                $autenticado = $loginUsuario->verificarCredenciais($email, $password);
                if ($autenticado) {
                    $_SESSION['usuario_logado'] = $email;
                    $_SESSION['usuario_id'] = $autenticado['id'];
                    header('Location: index.php?rota=tarefas');
                    exit;
                } else {
                    echo 'login não efetuado';
                }
            }
        } else {
            echo ('Login ou senha incorreta');
        }
    }
}
