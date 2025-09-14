<?php
require_once "conexao.php";
session_start();

$erro = "";
$msg = "";

// Requisito: SOLID - SRP (esta parte apenas cadastra usuários)
// Requisito: UI/UX - feedback visual (mensagens de sucesso/erro)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Requisito: Singleton / DIP (usa a conexão via instância única)
    $pdo = ConexaoBD::getInstance()->getConnection();

    // Verifica se já existe usuário com este email
    $s = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $s->execute([$_POST['email']]);
    $u = $s->fetch(PDO::FETCH_ASSOC);

    if ($u) {
        $erro = "Este e-mail já está cadastrado!";
    } else {
        try {
            $senhaHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $s = $pdo->prepare("INSERT INTO users (nome, email, password) VALUES (?, ?, ?)");
            $s->execute([$_POST['nome'], $_POST['email'], $senhaHash]);

            $msg = "Cadastro realizado com sucesso! Você já pode fazer login.";
            header("refresh:2;url=login.php");
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Cadastro</title>
</head>

<body>
    <div class="main">
        <div class="container">
            <h2>Registrar</h2>

            <!-- Requisito: UI/UX - mensagens de feedback -->
            <?php if ($erro): ?><div class="alert alert-error"><?= $erro ?></div><?php endif; ?>
            <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>

            <!-- Requisito: UI/UX - formulário simples e acessível -->
            <form method="post">
                <input class="input" name="nome" placeholder="Nome completo" required>
                <input class="input" type="email" name="email" placeholder="Email" required>
                <input class="input" type="password" name="password" placeholder="Senha" required>
                <button class="btn">Cadastrar</button>
            </form>

            <p>Já tem conta? <a href="login.php">Entrar</a></p>
        </div>
    </div>

    <div class="footer">Kauana Menin — Matrícula 207165 — Projeto de Software — UPF</div>
</body>

</html>