<?php
require_once "conexao.php";
require_once "autenticacao.php";

// Requisito: Autenticação do usuário (UI/UX - segurança)
require_login();
$user = current_user();

// Verifica se o ID do lembrete foi passado
if (!isset($_GET['id'])) {
    header("Location: principal.php?msg=Lembrete inválido");
    exit;
}

$pdo = ConexaoBD::getInstance()->getConnection(); // Requisito: Singleton / DIP
$id = $_GET['id'];

// Requisito: SOLID - SRP (esta parte apenas manipula o status do lembrete)
$stm = $pdo->prepare("SELECT * FROM reminders WHERE id=? AND user_id=?");
$stm->execute([$id, $user['id']]);
$lembrete = $stm->fetch();

if (!$lembrete) {
    header("Location: principal.php?msg=Lembrete não encontrado");
    exit;
}

// Verifica se já foi concluído
if ($lembrete['status'] === 'DONE') {
    header("Location: principal.php?msg=Esta atividade já foi concluída por hoje, volte amanhã.");
    exit;
}

// Atualiza o status para DONE
$update = $pdo->prepare("UPDATE reminders SET status='DONE' WHERE id=?");
$update->execute([$id]);

header("Location: principal.php?msg=Lembrete concluído!");
exit;
