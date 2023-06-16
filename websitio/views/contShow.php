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
    $id_c = isset($_GET['id']) ? $_GET['id'] : 0;
    
    
    # cria uma consulta banco de dados buscando todos os dados da tabela  
    # ordenando pelo campo data e limita o resultado a 10 registros.
    $query = "SELECT * FROM `gate`.`conteudo` WHERE id_c = $id_c ";    
    $stmt = $dbh->prepare($query);
    
    # executa a consulta banco de dados e aguarda o resultado.
    $stmt->execute();
    
    # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
    # se não existir retorna null
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    # destroi a conexao com o banco de dados.
    $dbh = null;
    // echo '<pre>';var_dump($row);exit;
?>

<!--DOBRA PALCO PRINCIPAL-->

<!--1ª DOBRA-->

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
    <!-- <style>
        .main_cta header {
            color: #fff;
            font-size: 2.3rem;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0;
        }
        
        .main_blog_header p {
            margin-top:20px;
        }
        .main_blog_header p span {
            border-radius: 5px;
            border: 1px solid darkorange;
            font-weight: 600;
            background-color: darkorange;
            color: #fff;
            padding:3px 25px;
        }

        .artigo_show div img {
            height:500px;
            width: 100%;
        }

        .artigo_show p  {
            margin-top: 10px;
        }

        .artigo_show__texto {
            font-size: 1.2rem;
            font-weight: 100;
            text-align: justify;
        }
    </style> -->
    <div class="main_cta">
        <header>
            <h1>Artigos</h1>
        </header>
    </div>
    <!--FIM 1ª DOBRA-->
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
    </section>
</main>

