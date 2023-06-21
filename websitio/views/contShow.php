<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();

    # inclui os arquivos header, menu e login.
    require_once "../db/conexao.php";

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();
    
    # cria variavel que recebe parametro da categoria
    # se foi passado via get quando o campo select do
    # formulario é modificado.    
    // $id_c = isset($_GET['id']) ? $_GET['id'] : 0;

    
    # cria uma consulta banco de dados buscando todos os dados da tabela  
    # ordenando pelo campo data e limita o resultado a 10 registros.
    $query = "SELECT * FROM `gate`.`conteudo` ORDER BY titulo, tipo";    
    $stmt = $dbh->prepare($query);
    
    $query .= " ORDER BY titulo DESC limit 8";
    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    # destroi a conexao com o banco de dados.
    $dbh = null;
    // echo '<pre>';var_dump($row);exit;
?>

<main>
    
    <?php
        # verifca se existe uma mensagem de erro enviada via GET.
        # se sim, exibe a mensagem enviada no cabeçalho.
        if(isset($_GET['error']) || isset($_GET['success']) ) { ?>
            <script>
                Swal.fire({
                icon: '<?php echo (isset($_GET['error']) ? 'error' : 'success');?>',
                title: 'GatePhowx',
                text: '<?php echo (isset($_GET['error']) ? $_GET['error']: $_GET['success']); ?>',
                })
            </script>
    <?php } ?>
    <div class="main_cta">
        <header>
            <h1>Artigos</h1>
        </header>
    </div>
    <section class="main_blog">
        <header class="main_blog_header">
            <?php 
                // if(!$row) {
                //     header('location: ./home.php?error=Artigo não encontrado!');
                //     exit;
                // }    
            ?>
            <h1 class="icon-blog">
                <?=$row['titulo'];?>
            </h1>
            <p>
                <strong>Conteudo: </strong><span><?=$row['texto'];?></span>
            </p>
        </header>
        <section class="artigo_show">
            <div>
                <?php 
                    $path =  'conteudo/'.$row['tipoImg'];
                    echo "<img alt='" . $row['titulo'] . "' src='$path'>";
                ?>
                
            </div>
            <br>
            <p class="artigo_show__texto">
               <?=$row['texto'];?>
            </p>
        </section>
        <br>
        <br>
        <?php if($rows) { foreach ($rows as $row){ ?>
            <article>
                
                <img src="<?=$imagem?>" width="200" alt="<?=$row['titulo']?>" title="<?=$row['titulo']?>">
                <h2 class="title" title="<?=$row['texto']?>">

                <p class="data"><?=$row['texto'];?></p>
                </h2>
            </article>
        <?php } } else { echo "<p>Não existem artigos cadastrados</p>"; } ?>
    </section>
</main>
