<?php
require_once "conexao.php";
session_start();

$erro = "";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Singleton / DIP: Pega a conexão via ConexaoBD
  $pdo = ConexaoBD::getInstance()->getConnection();

  // SRP: verifica se email já existe
  $s = $pdo->prepare("SELECT id FROM users WHERE email=?");
  $s->execute([$_POST['email']]);

  if ($s->fetch()) {
    // UI/UX - feedback visual de erro
    $erro = "Email já cadastrado!";
  } else {
    // SRP: cria novo usuário
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $ins = $pdo->prepare("INSERT INTO users(name,email,password) VALUES (?,?,?)");
    $ins->execute([$_POST['name'], $_POST['email'], $hash]);

    $_SESSION['user_id'] = $pdo->lastInsertId();
    // UI/UX - feedback visual de sucesso
    $msg = "Conta criada com sucesso!";
    header("refresh:1;url=dashboard.php");
  }
}
?>
<!doctype html>
<html>

<head>
  <link rel="stylesheet" href="css/style.css">
  <title>Registrar</title>
</head>

<body>
  <div class="main">
    <div class="container">
      <h2>Criar Conta</h2>

      <!-- UI/UX - feedback visual -->
      <?php if ($erro): ?><div class="alert"><?= $erro ?></div><?php endif; ?>
      <?php if ($msg): ?><div class="alert"><?= $msg ?></div><?php endif; ?>

      <!-- UI/UX - formulário simples e acessível -->
      <form method="post">
        <input class="input" name="name" placeholder="Nome completo" required>
        <input class="input" type="email" name="email" placeholder="Email" required>
        <input class="input" type="password" name="password" placeholder="Senha" required>
        <button class="btn">Registrar</button>
      </form>
    </div>
  </div>

  <div class="footer">Kauana Menin — Matrícula 207165 — Projeto de Software — UPF</div>
</body>

</html>