<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/cadastro-c.css">
    <title>Cadastro de Games</title>
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
        $tipo = 'GAME';
        $usuario_id_user = $_SESSION['usuario']['id'];
        
        # definie o caminho onde sera gravado o arquivo.
        $uploaddir = __DIR__ . '/conteudo/';
        $imagemName = basename($_FILES['img']['name']);
        $uploadfile = $uploaddir . $imagemName;
        
        # verifica se o diretorio existe? Se não existir cria um novo.
        if(!file_exists($uploaddir)) {
            mkdir($uploaddir, 0777);
        }
        # recebe o arquivo a ser gravado e inserido no diretorio criado. 
        # Se sim, gravano diretorio. Se não, limpa o nome da variavel que
        # sera usada no banco de dados.
        if(!move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)){
            $imagemName  = '';
        }
        
        // echo '<pre>';var_dump($_FILES, $_POST, $uploaddir, $imagemName, $uploadfile); exit;

        # cria um comando SQL para adicionar valores na tabela categorias 
        $query = "INSERT INTO `gate`.`conteudo` (`link`,`titulo`, `texto`, `tipo`, `tipoImg`, `usuario_id_user`)
                    VALUES (:link, :titulo, :texto, :tipo, :tipoImg, :usuario_id_user)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':texto', $texto);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':tipoImg', $imagemName);
        $stmt->bindParam(':usuario_id_user', $usuario_id_user);

        // 

        # executa o comando SQL para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: userAdm.php?success=Tendência inserido com sucesso!');
        } else {
            header('location: addGame.php?error=Erro ao inserir Tendência!');
        }
    }

    # destroi a conexao com o banco de dados.
    $dbh = null;
?>
<body>
    <div class="center">
        <h1>Cadastro de Games</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="url">
                <input type="url" name="link" pattern="https://.*">
                <span></span>
                <label>Link</label>
            </div>

            <div class="url">
                <input type="text" name="titulo">
                <span></span>
                <label>Título</label>
            </div>

                <textarea class="txtarea" name="texto" placeholder="Texto" cols="30" rows="10"></textarea>
                <span></span>
                <!-- <label>Texto</label> -->

                <input type="file" name="img" id="uploadbtn" accept=".jpg, .gif, .png">
                <label for="uploadbtn" class="uploadBtn">Escolher Arquivo</label>

            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
</html>