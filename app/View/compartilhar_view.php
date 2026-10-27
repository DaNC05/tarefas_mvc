<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartilhar</title>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .modal-content input[type="text"],
        .modal-content input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-top: 5px;
        }

        .modal-content input[type="submit"] {
            width: 100%;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="modal-overlay">
        <div class="modal-content">
            <h2>Compartilhar Tarefa</h2>
            <form action="index.php?rota=processar_compartilhamento" method="post">
                <input type="hidden" name="id_tarefa" value="<?= $id_tarefa ?>">
                <label for="email_convidado">Nome: </label>
                <input type="email" name="email_convidado" id="email_convidado">
                <label for="editar">Editar</label>
                <input type="radio" name="permissao" id="editar" value="1" required>
                <label for="visualizar">Visualizar</label>
                <input type="radio" name="permissao" id="visualizar" value="0" required>
                <input type="submit" value="Enviar">
            </form>
            <div>
                <a href="index.php?rota=tarefas">Voltar</a>
            </div>
        </div>
    </div>
</body>

</html>