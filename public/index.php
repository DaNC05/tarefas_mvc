<?php
session_start();
define('BASE_URL', 'http://localhost/tarefas_mvc/public/');
require_once '../app/controller/LoginController.php';
require_once '../app/controller/CadastroController.php';
require_once '../app/Controller/TarefaController.php';
$controller = new TarefaController;
$id = $_GET['id'] ?? null;
$rota = $_GET['rota'] ?? 'login';

$rotas_publicas = [
    'login',
    'autenticar',
    'cadastro',
    'salvar_cadastro'
];
$rotas_tarefas = [
    'tarefas' => 'listarTarefas',
    'hoje' => 'tarefasDeHoje',
    'favoritas' => 'tarefasFavoritas',
    'concluidas' => 'tarefasConcluidas',
    'concluir_tarefa' => 'concluirTarefa',
    'lista' => 'mostrarLista',
    'compartilhar_tarefa' => 'telaCompartilhar',
    'processar_compartilhamento' => 'processarCompartilhamento',
    'criar_subtarefa' => 'adicionarSubtarefa',
    'concluir_subtarefa' => 'concluirSubtarefa',
    'criar_tarefa' => 'enviarTarefas',
    'criar_lista' => 'criarLista',
    'deletar_lista' => 'deletarLista',
    'editar_lista' => 'editarLista',
    'atualizar_lista' => 'atualizarLista',
    'atualizar_descricao' => 'atualizarDescricao',
    'atualizar_titulo' => 'atualizarTitulo',
    'atualizar_data' => 'atualizarData',
    'deletar' => 'deletar',
    'compartilhadas' => 'tarefasCompartilhadas',
    'favoritar' => 'favoritar',
    'favoritar_ajax' => 'favoritarAjax'
];

if (!in_array($rota, $rotas_publicas) && !isset($_SESSION['usuario_logado'])) {
    header('Location: index.php?rota=login');
    exit;
}

if (in_array($rota, $rotas_publicas) || $rota === 'logout') {
    switch ($rota) {
        case 'login':
            $mostrarLogin = new LoginController;
            $mostrarLogin->mostrarPaginaLogin();
            break;
        case 'autenticar':
            $autenticar = new LoginController;
            $autenticar->autenticarUsuario();
            break;
        case 'cadastro':
            $mostrarCadastro = new CadastroController;
            $mostrarCadastro->mostrarPaginaCadastro();
            break;
        case 'salvar_cadastro':
            $salvar_cadastro = new CadastroController;
            $salvar_cadastro->cadatrarUsuario();
            break;
        case 'logout':
            session_destroy();
            header('Location: index.php?rota=login');
            break;
    }
} else if (isset($rotas_tarefas[$rota])) {

    $metodo_dinamico = $rotas_tarefas[$rota];

    $controller->$metodo_dinamico($id);
} else {
    header('Location: index.php?rota=tarefas');
}
