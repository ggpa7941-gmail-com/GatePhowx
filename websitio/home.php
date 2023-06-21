<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/home.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Pagina de noticias</title>
</head>
<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();

    # inclui os arquivos header, menu e login.
    
    require_once "./db/conexao.php";

    # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
    $dbh = Conexao::getInstance();
    
    # cria variavel que recebe parametro da categoria
    # se foi passado via get quando o campo select do
    # formulario é modificado.
    $filtroTitulo = isset($_GET['filtro']) ? $_GET['filtro'] : null;
    
    
    # cria uma consulta banco de dados buscando todos os dados da tabela  
    # ordenando pelo campo data e limita o resultado a 10 registros.
    $query = "SELECT * FROM `gate`.`conteudo` WHERE titulo = titulo";
    # verifica se existe filtro para categoria.
    # se sim adiciona condição ao select.
    if($filtroTitulo != null && $filtroTitulo != "0") {
        $query .= " AND titulo LIKE '%" .$filtroTitulo . "%' ";    
    }

    $query .= " ORDER BY titulo DESC limit 8";

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
    <section class="hero">
        <div class="main-width">
            <header>
                <div class="logo">
                    <h2><a href="#">GatePhowx</a></h2>
                </div>

                <div class="search-box">
                    <input type="text" 
                        name="filtro" 
                        value="<?= isset($_GET['filtro'])?$_GET['filtro']:'';?>" 
                        class="search-txt" placeholder="Faça sua pesquisa">

                    <a href="#" class="search-btn">
                        <img src="./IMG/loupe.png" alt="Lupa" width="15" height="15">
                    </a>
                </div>

                <nav>
                    <ul>
                        <li active><a href="#">Home</a></li>
                        <li><a href="#lancamentos">Lançamentos</a></li>
                        <li><a href="#games">Games</a></li>
                        <li><a href="#fms">filmes/séries</a></li>
                        <li><a href="#animes">Animes</a></li>
                        <li><a href="#">Perfil</a></li>
                        <?php if(isset($_SESSION['usuario']))  {?>
                            <li class="botao"><a href="logout.php">Logout</a></li>
                            <?php } else { ?>
                                <li class="botao"><a href="./views/login.php">Login</a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </header>
            <div class="content">
                <div class="main-text">
                    <h6>Tendência</h6>
                    <h3>NetherRealm</h3>
                    <h1>Mortal Kombat</h1>
                    <p>Mortal Kombat 1 promete um “universo renascido” com versões reimaginadas dos personagens que o público adora. Um novo sistema de luta, modos de jogo e fatalities também foram confirmados. <br> Mortal Kombat 1 chega ao mercado em 19 de setembro para PC, PlayStation 5, Xbox Series X|S e Nintendo Switch.
                    </p>
                    <a href="https://www.youtube.com/watch?v=Ue1Yo-3QnRc">Assista Agora</a>
            </div>
            <div class="imagem">
                <img src="./IMG/mortal1.png">
            </div>
        </div>
    </section>

    <div class="new">
        <section class="main-new" id="lancamentos">
            <header class="main-new-header">
                <h1>Lançamentos do mês</h1>
            </header>

        <?php if($rows) { foreach ($rows as $row){ ?>

        <?php } } else { echo "<p>Não existem artigos cadastrados</p>"; } ?>

            <article>
                <div class="imagem1">
                <?php 
                    $path =  './IMG/'.$row['tipoImg'];
                    echo "<img alt='" . $row['titulo'] . "' src='$path'>";
                ?>
                    
                </div>
                <h2 class="titulo"><?= $row['titulo']?></h2>
                <p class="data"><?=$row['texto'];?></p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./IMG/f1.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">F1 2023</h2>
                <p class="data">Data: 16 de junho de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./IMG/motogp.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">Motogp 2023</h2>
                <p class="data">Data: 08 de junho de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./IMG/geraldao.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">The Witcher (parte 1 da 3ª temporada)</h2>
                <p class="data">Data: 29 de junho de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./IMG/prime.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">Transformers: O despertar das feras</h2>
                <p class="data">Data: 08 de junho de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./IMG/flash.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">The flash</h2>
                <p class="data">Data: 15 de junho de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./IMG/moon.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">Sailor Moon Cosmos</h2>
                <p class="data">Data: 09 de junho de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./IMG/clover.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">Black Clover: Sword of the Wizard King</h2>
                <p class="data">Data: 16 de junho de 2023</p>
            </article>
        </section>
    </div>

    <div class="artigo">
    <section class="main-artigo" id="games">
        <header class="main-artigo-header">
            <h1>Games</h1>
        </header>

        <article>
            <div class="imagem1">
                <a href="https://www.youtube.com/watch?v=MiJUm-J68d8">
                    <img src="./IMG/mk1.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">Mortal Kombat 1 recebe trailer de gameplay</h2>
            <p> Durante o Summer Game Fest 2023, foi apresentado o primeiro trailer de gameplay do Mortal Kombat 1. <br> O trailer apresentou diversos Fatalities sangrentos e também nos mostrou personagens como Liu kang, Sub-Zero, Scorpion e Raiden. Jean-Claude Van Dame foi anunciado como Johnny Cage, tanto como sua voz quanto como modelo para o personagem. <br> Também foi apresentado a introdução de golpes em dupla. <br> Mortal Kombat 1 foi revelado em maio, e será lançado para PlayStation 5, Xbox Series X/S, Nintendo Switch e PC no dia 19 de setembro de 2023.</p>
        </article> 
        <article>
            <div class="imagem1">    
                <a href="https://www.youtube.com/watch?v=bg140A2HPnk">
                    <img src="./IMG/aranha.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>    
            </div>
            <h2 class="titulo-artigo">Marvel's Spider Man 2 ganha data de lançamento</h2>
            <p> Marvel's Spider Man 2 ganha data de lançamento durante Summer Game Fest 2023. <br> Sendo um dos jogos mais aguardados do ano, a obra acompanha a história de Peter Parker e Miles Morales, tendo já confirmados Venom e Kraven como antagonistas. <br> A obra será um exclusivo para PS5, mas apesar de ser um exclusivo para os consoles da Sony, Marvel's Spider Man 2 pode ser lançado para PC futuramente. <br> A data de lançamento está prevista para o dia 20 de outubro de 2023.</p>
        </article>
        <article>
            <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <!-- <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
        <article>
            <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
    </div> -->

    <div class="artigo">
    <section class="main-artigo" id="fms">
        <header class="main-artigo-header">
            <h1>Filmes/Séries</h1>
        </header>

        <article>
            <div class="imagem1">
                <a href="https://www.youtube.com/watch?v=TN-_xS4Kdpg">
                    <img src="./IMG/geraldao.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">The Witcher recebe trailer com data para terceira temporada</h2>
            <p> Conforme anunciado nas redes sociais da Netflix, a nova temporada da série será dividida em duas partes. <br> A primeira parte, que incluirá os episódios de um ao cinco, será lançada no dia 29 de junho. já a segunda parte, que incluirá os episódios seis, sete e oito, será lançada no dia 27 de jullho.</p>
        </article> 
        <article>
            <div class="imagem1">    
            <a href="https://www.imdb.com/title/tt9362722/?ref_=nv_sr_srsg_0_tt_8_nm_0_q_spider">
                    <img src="./IMG/aranhaverso.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>    
            </div>
            <h2 class="titulo-artigo">Homem-Aranha: Através do Aranhaverso supera recorde de O Cavaleiro das Trevas</h2>
            <p> A animação da Marvel recebeu no IMDb nota 9.1, superando o Batman no ranking dos melhores filmes de super heróis. <br> Batman: O Cavaleiro das Trevas, de Christopher Nolan, é uma grande referência em termos de filmes de super-heróis. O longa detém um impressionante 9.0/10 no IMDb, estando em primeiro lugar no ranking dos melhores filmes do gênero. <br> Entretanto, Homem-aranha, através do aranhaverso ultrapassou o filme, assumindo a liderança com 9.1/10.</p>
        </article>
        <article>
           <!-- <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
             <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
        <article>
            <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
    </div> -->

    <div class="artigo">
    <section class="main-artigo" id="animes">
        <header class="main-artigo-header">
            <h1>Animes</h1>
        </header>

        <!-- <article>
            <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
        <article>
            <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
        <article>
            <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
        <article>
            <div class="imagem1">
                <a href="https://www.google.com">
                    <img src="./IMG/branco.png" width="500" alt="Imagem post" title="Imagem Post">
                </a>
            </div>
            <h2 class="titulo-artigo">Novas Notícias</h2>
            <p> Em breve, novas notícias!!!</p>
        </article>
    </div> -->
</body>
</html>