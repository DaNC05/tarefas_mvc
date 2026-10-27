<?php
class Conexao
{
    public static function getConexao()
    {
        $utilizador = 'root';
        $chave = '';
        $dsn = "mysql:host=localhost;dbname=tarefas_mvc;charset=utf8";


        try {
            $conexao = new PDO($dsn, $utilizador, $chave);
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conexao;
        } catch (PDOException $err) {
            echo $err->getMessage();
        }
    }
}
