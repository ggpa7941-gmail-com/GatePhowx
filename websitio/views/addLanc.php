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

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'ADM' )) {
        header("Location: ./home.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();
    
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (nome, status, tipo) para armazenar os dados passados via método POST.
        $link = isset($_POST['link']) ? $_POST['link'] : '';
        $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
        $texto = isset($_POST['texto']) ? $_POST['texto'] : '';
        $tipo = 'LANC';
        $tipoImg = isset($_POST['tipoImg']) ? $_POST['tipoImg'] : '';

        

        # cria um comando SQL para adicionar valores na tabela categorias 
        $query = "INSERT INTO `gate`.`conteudo` (`link`,`titulo`, `texto`, `tipo`, `tipoImg`)
                    VALUES (:link, :titulo, :texto, :tipo, :tipoImg)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':texto', $texto);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':tipoImg', $img);

        // echo '<pre>';var_dump($query); exit;

        # executa o comando SQL para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: userAdm.php?success=Categoria inserido com sucesso!');
        } else {
            header('location: addTend.php?error=Erro ao inserir categoria!');
        }
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
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