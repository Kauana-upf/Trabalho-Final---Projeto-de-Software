<?php
class ConexaoBD
{
    private static $instance; // Singleton
    private $conn;

    private $host = "localhost";
    private $db = "lembrese";
    private $user = "root";
    private $pass = "";

    // Construtor privado → evita múltiplas conexões (Singleton)
    private function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db;charset=utf8",
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Falha na conexão: " . $e->getMessage());
        }
    }

    // Requisito: Design Pattern - Singleton
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ConexaoBD();
        }
        return self::$instance;
    }

    // Requisito: SOLID - DIP (dependências acessam a conexão via instância única)
    public function getConnection()
    {
        return $this->conn;
    }
}
