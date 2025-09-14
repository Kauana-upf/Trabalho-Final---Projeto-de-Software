<?php
require_once "conexao.php";

class Lembrete
{
    private $id;
    private $titulo;
    private $descricao;
    private $data;

    // SRP - construtor: inicializa apenas dados do lembrete
    public function __construct($titulo, $descricao, $data)
    {
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->data = $data;
    }

    // DIP - salva lembrete usando ConexaoBD (Singleton)
    public function salvar()
    {
        $conn = ConexaoBD::getInstance()->getConnection();

        $sql = "INSERT INTO lembretes (titulo, descricao, data) VALUES (:titulo, :descricao, :data)";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':titulo', $this->titulo, PDO::PARAM_STR);
        $stmt->bindValue(':descricao', $this->descricao, PDO::PARAM_STR);
        $stmt->bindValue(':data', $this->data, PDO::PARAM_STR);

        $stmt->execute();
    }
}

class LembreteFactory
{
    // Factory - cria objetos Lembrete centralizando lógica
    public static function criarLembrete($titulo, $descricao, $data)
    {
        return new Lembrete($titulo, $descricao, $data);
    }
}
