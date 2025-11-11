<?php
require 'config.php';

$usuario = [];
$id = filter_input(INPUT_GET, 'id');

if($id){
    $sql = $pdo->prepare("SELECT * FROM usuario WHERE id = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    if($sql->rowCount() > 0){
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    }else{
        header("Location: index.php");
        exit;
    }
}else{
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="cyberpunk.css">
</head>
<body>

<h1>Editar Usuário</h1>
<form method="POST" action="editar_action.php">
    <input type="hidden" name="id" value="<?=$usuario['id'];?>"/>
    <label>
        Nome: <input type="text" name="nome" value="<?=$usuario['nome'];?>"/>
    </label>
    <label>
        E-mail: <input type="text" name="email" value="<?=$usuario['email'];?>"/>
    </label>
    <input type="submit" value="Atualizar"/>
</form>

</body>
</html>