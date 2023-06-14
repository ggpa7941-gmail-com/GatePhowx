<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/cadastro-c.css">
    <title>Cadastro de Lançamentos</title>
</head>
<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
   
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once "../db/conexao.php";
   
    # verifica se os dados do formulario foram enviados via POST
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (link, texto) para armazenar os dados passados via método POST.
        $link = isset($_POST['link']) ? $_POST['link'] : '';
        $texto = isset($_POST['texto']) ? $_POST['texto'] : '';
        
        // $status = 1;
       
        // echo '<pre>';var_dump($_POST); exit;
        # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
        $dbh = Conexao::getInstance();



        
        # cria uma consulta banco de dados verificando se o usuario existe
        # usando como parametros os campos nome e password.
        $query = "INSERT INTO `gate`.`conteudo` (`link`,`texto`)
                    VALUES ( :link, :texto)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':texto', $texto);
        $stmt->bindParam(':sexo', $sexo);




        # executa a consulta banco de dados para inserir o resultado.
        $stmt->execute();


        $idUsuario = $dbh->lastInsertId();
        

        $query = "INSERT INTO `gate`.`imagem` (`link`, `tipo`, `usuario_id_user`)
                    VALUES (:cidade, :estado, :usuario_id_user)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':usuario_id_user', $idUsuario);
        # executa a consulta banco de dados para inserir o resultado.
        $stmt->execute();


        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: ../login/login.php?success=Cadastro realizado com sucesso!');
        } else {
            header('location: cadastro1.php?error=Erro ao cadastrar nova conta!');
        }

        # destroi a conexao com o banco de dados.
        $dbh = null;
    }
?>
<body>
    <div class="center">
        <h1>Cadastro de Lançamentos</h1>
        <form method="post">
            <div class="url">
                <input type="url" name="url" pattern="https://.*">
                <span></span>
                <label>Link</label>
            </div>

            <div class="url">
                <input type="text" name="titulo">
                <span></span>
                <label>Título</label>
            </div>

            <div class="url">
                <input type="text" name="data">
                <span></span>
                <label>Data de lançamento</label>
            </div>

                <textarea class="txtarea" name="area" placeholder="Texto" cols="30" rows="10"></textarea>
                <span></span>
                <!-- <label>Texto</label> -->

                <input type="file" id="uploadbtn" accept=".jpg, .gif, .png">
                <label for="uploadbtn" class="uploadBtn">Escolher Arquivo</label>

            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
</html>