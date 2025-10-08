<?php

require 'config.php';

// Limpeza de entrada com htmlspecialchars é boa, mas o filter_input é mais robusto.
$nome = htmlspecialchars($_POST['nome'] ?? ''); // Uso do operador '??' para evitar Warning se a chave não existir
$email = htmlspecialchars($_POST['email'] ?? '');

// Se você quisesse usar a forma comentada, seria assim:
/*
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); // Ou FILTER_SANITIZE_SPECIAL_CHARS
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
*/

// Verifica se os campos obrigatórios foram preenchidos
if($nome && $email){

    // 1. CORREÇÃO: Mude o comando de 'INSERT' para 'SELECT'
    // A query deve ser um SELECT para *verificar* se o e-mail já existe.
    $sql = $pdo->prepare("SELECT id FROM usuario WHERE email = :email");
    $sql->bindValue(':email', $email);
    $sql->execute();

    // Verifica se o SELECT retornou zero linhas (rowCount() === 0)
    if($sql->rowCount() === 0){
        // O e-mail NÃO existe, então podemos inserir

        $sql = $pdo->prepare("INSERT INTO usuario (nome, email) VALUES (:nome, :email)");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':email', $email);
        $sql->execute();

        // 2. CORREÇÃO: Redirecionamento correto após o cadastro
        // O header() DEVE vir antes de qualquer saída de texto/HTML.
        // O exit; é necessário APÓS o header() para garantir que o script pare.
        header("Location: index.php");
        exit;
    } else {
        // O e-mail JÁ existe
        header("Location: cadastrar.php?erro=email_existente");
        exit;
    }

} else {
    // Campos vazios
    header("Location: cadastrar.php?erro=campos_vazios");
    exit;
}