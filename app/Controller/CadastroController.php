<?php
require_once '../app/Model/Usuario.php';
class CadastroController
{
    public function mostrarPaginaCadastro()
    {
        require '../app/View/cadastro_view.php';
    }
    public function cadatrarUsuario()
    {
        require '../app/Model/Usuario.php';

        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
            if (empty($_POST['email']) && empty($_POST['password']) && empty($_POST['confirmPassword'])) {
                $dadosErr = "Preencha os dados corretamente.";
            } elseif (empty($_POST['password'])) {
                $passwordErr = "A senha é obrigatório.";
            } elseif (empty($_POST['confirmPassword'])) {
                $confirmPasswordErr = "Confirme sua senha.";
            } elseif ($_POST['password'] != $_POST['confirmPassword']) {
                $textErr = "As senhas não coincidem.";
            } elseif (empty($_POST['email'])) {
                $emailErr = "E-mail é obrigatório.";
            } else {
                $cadastroUsuario = new Usuario;
                $email = htmlspecialchars($_POST['email']);
                $password = htmlspecialchars($_POST['password']);
                $salvar_cadastro = $cadastroUsuario->cadastrarUsuario($email, $password);
                if ($salvar_cadastro) {
                    $_SESSION['usuario_logado'] = $email;
                    header('Location: index.php?rota=tarefas');
                    exit;
                } else {
                    echo 'cadastro não efetuado';
                }
            }
        }
    }
}
