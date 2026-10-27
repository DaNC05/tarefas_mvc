<?php
require_once '../app/Model/Tarefas.php';
require_once '../app/Model/Usuario.php';

class TarefaController
{

    public function __construct()
    {
        throw new \Exception('Not implemented');
    }

    public function listarTarefas()
    {
        $filtro = $_GET['filtro'] ?? null;
        $id = $_SESSION['usuario_id'];
        $tarefas = new Tarefa;
        $tarefas_criadas = $tarefas->mostrarTarefas($id, $filtro);
        $listas_criadas = $tarefas->buscarListasDoUsuario($id);
        if ($listas_criadas && !$filtro) {
            header('Location: index.php?rota=lista&id=' . $listas_criadas[0]['id']);
            exit;
        }
        $titulo_pagina = "Tarefas";

        require '../app/View/tarefas_view.php';
    }
    public function tarefasDeHoje()
    {
        $id = $_SESSION['usuario_id'];
        $tarefas = new Tarefa;
        $listas_criadas = $tarefas->buscarListasDoUsuario($id);
        $tarefas_concluidas = [];
        $tarefas_pendentes =  $tarefas->buscarTarefasDeHoje($id);
        $tarefas_compartilhadas = $tarefas->buscarTarefasCompatilhadasComigo($id);
        foreach ($tarefas_compartilhadas as &$tarefa) {
            $tarefa['subtarefas'] = $tarefas->buscarSubtarefa($tarefa['id']);
        }
        foreach ($tarefas_pendentes as &$tarefa) {
            $tarefa['subtarefas'] = $tarefas->buscarSubtarefa($tarefa['id']);
        }
        unset($tarefa);
        $titulo_pagina = "Hoje";
        require '../app/View/tarefas_view.php';
    }
    public function tarefasConcluidas()
    {
        $id = $_SESSION['usuario_id'];
        $tarefas = new Tarefa;
        $listas_criadas = $tarefas->buscarListasDoUsuario($id);
        $tarefas_pendentes =  $tarefas->buscarTarefasConcluidas($id);
        $tarefas_compartilhadas = $tarefas->buscarTarefasCompatilhadasComigo($id);
        foreach ($tarefas_compartilhadas as &$tarefa) {
            $tarefa['subtarefas'] = $tarefas->buscarSubtarefa($tarefa['id']);
        }
        foreach ($tarefas_pendentes as &$tarefa) {
            $tarefa['subtarefas'] = $tarefas->buscarSubtarefa($tarefa['id']);
        }
        unset($tarefa);

        $titulo_pagina = "Concluídas";
        require '../app/View/tarefas_view.php';
    }
    public function tarefasFavoritas()
    {
        $id = $_SESSION['usuario_id'];
        $tarefas = new Tarefa;
        $listas_criadas = $tarefas->buscarListasDoUsuario($id);
        $tarefas_pendentes =  $tarefas->buscarTarefasFavoritas($id);
        $tarefas_compartilhadas = $tarefas->buscarTarefasCompatilhadasComigo($id);
        foreach ($tarefas_compartilhadas as &$tarefa) {
            $tarefa['subtarefas'] = $tarefas->buscarSubtarefa($tarefa['id']);
        }
        foreach ($tarefas_pendentes as &$tarefa) {
            $tarefa['subtarefas'] = $tarefas->buscarSubtarefa($tarefa['id']);
        }
        unset($tarefa);

        $titulo_pagina = "Favoritas";
        require '../app/View/tarefas_view.php';
    }
    public function enviarTarefas()
    {
        if (isset($_POST['titulo'])) {
            $titulo = htmlspecialchars($_POST['titulo']);
            $descricao = htmlspecialchars($_POST['descricao'] ?? '');
            $data = htmlspecialchars($_POST['data'] ?? '');
            $hora = htmlspecialchars($_POST['hora'] ?? '');
            $nome_arquivo = '';
            if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
                $nome_arquivo = htmlspecialchars($_FILES['arquivo']['name']);
                $arquivo_temp = $_FILES['arquivo']['tmp_name'];
                move_uploaded_file($arquivo_temp, '../public/uploads/' . $nome_arquivo);
            }
            $lista_id = htmlspecialchars($_POST['lista_id']);
            $id = $_SESSION['usuario_id'];
            $tarefa = new Tarefa;
            $id_nova_tarefa = $tarefa->criarTarefas($id, $titulo, $descricao, $data, $hora, $lista_id, $nome_arquivo);
            if (!empty($_POST['checklist'])) {
                foreach ($_POST['checklist'] as $item) {
                    if (!empty($item)) {
                        $tarefa->criarSubtarefa($id_nova_tarefa, htmlspecialchars($item));
                    }
                }
            }
            if ($lista_id != 1) {
                header("Location: index.php?rota=lista&id=$lista_id");
            } else {
                header('Location: index.php?rota=tarefas');
            }
        }
    }
    public function mostrarLista()
    {
        $id_usuario = $_SESSION['usuario_id'];
        $id_lista = $_GET['id'];
        $lista_tarefas = new Tarefa;
        $tarefas_pendentes = $lista_tarefas->buscarTarefasDaLista($id_lista, 'pendente');
        $tarefas_concluidas = $lista_tarefas->buscarTarefasDaLista($id_lista, 'concluida');
        $tarefas_compartilhadas = [];
        $tarefas_compartilhadas = $lista_tarefas->buscarTarefasCompatilhadasComigo($id_lista);
        $listas_criadas = $lista_tarefas->buscarListasDoUsuario($id_usuario);

        foreach ($tarefas_pendentes as &$tarefa) {
            $tarefa['subtarefas'] = $lista_tarefas->buscarSubtarefa($tarefa['id']);
        }

        unset($tarefa);
        $titulo_pagina = "Lista";

        foreach ($listas_criadas as &$lista) {
            $lista['contagem'] = $lista_tarefas->contarTarefasPendentes($lista['id']);

            if ($lista['id'] == $id_lista) {
                $titulo_pagina = $lista['nome'];
            }
        }
        unset($lista);
        $tarefa_selecionada = null;
        if (isset($_GET['tarefa_id'])) {
            $id_tarefa_clicada = $_GET['tarefa_id'];
            $tarefa_selecionada = $lista_tarefas->buscarTarefaPorId($id_tarefa_clicada);

            if ($tarefa_selecionada) {
                $tarefa_selecionada['subtarefas'] = $lista_tarefas->buscarSubtarefa($id_tarefa_clicada);
            }
        }
        require '../app/View/tarefas_view.php';
    }
    public function criarLista()
    {
        if (isset($_POST['nome'])) {
            $nome = htmlspecialchars($_POST['nome']);
            $cor = htmlspecialchars($_POST['cor']);
            $id_usuario = $_SESSION['usuario_id'];
            $lista = new Tarefa;
            $lista_criada = $lista->criarLista($id_usuario, $nome, $cor);
            header('Location: index.php?rota=tarefas');
        }
    }
    public function deletarLista()
    {

        $id_lista = $_GET['id'];
        $lista = new Tarefa;
        $lista_deletada = $lista->deletarLista($id_lista);
        header('Location: index.php?rota=tarefas');
        exit;
    }
    public function editarLista()
    {
        $id_lista = $_GET['id'];
        $lista = new Tarefa;
        $lista_atual = $lista->buscarListaPorId($id_lista);
        require '../app/View/editar_lista_view.php';
    }
    public function atualizarLista()
    {
        $id_lista = htmlspecialchars($_POST['id']);
        $novo_nome = htmlspecialchars($_POST['novo_nome']);
        $lista = new Tarefa;
        $lista->renomearLista($id_lista, $novo_nome);
        header('Location: index.php?rota=tarefas');
        exit;
    }
    public function deletar($id_tarefa)
    {
        $tarefa = new Tarefa;
        $tarefa_deletada = $tarefa->deletarTarefa($id_tarefa);
        header('Location: index.php?rota=tarefas');
    }
    public function concluirTarefa()
    {
        $id_tarefa = $_POST['id'];
        $status = $_POST['status'];
        $lista_id = $_POST['id_lista'] ?? 1;
        $tarefa = new Tarefa;
        $tarefa_concluida = $tarefa->atualizarStatus($id_tarefa, $status);

        echo "CONCLUIDO!";
    }
    public function atualizarDescricao()
    {
        $id_tarefa = $_POST['id'];
        $nova_descricao = $_POST['descricao'];
        $tarefa = new Tarefa;
        $atualizar_tarefa = $tarefa->atualizarDescricao($id_tarefa, $nova_descricao);
        echo "Atualizado";
    }

    public function atualizarTitulo()
    {
        $id_tarefa = $_POST['id'];
        $nova_titulo = $_POST['titulo'];
        $tarefa = new Tarefa;
        $atualizar_tarefa = $tarefa->atualizarTitulo($id_tarefa, $nova_titulo);
        echo "Atualizado";
    }

    public function atualizarData()
    {
        $id_tarefa = $_POST['id'];
        $nova_data = $_POST['data'];
        $tarefa = new Tarefa;
        $atualizar_tarefa = $tarefa->atualizarData($id_tarefa, $nova_data);
        echo "Atualizado";
    }


    public function adicionarSubtarefa()
    {
        $subtarefa = new Tarefa;
        $id_tarefa_selecionada = $_POST['id_tarefa_selecionada'];
        $descricao_subtarefa = $_POST['descricao_subtarefa'];
        $id_lista = $_POST['id_lista'] ?? '';
        $add_subtarefa = $subtarefa->criarSubtarefa($id_tarefa_selecionada, $descricao_subtarefa);

        if (!empty($id_lista)) {
            header('Location: index.php?rota=lista&id=' . $id_lista . '&tarefa_id=' . $id_tarefa_selecionada);
        } else {
            header('Location: index.php?rota=compartilhadas&tarefa_id=' . $id_tarefa_selecionada);
        }
        exit;
    }
    public function concluirSubtarefa()
    {
        $id_subtarefa = $_POST['id'];
        $subtarefa = new Tarefa;
        $status = $_POST['status'];
        $subtarefa_concluida = $subtarefa->atualizarStatusSubtarefa($id_subtarefa, $status);
        echo "RECEBIDO!";
    }
    public function favoritar($id_tarefa)
    {
        $tarefa = new Tarefa;
        $tarefa_favoritada = $tarefa->favoritarTarefas($id_tarefa);
        header('Location: index.php?rota=tarefas');
    }
    public function favoritarAjax()
    {
        $id = $_POST['id'];
        $tarefa = new Tarefa;
        $tarefa_favoritadaAjax = $tarefa->favoritarTarefas($id);
        echo "Favoritado com sucesso!";
    }

    public function telaCompartilhar($id_tarefa)
    {
        $id = $_SESSION['usuario_id'];
        $tarefas = new Tarefa;
        $tarefas_compartilhadas = $tarefas->mostrarTarefasCompartilhadas($id);
        require '../app/View/compartilhar_view.php';
    }
    public function tarefasCompartilhadas()
    {
        $id = $_SESSION['usuario_id'];
        $tarefas = new Tarefa();
        $tarefa_selecionada = null;
        $pode_editar = false;
        if (isset($_GET['tarefa_id'])) {
            $tarefa_selecionada = $tarefas->buscarTarefaPorId($_GET['tarefa_id']);
            $tarefa_selecionada['subtarefas'] = $tarefas->buscarSubtarefa($_GET['tarefa_id']);
            $permissao = $tarefas->verificarPermissaoCompartilhamento($_GET['tarefa_id'], $id);
            if ($permissao == 1) {
                $pode_editar = true;
            }
        }
        $listas_criadas = $tarefas->buscarListasDoUsuario($id);
        $tarefas_pendentes = $tarefas->buscarTarefasCompatilhadasComigo($id);
        $tarefas_concluidas = [];
        $titulo_pagina = "Compartilhadas Comigo";
        require_once '../app/View/tarefas_view.php';
    }
    public function processarCompartilhamento()
    {
        $email_convidado = htmlspecialchars($_POST['email_convidado']);
        $id_tarefa_compartilhada = htmlspecialchars($_POST['id_tarefa']);
        $permissao = isset($_POST['permissao']) ? (int)$_POST['permissao'] : 0;
        $usuarioModel = new Usuario;
        $tarefaModel = new Tarefa;

        $convidado_encontrado = $usuarioModel->buscarUsuarioPorEmail($email_convidado);
        if ($convidado_encontrado) {
            $id_convidado = $convidado_encontrado['id'];
            $compartilhar_tarefas = $tarefaModel->compartilharTarefa($id_tarefa_compartilhada, $id_convidado, $permissao);
            header('Location: index.php?rota=tarefas&msg=sucesso');
        }
        header('Location: index.php?rota=tarefas');
    }
}
