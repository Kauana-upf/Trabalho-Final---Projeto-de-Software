<?php
// Requisito: UI/UX - logout seguro
session_start();
session_destroy();
header("Location: login.php?msg=Você saiu do sistema");
