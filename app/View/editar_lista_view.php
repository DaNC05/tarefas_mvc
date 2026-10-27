<head>
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
<div class="modal-overlay">
    <div class="modal-content">
        <h2>Editar Lista</h2>

        <form action="index.php?rota=atualizar_lista" method="post">
            <input type="hidden" name="id" value="<?= $lista_atual['id'] ?>">
            <input type="text" name="novo_nome" value="<?= $lista_atual['nome'] ?>">
            <input type="submit" value="Enviar">
        </form>

    </div>
</div>