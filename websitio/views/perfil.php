<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/perfil.css">
    <title>Perfil do usuário</title>
</head>
<?php

    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once "../db/conexao.php";

    $id_user = isset($_GET['id']) ? $_GET['id'] : 0;

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'USU') {
        header("Location: ./home.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # recupera o id do enviado por post para delete ou update.
        $id = (isset($_POST['id_user']) ? $_POST['id_user'] : 0);
        $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
        # verifica se o nome do botão acionado por post se é deletar ou atualizar
        if($operacao === 'deletar'){
            # cria uma query no banco de dados para excluir o usuario com id informado 
            $query = "DELETE FROM `gate`.`usuario` WHERE id_user = :id_user";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':id_user', $id);
            
            # executa a consulta banco de dados para excluir o registro.
            $stmt->execute();

            # verifica se a quantiade de registros excluido é maior que zero.
            # se sim, redireciona para a pagina de admin com mensagem de sucesso.
            # se não, redireciona para a pagina de admin com mensagem de erro.
            if($stmt->rowCount()) {
                header('location: login.php?success=Usuário excluído com sucesso!');
            } else {
                header('location: perfil.php?error=Erro ao excluir usuário!');
            }
        }

    }

    # cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
    # ordenando pelo campo perfil e nome.
    $query = "SELECT * FROM `gate`.`usuario` ORDER BY id_user = :id_user";
    $stmt = $dbh->prepare($query);
    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    

    # destroi a conexao com o banco de dados.
    $dbh = null; 

?>
<body>
    <div class="profile-card">
        <div class="img-container">
            <img src="../IMG/perfil.png" style="width:100%">
            <div class="titulo">
                <h2>Dados do Usuário</h2>
            </div>
        </div>
        <div class="main-container">
        <?php if($rows){?>
            <p>Nome:</p><?=$row['nome']?>
            <p>Email:</p><?=$row['email']?>
            <p>Estado:</p><?=$row['estado']?>
            <p>Cidade:</p><?=$row['cidade']?>
            <p>Gênero:</p><?=$row['sexo']?>
            <hr>
            <form action="" method="post">
                <input type="hidden" name="id_user" value="<?=$row['id_user']?>">
                    <button class="btn" 
                            name="botao" 
                            value="deletar"
                            onclick="return confirm('Deseja excluir o usuário?');">Excluir</button>
                    <button class="btn" 
                    name="editar" 
                    value="editar"
                    onclick="return confirm('Deseja excluir o usuário?');">Editar</button>
            </form>
            <?php }else{?>
                <strong>Não existem usuários cadastrados.</strong>
                <?php } ?>
        </div>
    </div>
</body>
</html>