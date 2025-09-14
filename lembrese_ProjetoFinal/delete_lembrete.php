<?php
require_once "conexao.php";
session_start();

// Autenticação do usuário (UI/UX - segurança)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pdo = ConexaoBD::getInstance()->getConnection(); // Singleton / DIP

    // SRP - busca apenas o lembrete específico do usuário
    $stmt = $pdo->prepare("SELECT * FROM reminders WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $reminder = $stmt->fetch();

    if (!$reminder) {
        header("Location: principal.php?msg=Lembrete não encontrado");
        exit;
    }

    // SRP - exclusão apenas do lembrete
    if (isset($_POST['confirm'])) {
        $del = $pdo->prepare("DELETE FROM reminders WHERE id = ? AND user_id = ?");
        $del->execute([$id, $_SESSION['user_id']]);

        // UI/UX - feedback visual após exclusão
        header("Location: principal.php?msg=Lembrete excluído com sucesso!");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Excluir Lembrete</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="main">
        <div class="container">
            <h2>Excluir Lembrete</h2>

            <!-- UI/UX - Confirmação clara para o usuário -->
            <p>Você tem certeza que deseja excluir o lembrete <b><?= htmlspecialchars($reminder['title']); ?></b>?</p>

            <form method="post">
                <button class="btn red" type="submit" name="confirm" value="1">Sim, excluir</button>
                <a class="btn" href="principal.php">Cancelar</a>
            </form>
        </div>
    </div>
</body>

</html>