<?php
require_once "conexao.php";
session_start();
$erro = "";
$msg = "";

// Requisito: UI/UX - autenticação de usuário
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Requisito: Design Pattern - Singleton (ConexaoBD)
  $pdo = ConexaoBD::getInstance()->getConnection();

  $s = $pdo->prepare("SELECT * FROM users WHERE email=?");
  $s->execute([$_POST['email']]);
  $u = $s->fetch();

  // Requisito: UI/UX - feedback visual de login
  if ($u && password_verify($_POST['password'], $u['password'])) {
    $_SESSION['user_id'] = $u['id'];
    $msg = "Login realizado com sucesso!";
    header("refresh:1;url=principal.php"); // redireciona após 1s
    exit;
  } else {
    $erro = "Email ou senha inválidos.";
  }
}
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.css">
  <title>Login</title>
</head>

<body>
  <div class="main">
    <div class="container">
      <h1>Lembre-se</h1>
      <h2>Login</h2>
      <?php if ($erro): ?><div class="alert alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
      <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

      <form method="post">
        <input class="input" type="email" name="email" placeholder="Email" required>
        <input class="input" type="password" name="password" placeholder="Senha" required>
        <button class="btn">Entrar</button>
        <a href="cadastro.php">Registrar-se</a>
      </form>
    </div>
  </div>
  <div class="footer">Kauana Menin — Matrícula 207165 — Projeto de Software — UPF</div>
</body>

</html>