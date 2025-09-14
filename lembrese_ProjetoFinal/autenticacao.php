<?php
// auth.php → Controle de sessão e autenticação

session_start();
require_once "conexao.php";

// Requisito: SOLID - SRP (esta função só retorna dados do usuário logado)
function current_user()
{
    if (!isset($_SESSION['user_id'])) return null;

    // Requisito: Singleton / DIP (usa a conexão via instância única)
    $pdo = ConexaoBD::getInstance()->getConnection();
    $s = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $s->execute([$_SESSION['user_id']]);
    return $s->fetch(PDO::FETCH_ASSOC);
}

// Requisito: SOLID - SRP (esta função só verifica se usuário está logado)
// Requisito: UI/UX - segurança (bloqueio de acesso não autorizado)
function require_login()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}
