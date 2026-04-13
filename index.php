<?php
session_start();

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    $logado = false;
    $funcao = "";

    if (!file_exists("usuarios.txt")){
        $msg = "<p class='erro'>Erro: Arquivo de usuários não existe. Cadastre um usuário primeiro!</p>";
    } else {
        $arquivo = fopen("usuarios.txt", "r") or die("Erro ao abrir arquivo de usuários!");
        fgets($arquivo); 
        
        while(($linha = fgets($arquivo)) !== false){
            $dados = explode(";", trim($linha)); 
            $user_arq = $dados[0];
            $senha_arq = $dados[1];
            $funcao_arq = $dados[2];

            if ($usuario == $user_arq && $senha == $senha_arq){
                $logado = true;
                $funcao = $funcao_arq;
                break;
            }
        }
        fclose($arquivo);
    }
    
    if ($logado){
        $_SESSION['funcao'] = $funcao; 
        
        if ($funcao == 'gestor'){
            header("Location: gestor.php");
            exit;
        } else if ($funcao == 'funcionario'){
            header("Location: funcionario.php");
            exit;
        }
    } else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $msg = "<p class='erro'>Usuário ou senha inválidos.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Jogo Corporativo</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Home Jogo Corporativo - Login</h1>
        
        <form action="index.php" method="POST">
            Usuário: <input type="text" name="usuario" required>
            <br><br>
            Senha: <input type="password" name="senha" required>
            <br><br>
            <input type="submit" value="Entrar">
        </form>

        <p><?php echo $msg; ?></p>
    </body>
</html>
