<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <title>Tarefas</title>
</head>

<body>
    <div class="app-container">
        <aside class="sidebar">
            <nav>
                <div class="content-title">
                    <img src="<?= BASE_URL ?>assets/img/logo.png" alt="TaskFlow Logo">
                </div>

                <div class="content-menu">
                    <div class="<?= ($_GET['rota'] ?? '') == 'tarefas' ? 'menu-ativo' : '' ?>">
                        <a href="index.php?rota=tarefas"><span class="material-symbols-outlined">checklist</span><span>Tarefas</span></a>
                    </div>
                    <div class="<?= ($_GET['rota'] ?? '') == 'hoje' ? 'menu-ativo' : '' ?>">
                        <a href="index.php?rota=hoje"><span class="material-symbols-outlined">calendar_today</span><span>Hoje</span></a>
                    </div>
                    <div class="<?= ($_GET['rota'] ?? '') == 'favoritas' ? 'menu-ativo' : '' ?>">
                        <a href="index.php?rota=favoritas"><span class="material-symbols-outlined">star</span><span>Favoritas</span></a>
                    </div>
                    <div class="<?= ($_GET['rota'] ?? '') == 'concluidas' ? 'menu-ativo' : '' ?>">
                        <a href="index.php?rota=concluidas"><span class="material-symbols-outlined">task_alt</span><span>Concluídas</span></a>
                    </div>
                    <div class="<?= ($_GET['rota'] ?? '') == 'compartilhadas' ? 'menu-ativo' : '' ?>">
                        <a href="index.php?rota=compartilhadas">
                            <span class="material-symbols-outlined">folder_shared</span> Compartilhadas
                        </a>
                    </div>
                </div>

                <div class="content-list">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px;">
                        <span style="font-weight: 600; font-size: 14px; color: #64748b;">Listas</span>
                    </div>

                    <form action="index.php?rota=criar_lista" method="POST" style="display: flex; gap: 8px; margin-bottom: 15px; padding-right: 15px; align-items: center;">
                        <input type="color" name="cor" value="#2563eb" style="width: 28px; height: 28px; border: none; padding: 0; cursor: pointer; border-radius: 4px; flex-shrink: 0;">

                        <input type="text" name="nome" placeholder="+ Nova lista (Enter)" required
                            style="flex: 1; padding: 8px 10px; border: 1px dashed #cbd5e1; border-radius: 6px; background: transparent; outline: none; font-size: 13px; color: #334155;">

                        <button type="submit" style="display: none;"></button>
                    </form>

                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px; padding-right: 15px; overflow-y: auto; max-height: 25vh;">
                        <?php foreach (($listas_criadas ?? []) as $lista): ?>
                            <li class="<?= isset($_GET['id']) && $lista['id'] == $_GET['id'] ? 'ativa' : ''; ?> sidebar-list-item"
                                style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border-radius: 6px; transition: background 0.2s;">

                                <div style="display: flex; align-items: center; gap: 10px; flex: 1; overflow: hidden;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: <?= $lista['cor'] ?>; flex-shrink: 0;"></span>

                                    <a href="index.php?rota=lista&id=<?= $lista['id'] ?>" style="color: #334155; text-decoration: none; font-size: 13px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?= htmlspecialchars($lista['nome'] ?? '') ?>
                                    </a>

                                    <span style="font-size: 11px; background: #e2e8f0; padding: 2px 6px; border-radius: 10px; color: #475569;">
                                        <?= $lista['contagem'] ?? '0' ?>
                                    </span>
                                </div>

                                <div class="sidebar-list-actions" style="display: flex; align-items: center; gap: 8px; flex-shrink: 0; margin-left: 10px;">
                                    <a href="index.php?rota=editar_lista&id=<?= $lista['id'] ?>" title="Editar" style="color: #94a3b8; text-decoration: none;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                    </a>
                                    <a href="index.php?rota=deletar_lista&id=<?= $lista['id'] ?>" title="Deletar" style="color: #94a3b8; text-decoration: none;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="content-profile">
                    <span>Daniel</span>
                    <span style="font-size: 12px; color: #64748b;"><?php echo $_SESSION['usuario_logado']; ?></span>
                    <a href="index.php?rota=logout" style="color: #ef4444; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 4px; margin-top: 5px;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">logout</span> Sair
                    </a>
                </div>
            </nav>
        </aside>
        <section class="content-principal">
            <header>
                <h1><?= htmlspecialchars($titulo_pagina) ?></h1>
            </header>
            <main>
                <div>
                    <form action="index.php?rota=criar_tarefa" method="post" enctype="multipart/form-data" class="content-form-tasks">
                        <input type="hidden" name="lista_id" value="<?= $_GET['id'] ?? 1 ?>">
                        <input type="text" name="titulo" placeholder="Adicione a sua tarefa aqui..." style="flex-grow: 1;" required>
                        <input type="submit" value="+">
                    </form>
                </div>

                <div>
                    <div>
                        <ul>
                            <?php foreach ($tarefas_pendentes as $tarefa): ?>
                                <?php $is_concluida = in_array($tarefa['status'], ['concluida', 'concluido']); ?>
                                <li class="task-item">
                                    <div class="task-info">
                                        <input type="checkbox" <?= $is_concluida ? 'checked' : '' ?> name="tarefa_id" value="<?= $tarefa['id'] ?>" data-lista="<?= $_GET['id'] ?? 1 ?>" class="check-tarefa" style="margin-top: 10px;">
                                        <a href="index.php?rota=<?= isset($_GET['id']) ? "lista&id=" . $_GET['id'] ?? 1 : ($_GET['rota'] ?? 'tarefas') ?>&tarefa_id=<?= $tarefa['id'] ?>" class="<?= $is_concluida ? 'riscado' : '' ?>" style="color: inherit; text-decoration: none;">
                                            <?php if (!empty($tarefa['is_compartilhada'])): ?>
                                                <span class="material-symbols-outlined" title="Tarefa Compartilhada">group</span>
                                            <?php endif; ?>
                                            <h3><?= htmlspecialchars($tarefa['titulo']) ?></h3>
                                        </a>
                                        <span><strong><?= $tarefa['data_hora'] ?></strong></span>
                                    </div>
                                    <div class="task-actions">
                                        <button type="button" class="btn-favoritar" value="<?= $tarefa['id'] ?>" style="background: none; border: none; cursor: pointer; font-size: 1.2rem; color: #f59e0b;"> <?= $tarefa['favorita'] == 1 ? '★' : '☆' ?> </button>
                                        <span class="material-symbols-outlined">more_vert</span>
                                        <div class="dropdown-menu">
                                            <a href="index.php?rota=deletar&id=<?= $tarefa['id'] ?>" class="dropdown-item">
                                                <span class="material-symbols-outlined">delete</span>
                                                Deletar </a>
                                            <a href="index.php?rota=compartilhar_tarefa&id=<?= $tarefa['id'] ?>" class="dropdown-item">
                                                <span class="material-symbols-outlined">share</span>
                                                Compartilhar </a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

            </main>
        </section>
        <?php if (!empty($tarefa_selecionada)): ?>
            <aside class="task-details-panel">
                <div class="panel-header">
                    <h2>Detalhes da tarefa</h2>
                    <a href="index.php?rota=<?= isset($_GET['id']) ? 'lista&id=' . $_GET['id'] : ($_GET['rota'] ?? 'tarefas') ?>" class="btn-close-panel">
                        <span class="material-symbols-outlined">close</span>
                    </a>
                </div>

                <div class="panel-body">
                    <?php
                    $bloqueio_texto = (isset($pode_editar) && $pode_editar === false) ? 'readonly' : '';
                    $bloqueio_total = (isset($pode_editar) && $pode_editar === false) ? 'disabled' : '';
                    ?>
                    <div class="panel-title-group">
                        <span class="material-symbols-outlined check-icon">check_circle</span>
                        <input type="text" class="input-titulo" <?= $bloqueio_texto ?> placeholder="<?= htmlspecialchars($tarefa_selecionada['titulo']) ?>" data-id="<?= $tarefa_selecionada['id'] ?>" rows="4" placeholder="Adicione um titulo..." value="<?= $tarefa_selecionada['titulo'] ?? '' ?>">
                    </div>

                    <div class="panel-section">
                        <label><span class="material-symbols-outlined">calendar_today</span> Prazo</label>
                        <input type="date" class="input-data" <?= $bloqueio_total ?> data-id="<?= $tarefa_selecionada['id'] ?>" rows="4" placeholder="<?= !empty($tarefa_selecionada['data_vencimento']) ? $tarefa_selecionada['data_vencimento'] : 'Sem data definida' ?> Adicione uma data..." value="<?= $tarefa_selecionada['data_vencimento'] ?? '' ?>">
                    </div>

                    <div class="panel-section">
                        <label><span class="material-symbols-outlined">subject</span> Descrição</label>
                        <textarea class="input-descricao" <?= $bloqueio_texto ?> data-id="<?= $tarefa_selecionada['id'] ?>" rows="4" placeholder="Adicione uma descrição..."><?= $tarefa_selecionada['descricao'] ?? '' ?></textarea>
                    </div>

                    <div class="panel-section">
                        <label><span class="material-symbols-outlined">checklist</span> Checklist</label>
                        <ul class="panel-subtasks">
                            <?php if (!empty($tarefa_selecionada['subtarefas'])): ?>
                                <?php foreach ($tarefa_selecionada['subtarefas'] as $sub): ?>
                                    <li>
                                        <input type="checkbox" <?= ($sub['status']  ?? 'pendente') == 'concluida'  ? 'checked' : '' ?> class="check-subtarefa" <?= $bloqueio_total ?> id="<?= $sub['id'] ?>">
                                        <span><?= htmlspecialchars($sub['descricao']) ?></span>
                                    </li>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <li class="empty-subtasks">Nenhuma subtarefa cadastrada.</li>
                            <?php endif; ?>
                        </ul>
                        <?php if (!isset($pode_editar) || $pode_editar === true): ?>
                            <form method="POST" action="index.php?rota=criar_subtarefa">
                                <input type="hidden" name="id_tarefa_selecionada" value="<?= $tarefa_selecionada['id'] ?>">
                                <input type="hidden" name="id_lista" value="<?= $_GET['id'] ?? '' ?>">
                                <input type="text" name="descricao_subtarefa">
                                <input type="submit" value="Adicionar">
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if (!isset($pode_editar) || $pode_editar === true): ?>
                        <div class="panel-footer">
                            <a href="index.php?rota=deletar&id=<?= $tarefa_selecionada['id'] ?>" class="btn-delete-task">
                                <span class="material-symbols-outlined">delete</span> Excluir tarefa
                            </a>
                            <a href="index.php?rota=compartilhar_tarefa&id=<?= $tarefa_selecionada['id'] ?>" class="btn-share-task">
                                <span class="material-symbols-outlined">share</span> Compartilhar tarefa
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        <?php endif; ?>
    </div>
    <script src="script.js"></script>
</body>

</html>