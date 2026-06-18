<?php

$host = 'localhost';
$banco = 'atendelab';
$usuario = 'root';
$senha = '';
$porta = '3306'; // Altere para 3307 se necessário

try {
    $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}