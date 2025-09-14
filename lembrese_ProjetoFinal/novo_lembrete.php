<?php
require_once "autenticacao.php";

// Autenticação do usuário
require_login();
$user = current_user();

require_once "conexao.php";

// Singleton / DIP
$pdo = ConexaoBD::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $dateValue = !empty($_POST['date']) ? $_POST['date'] : null;

    // SRP
    $sql = "INSERT INTO reminders (user_id, type, title, description, date, time, repeat_daily) 
            VALUES (:user_id, :type, :title, :description, :date, :time, :repeat_daily)";
    $ins = $pdo->prepare($sql);

    $ins->execute([
        ':user_id'      => $user['id'],
        ':type'         => $_POST['type'],
        ':title'        => $_POST['title'],
        ':description'  => $_POST['description'],
        ':date'         => $dateValue,
        ':time'         => $_POST['time'],
        ':repeat_daily' => isset($_POST['repeat_daily']) ? 1 : 0
    ]);

    // Feedback visual
    header("Location: principal.php?msg=Lembrete criado com sucesso!");
    exit;
}
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Novo lembrete</title>
</head>

<body>
    <div class="main">
        <div class="container">
            <h2>Adicionar Lembrete</h2>

            <!-- Formulário simples e acessível -->
            <form method="post">
                <label for="type">Tipo de lembrete</label>
                <select id="type" name="type" class="input" required>
                    <option value="WATER">💧 Água</option>
                    <option value="MEDICINE">💊 Remédio</option>
                    <option value="ACTIVITY">🏃 Atividade</option>
                </select>

                <label for="title">Título</label>
                <input class="input" id="title" name="title" placeholder="Ex: Caminhada" required>

                <label for="description">Descrição</label>
                <textarea class="input" id="description" name="description" placeholder="Ex: Consulta com o Dr. João"></textarea>

                <label for="time">Horário</label>
                <input class="input" type="time" id="time" name="time" required>

                <label for="date">Data</label>
                <input class="input" type="date" id="date" name="date">

                <label>
                    <input type="checkbox" id="repeat_daily" name="repeat_daily">
                    Lembrar todos os dias
                </label>

                <button class="btn">Salvar Lembrete</button>
            </form>
        </div>
    </div>

    <div class="footer">
        Kauana Menin — Matrícula 207165 — Projeto de Software — UPF
    </div>

    <script>
        // UI/UX - Ajuste do formulário se "Lembrar todos os dias" for marcado
        document.getElementById('repeat_daily').addEventListener('change', function() {
            let dateInput = document.getElementById('date');
            if (this.checked) {
                dateInput.required = false;
                dateInput.disabled = true;
                dateInput.value = "";
            } else {
                dateInput.required = false;
                dateInput.disabled = false;
            }
        });
    </script>
</body>

</html>
