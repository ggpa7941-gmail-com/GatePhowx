<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/listUser.css">
    <title>Lista de conteúdos</title>
</head>
<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once "../db/conexao.php";

    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || ($_SESSION['usuario']['perfil'] != 'ADM')) {
        header("Location: ./home.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();

     # Rotina para excluir dados da tabela artigos
    # verifica se os dados do formulario foram enviados via POST 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # recupera o id do enviado por post para delete ou update.
        $id = (isset($_POST['id_c']) ? $_POST['id_c'] : 0);
        $operacao = (isset($_POST['botao']) ? $_POST['botao'] : null);
        # verifica se o nome do botão acionado por post se é deletar 
        if($operacao === 'deletar'){
            # cria uma query no banco de dados para excluir o usuario com id informado 
            $query = "DELETE FROM `gate`.`conteudo` WHERE id_c = :id_c";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':id_c', $id);
            
            # executa a consulta banco de dados para excluir o registro.
            $stmt->execute();

            # verifica se a quantiade de registros excluido é maior que zero.
            # se sim, redireciona para a pagina de admin com mensagem de sucesso.
            # se não, redireciona para a pagina de admin com mensagem de erro.
            if($stmt->rowCount()) {
                header('location: listConteudo.php?success=Artigo excluído com sucesso!');
            } else {
                header('location: listConteudo.php?error=Erro ao excluir artigo!');
            }
        }
    } 

    # cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
    # ordenando pelo campo perfil e nome.
    $query = "SELECT * FROM `gate`.`conteudo` ORDER BY titulo, tipo";
    $stmt = $dbh->prepare($query);
    
    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    # destroi a conexao com o banco de dados.
    $dbh = null;
?>
<body>
    <div class="lista">
        <header class="main-header-lista">
            <h1>Lista de conteúdos</h1>
        </header>
    </div>
<main>
<?php
        # verifca se existe uma mensagem de erro enviada via GET.
        # se sim, exibe a mensagem enviada no cabeçalho.
        if(isset($_GET['error']) || isset($_GET['success']) ) { ?>
            <script>
                Swal.fire({
                icon: '<?php echo (isset($_GET['error']) ? 'error' : 'success');?>',
                title: 'Artigos',
                text: '<?php echo (isset($_GET['error']) ? $_GET['error']: $_GET['success']); ?>',
                })
            </script>
        <?php } ?>
        <div class="main_opc">
            <div class="main_stage">
                <article>
                    <header>
                        <table  width="100%" class="table">
                            <tr>
                                <th>ID</th>
                                <th>URL</th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>texto</th>
                                <th>Imagem</th>
                                <th>Ação</th>
                            </tr>
                            <?php
                                # verifica se os dados existem na variavel $row.
                                # se existir faz um loop nos dados usando foreach.
                                # cria uma variavel $count para contar os registros da tabela.
                                # se não existir vai para o else e imprime uma mensagem.
                                if($rows) {
                                    $count = 1;
                                    foreach ($rows as $row) {?>
                                    <tr>
                                        <td><?=$count?></td>
                                        <td><?=$row['link']?></td>
                                        <td><?=$row['titulo']?></td>
                                        <td><?=$row['tipo']?></td>
                                        <td><?=$row['texto']?></td>
                                        <td><?=$row['tipoImg']?></td>
                                        <td>
                                            <div style="display:flex;">
                                                <form action="" method="post">
                                                    <input type="hidden" name="id_c" value="<?=$row['id_c']?>">
                                                    <button class="btn"
                                                    name="botao"
                                                    value="deletar"
                                                    onclick="return confirm('Deseja apagar este conteúdo ?');">Excluir</button>
                                                    <button class="btn">Editar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>    
                                    <?php $count++;} } else {?>
                                <tr><td colspan="6"><strong>Não existem conteúdos cadastrados.</strong></td></tr>
                            <?php }?>
                        </table>
                    </header>
                </article>
            </div>
        </div>

</main>

    <div class="botao">
        <a class="back-btn" href="../views/userAdm.php">Voltar</a>
    </div>

</body>
</html>