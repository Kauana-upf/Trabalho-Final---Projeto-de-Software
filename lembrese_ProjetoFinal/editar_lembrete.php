<?php
require_once "autenticacao.php";
// Autenticação do usuário
require_login();
$user = current_user();

// ConexaoBD - Singleton / DIP
require_once "conexao.php";
$pdo = ConexaoBD::getInstance()->getConnection();

// SRP - busca apenas o lembrete específico do usuário
$id = $_GET['id'];
$s = $pdo->prepare("SELECT * FROM reminders WHERE id=? AND user_id=?");
$s->execute([$id, $user['id']]);
$r = $s->fetch();
if (!$r) {
  header("Location: principal.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // SRP - atualização de lembrete apenas
  $dateValue = !empty($_POST['date']) ? $_POST['date'] : null;

  $upd = $pdo->prepare("UPDATE reminders 
                          SET type=?, title=?, description=?, date=?, time=?, repeat_daily=? 
                          WHERE id=? AND user_id=?");
  $upd->execute([
    $_POST['type'],
    $_POST['title'],
    $_POST['description'],
    $dateValue,
    $_POST['time'],
    isset($_POST['repeat_daily']) ? 1 : 0,
    $id,
    $user['id']
  ]);

  // UI/UX - feedback visual
  header("Location: principal.php?msg=Lembrete atualizado!");
  exit;
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.css">
  <title>Editar lembrete</title>
</head>

<body>
  <div class="main">
    <div class="container">
      <h2>Editar Lembrete</h2>

      <!-- UI/UX - formulário simples, acessível e com feedback -->
      <form method="post">
        <label for="type">Tipo de lembrete</label>
        <select name="type" id="type" class="input">
          <option value="WATER" <?= $r['type'] == 'WATER' ? 'selected' : '' ?>>💧 Água</option>
          <option value="MEDICINE" <?= $r['type'] == 'MEDICINE' ? 'selected' : '' ?>>💊 Remédio</option>
          <option value="ACTIVITY" <?= $r['type'] == 'ACTIVITY' ? 'selected' : '' ?>>🏃 Atividade</option>
        </select>

        <label for="title">Título</label>
        <input class="input" id="title" name="title" value="<?= htmlspecialchars($r['title']) ?>" required>

        <label for="description">Descrição</label>
        <textarea class="input" id="description" name="description"><?= htmlspecialchars($r['description']) ?></textarea>

        <label for="time">Horário</label>
        <input class="input" type="time" id="time" name="time" value="<?= $r['time'] ?>" required>

        <label for="date">Data</label>
        <input class="input" type="date" id="date" name="date" value="<?= $r['date'] ?>">

        <label>
          <input type="checkbox" id="repeat_daily" name="repeat_daily" <?= $r['repeat_daily'] ? 'checked' : '' ?>>
          Lembrar todos os dias
        </label>

        <button class="btn">Salvar</button>
      </form>
    </div>
  </div>

  <div class="footer">Kauana Menin — Matrícula 207165 — Projeto de Software — UPF</div>

  <script>
    // UI/UX - desabilita campo data se "todos os dias" estiver marcado
    const checkbox = document.getElementById('repeat_daily');
    const dateInput = document.getElementById('date');

    function toggleDateInput() {
      if (checkbox.checked) {
        dateInput.disabled = true;
        dateInput.value = "";
      } else {
        dateInput.disabled = false;
      }
    }

    toggleDateInput();
    checkbox.addEventListener('change', toggleDateInput);
  </script>
</body>

</html>