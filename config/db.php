<?php
// Credenciais de conexão — ajustar conforme configuração da VM
$host     = 'localhost';
$user     = 'root';
$password = '';
$database = 'contact_list';

// Abre a conexão com o banco usando MySQLi
$conn = mysqli_connect($host, $user, $password, $database);

// Encerra com mensagem de erro se a conexão falhar
if (!$conn) {
    die('Erro ao conectar com o banco de dados: ' . mysqli_connect_error());
}

// Define o charset para evitar problemas com caracteres especiais
mysqli_set_charset($conn, 'utf8mb4');