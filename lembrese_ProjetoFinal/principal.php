<?php
require_once "conexao.php";
require_once "autenticacao.php";

// Autenticação do usuário
require_login();
$user = current_user();

// Singleton / DIP
$pdo = ConexaoBD::getInstance()->getConnection();

// SRP - Listar apenas lembretes do usuário logado
$stm = $pdo->prepare("SELECT * FROM reminders WHERE user_id=? ORDER BY date, time");
$stm->execute([$user['id']]);
$lembretes = $stm->fetchAll(PDO::FETCH_ASSOC);

$msg = $_GET['msg'] ?? "";
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Painel</title>

    <!-- UI/UX - estilo atualizado com cache-busting -->
    <link rel="stylesheet" href="css/style.css?v=<?= file_exists(__DIR__ . '/css/style.css') ? filemtime(__DIR__ . '/css/style.css') : time() ?>">
</head>

<body>
    <div class="main">
        <div class="container">
            <h2>Bem-vindo, <?= htmlspecialchars($user['nome']) ?></h2>

            <!-- UI/UX - feedback visual -->
            <?php if ($msg): ?>
                <div class="msg-success"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <!-- UI/UX - botões principais -->
            <a class="btn menu-btn" href="novo_lembrete.php">+ Novo lembrete</a>
            <a class="btn menu-btn" href="historico.php">Histórico</a>
            <a class="btn menu-btn" href="logout.php">Sair</a>

            <!-- SRP - lista de lembretes -->
            <div class="lembretes-lista">
                <?php foreach ($lembretes as $r):
                    $statusClass = ($r['status'] === 'DONE') ? 'alert-done' : 'alert-pending';
                    $statusText  = ($r['status'] === 'DONE') ? 'Concluído' : 'Pendente';
                ?>
                    <div class="reminder-card <?= $statusClass ?>">
                        <!-- UI/UX - informações do lembrete -->
                        <div class="reminder-top">
                            <div class="reminder-title"><?= htmlspecialchars($r['title']) ?></div>
                            <div class="reminder-meta">
                                <?= $r['date'] ? date("d/m/Y", strtotime($r['date'])) . " · " : "" ?>
                                <?= substr($r['time'], 0, 5) ?>
                                <span class="status-text"> · <?= $statusText ?></span>
                            </div>
                        </div>

                        <!-- UI/UX - ações do lembrete -->
                        <div class="actions">
                            <a href="editar_lembrete.php?id=<?= $r['id'] ?>" class="btn-small edit-btn">Editar</a>
                            <a href="concluir_lembrete.php?id=<?= $r['id'] ?>" class="btn-small done-btn">Já fiz</a>
                            <a href="delete_lembrete.php?id=<?= $r['id'] ?>" class="btn-small delete-btn">Excluir</a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$lembretes): ?>
                    <p>Nenhum lembrete cadastrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer">Kauana Menin — Matrícula 207165 — Projeto de Software — UPF</div>
</body>

</html>