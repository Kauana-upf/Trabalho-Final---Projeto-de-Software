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
    <style>
        /* Ajuste para não sobrepor o botão */
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #1a7256;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            background: #145b44;
        }

        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
        }

        .alert-done {
            background: #d4edda;
            color: #155724;
        }

        .alert-pending {
            background: #fff3cd;
            color: #856404;
        }

        .container {
            text-align: center;
        }
    </style>
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

            <!-- Botão com margem inferior -->
            <div>
                <a class="btn" href="principal.php">Voltar</a>
            </div>
        </div>
    </div>

    <div class="footer">Kauana Menin — Matrícula 207165 — Projeto de Software — UPF</div>
</body>

</html>
