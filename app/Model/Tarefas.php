<?php

require_once '../app/config/Conexao.php';

class Tarefa
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConexao();
    }
    public function criarTarefas($criador_id, $titulo, $descricao,  $data, $hora, $lista_id, $caminho_anexo)
    {
        $stmt = $this->pdo->prepare("INSERT INTO tarefas (criador_id, titulo,descricao, data_vencimento, hora_vencimento, lista_id,caminho_anexo ,status) VALUES (:criador_id, :titulo,:descricao , :data , :hora,:lista_id,:caminho_anexo ,:status)");
        $status = 'pendente';
        $stmt->execute(['criador_id' => $criador_id, 'titulo' => $titulo, 'descricao' => $descricao, 'data' => $data, 'hora' => $hora, 'lista_id' => $lista_id, 'caminho_anexo' => $caminho_anexo, 'status' => $status]);
        return $this->pdo->lastInsertId();
    }

    public function criarSubtarefa($tarefa_id, $descricao_item)
    {
        $stmt = $this->pdo->prepare("INSERT INTO subtarefas(tarefa_id, descricao) VALUES(:tarefa_id, :descricao)");
        $stmt->execute(['tarefa_id' => $tarefa_id, 'descricao' => $descricao_item]);
    }
    public function buscarSubtarefa($tarefa_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM subtarefas WHERE tarefa_id = :tarefa_id");
        $stmt->execute(['tarefa_id' => $tarefa_id]);
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function atualizarStatusSubtarefa($id_tarefa, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE subtarefas SET status = :status, data_conclusao = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id_tarefa, 'status' => $status]);
    }
    public function buscarListasDoUsuario($criador_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM listas WHERE criador_id = :criador_id");
        $stmt->execute(['criador_id' => $criador_id]);
        return  $stmt->fetchAll();
    }
    public function buscarTarefasDaLista($lista_id, $status)
    {
        $stmt = $this->pdo->prepare("SELECT *, EXISTS(SELECT 1 FROM tarefas_compartilhadas WHERE tarefas_compartilhadas.tarefa_id = tarefas.id) AS is_compartilhada FROM tarefas WHERE lista_id = :lista_id AND status = :status");
        $stmt->execute(['lista_id' => $lista_id, 'status' => $status]);
        return $stmt->fetchAll();
    }
    public function buscarListaPorId($id_lista)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM listas WHERE id = :id ");
        $stmt->execute(['id' => $id_lista]);
        return $stmt->fetch();
    }
    public function criarLista($criador_id, $nome, $cor)
    {
        $stmt = $this->pdo->prepare("INSERT INTO listas (nome,criador_id,cor) VALUES (:nome,:criador_id,:cor)");
        $stmt->execute(['criador_id' => $criador_id, 'nome' => $nome, 'cor' => $cor]);
    }
    public function deletarLista($lista_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM listas WHERE id = :id");
        $stmt->execute(['id' => $lista_id]);
    }
    public function renomearLista($lista_id, $novo_nome)
    {
        $stmt = $this->pdo->prepare("UPDATE listas SET nome = :nome WHERE id = :id");
        $stmt->execute(['id' => $lista_id, 'nome' => $novo_nome]);
    }

    public function atualizarDescricao($id_tarefa, $nova_descricao)
    {
        $stmt = $this->pdo->prepare("UPDATE tarefas SET descricao = :descricao WHERE id= :id");
        $stmt->execute(['id' => $id_tarefa, 'descricao' => $nova_descricao]);
    }

    public function atualizarTitulo($id_tarefa, $novo_titulo)
    {
        $stmt = $this->pdo->prepare("UPDATE tarefas SET titulo = :titulo WHERE id= :id");
        $stmt->execute(['id' => $id_tarefa, 'titulo' => $novo_titulo]);
    }


    public function atualizarData($id_tarefa, $nova_data)
    {
        $stmt = $this->pdo->prepare("UPDATE tarefas SET data_vencimento = :data_vencimento WHERE id= :id");
        $stmt->execute(['id' => $id_tarefa, 'data_vencimento' => $nova_data]);
    }

    public function mostrarTarefas($criador_id)
    {
        $stmt = $this->pdo->prepare("SELECT *, EXISTS(SELECT 1 FROM tarefas_compartilhadas WHERE tarefas_compartilhadas.tarefa_id = tarefas.id) AS is_compartilhada FROM tarefas WHERE criador_id = :criador_id");
        $stmt->execute(['criador_id' => $criador_id]);
        return $stmt->fetchAll();
    }
    public function buscarTarefaPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tarefas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function buscarTarefasDeHoje($criador_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tarefas WHERE criador_id = :criador_id AND data_vencimento = CURDATE()");
        $stmt->execute(['criador_id' => $criador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarTarefasFavoritas($criador_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tarefas WHERE criador_id = :criador_id AND favorita = 1");
        $stmt->execute(['criador_id' => $criador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarTarefasConcluidas($criador_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tarefas WHERE criador_id = :criador_id AND status = 'concluida'");
        $stmt->execute(['criador_id' => $criador_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function atualizarStatus($id_tarefa, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE tarefas SET status = :status, data_conclusao = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id_tarefa, 'status' => $status]);
    }
    public function deletarTarefa($id_tarefa)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tarefas_compartilhadas WHERE tarefa_id = :id ");
        $stmt->execute(['id' => $id_tarefa]);

        $stmt = $this->pdo->prepare("DELETE FROM subtarefas WHERE tarefa_id = :id ");
        $stmt->execute(['id' => $id_tarefa]);

        $stmt = $this->pdo->prepare("DELETE FROM tarefas WHERE id = :id ");
        $stmt->execute(['id' => $id_tarefa]);
    }
    public function contarTarefasPendentes($lista_id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(id) FROM tarefas WHERE lista_id = :lista_id AND status = :status ");
        $stmt->execute(['lista_id' => $lista_id, 'status' => 'pendente']);
        return $stmt->fetchColumn();
    }
    public function favoritarTarefas($id_tarefa)
    {
        $stmt = $this->pdo->prepare("UPDATE tarefas SET favorita = NOT favorita WHERE id = :id");
        $stmt->execute(['id' => $id_tarefa]);
    }
    public function compartilharTarefa($tarefa_id, $usuario_convidado_id, $permissao)
    {
        $stmt = $this->pdo->prepare("INSERT INTO tarefas_compartilhadas(tarefa_id, usuario_convidado_id, permissao_editar) VALUES (:tarefa_id, :usuario_convidado_id, :permissao)");
        $stmt->execute(['tarefa_id' => $tarefa_id, 'usuario_convidado_id' => $usuario_convidado_id, 'permissao' => $permissao]);
    }
    public function verificarPermissaoCompartilhamento($tarefa_id, $usuario_id)
    {
        $stmt = $this->pdo->prepare("SELECT permissao_editar from tarefas_compartilhadas WHERE usuario_convidado_id = :usuario_id AND tarefa_id = :tarefa_id");
        $stmt->execute(['tarefa_id' => $tarefa_id, 'usuario_id' => $usuario_id]);
        return $stmt->fetchColumn();
    }
    public function mostrarTarefasCompartilhadas($id_usuario)
    {
        $stmt = $this->pdo->prepare("SELECT tarefas.titulo FROM tarefas JOIN tarefas_usuarios ON tarefas.id = tarefas_usuarios.id_tarefa WHERE id_usuario = :id_usuario");
        $stmt->execute(['id_usuario' => $id_usuario]);
        return $stmt->fetchAll();
    }
    public function buscarTarefasCompatilhadasComigo($id_usuario)
    {
        $stmt = $this->pdo->prepare("SELECT tarefas.* FROM tarefas_compartilhadas INNER JOIN tarefas ON tarefas.id = tarefas_compartilhadas.tarefa_id   WHERE tarefas_compartilhadas.usuario_convidado_id = :usuario_convidado_id");
        $stmt->execute(['usuario_convidado_id' => $id_usuario]);
        return $stmt->fetchAll();
    }
}
