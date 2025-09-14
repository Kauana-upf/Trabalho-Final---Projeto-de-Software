<?php
require_once "autenticacao.php";

// Autenticação do usuário
require_login();
$user = current_user();

// Singleton / DIP - conexão com banco centralizada
$pdo = ConexaoBD::getInstance()->getConnection();

// SRP - busca apenas lembretes do usuário logado, para histórico
$s = $pdo->prepare("SELECT * FROM reminders WHERE user_id=? ORDER BY date DESC,time DESC");
$s->execute([$user['id']]);
$list = $s->fetchAll();
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Histórico</title>
</head>

<body>
    <div class="main">
        <div class="container">
            <h2>Histórico de Lembretes</h2>

            <!-- SRP / UI/UX - lista de lembretes com status visual -->
            <?php if ($list): ?>
                <?php foreach ($list as $r): ?>
                    <?php
                    $statusClass = '';
                    if ($r['status'] === 'DONE') {
                        $statusClass = 'alert-done';
                    } elseif ($r['status'] === 'PENDING') {
                        $statusClass = 'alert-pending';
                    }
                    ?>
                    <div class="alert <?= $statusClass ?>">
                        <strong><?= htmlspecialchars($r['title']) ?></strong><br>
                        <?= $r['date'] ?> <?= $r['time'] ?> — [<?= $r['status'] ?>]
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- UI/UX - feedback visual quando não há registros -->
                <p>Sem registros ainda.</p>
            <?php endif; ?>

            <!-- UI/UX - botão de navegação -->
            <a class="btn" href="principal.php">Voltar</a>
        </div>
    </div>

    <div class="footer">Kauana Menin — Matrícula 207165 — Projeto de Software — UPF</div>
</body>

</html>