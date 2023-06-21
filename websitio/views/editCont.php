<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once "../db/conexao.php";

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] == 'USU')) {
        header("Location: ./home.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # verifica se uma variavel id foi passada via GET 
    $id = isset($_GET['id_c']) ? $_GET['id_c'] : 0;

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();
    
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (nome, status, tipo) para armazenar os dados passados via método POST.
        $link = isset($_POST['link']) ? $_POST['link'] : '';
        $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
        $texto = isset($_POST['texto']) ? $_POST['texto'] : '';
        // $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $usuario_id_user = $_SESSION['usuario']['id'];
        
        # verifica se a imagem a ser cadastrada é interna? Se sim, entra no if.

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
        // echo '<pre>'; var_dump($usuario); exit;
        
        # cria um comando SQL para adicionar valores na tabela categorias 
        $query = "UPDATE `gate`.`conteudo` SET
                `link` = :link, 
                `titulo` = :titulo, 
                `texto` = :texto,  
                `tipoImg` = :tipoImg,
                `usuario_id_user` = :usuario_id_user
                WHERE id_c = :id_c";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':texto', $texto);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':imagem', $imagemName);
        $stmt->bindParam(':usuario_id_user', $usuario_id_user);
        $stmt->bindParam(':id_c', $id_c);

        # executa o comando SQL para inserir o resultado.
        $stmt->execute();

        # verifica se a quantiade de registros inseridos é maior que zero.
        # se sim, redireciona para a pagina de admin com mensagem de sucesso.
        # se não, redireciona para a pagina de cadastro com mensagem de erro.
        if($stmt->rowCount()) {
            header('location: artigo_index.php?success=Artigo atualizado com sucesso!');
        } else {
            // echo '<pre>';var_dump($stmt->errorInfo()); exit;
            header('location: artigo_edit.php?id='. $id . '&error=Erro ao atualizar artigo!');
        }
    }

    # cria uma consulta banco de dados buscando todos os dados da tabela  
    # ordenando pelo campo nome.
    $query = "SELECT * FROM `gate`.`conteudo` WHERE id_c = :id_c";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':id_c', $id);

    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

        
    # cria uma consulta banco de dados buscando todos os dados da tabela  
    # ordenando pelo campo nome.
    $query = "SELECT * FROM `gate`.`conteudo` ORDER BY titulo";
    $stmt = $dbh->prepare($query);
    
    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    # destroi a conexao com o banco de dados.
    $dbh = null;
?>