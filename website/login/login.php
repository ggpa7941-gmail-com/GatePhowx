<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./_css/login.css">
    <title>login</title>
</head>
<?php
    require_once "../db/conexao.php";

    # verifica se os dados do formulario foram passados via método POST.
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
        # cria duas variaveis (nome, password) para armazenar os dados passados via método POST.
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $senha = isset($_POST['senha']) ? md5($_POST['senha']) : '';

        # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
        $dbh = Conexao::getInstance();

        # cria uma consulta banco de dados verificando se o usuario existe 
        # usando como parametros os campos nome e password.
        $query = "SELECT * FROM `gate`.`usuario` WHERE email = :email AND `senha` = :senha";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);

        # executa a consulta banco de dados e aguarda o resultado.
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       
        # se o resultado retornado for diferente de NULL, cria uma sessão com os dados do usuario.
        # e redireciona para a pagina de administracao de usuarios.
        # se não, destroi toodas as sessões existentes e redireciona para a pagina inicial.
        if($row) {
            $_SESSION['usuario'] = [
                'email' => $row['email'],
                'perfil' => $row['perfil'],
            ];
            if($row['perfil'] === 'ADM') {
                header('location: ../usuarios/userAdm.php');
            } else {
                header('location: ../usuarios/user.php');
            }
        } else {
            session_destroy();
            header('location: ../login/login.php?error=Usuário ou senha inválidos.');
        }

        # destroi a conexao com o banco de dados.
        $dbh = null;
    }
?>
<body>
    <div class="center">
        <h1>Login</h1>
        <form action="../usuarios/user.php" method="post">
            <div class="txt">
                <input type="text" required>
                <span></span>
                <label>Usuario</label>
            </div>
            <div class="txt">
                <input type="password" required>
                <span></span>
                <label>Senha</label>
            </div>
            <div class="pass">Recuperar senha</div>
            <input type="submit" value="Login">
            <div class="signup">
                Não é cadastrado? <a href="../cadastro/cadastro1.php">Cadastre-se</a>
            </div>
        </form>
    </div>
</body>
</html>