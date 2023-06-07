<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./_css/home.css">
    <title>Document</title>
</head>
<?php 
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
    
    # inclui o arquivo header
    
    
    # verifica se existe sessão de usuario e se ele é administrador.
    # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
    # sai da pagina.
    if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
        header("Location: ../login/login.php?error=Usuário não tem permissão para acessar esse recurso");
        exit;
    }
?>
<body>
    <form action="" method="post">
        <section class="hero">
            <div class="main-width">
                <header>
                    <div class="logo">
                        <h2><a href="#">GatePhowx</a></h2>
                    </div>

                    <nav>
                        <ul>
                            <li active><a href="#">Home</a></li>
                            <li><a href="#">Lançamentos</a></li>
                            <li><a href="#">Games</a></li>
                            <li><a href="#">filmes/séries</a></li>
                            <li><a href="#">Animes</a></li>
                            <li><a href="#">Perfil</a></li>
                            <?php if(isset($_SESSION['usuario']))  {?>
                                <li class="botao"><a href="../login/logout.php">Logout</a></li>
                                <?php } else { ?>
                                    <li class="botao"><a href="../login/login.php">Login</a></li>
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
                        <button><a href="../cadastro-c/cadastro-c.php">Editar</a></button>
                </div>
                <div class="imagem">
                    <img src="./_img/mortal1.png">
                </div>
            </div>
        </section>

        <div class="new">
        <section class="main-new">
            <header class="main-new-header">
                <h1>Lançamentos do mês</h1>
            </header>
            <button><a href="#">Editar</a></button>
            <article>
                <div class="imagem1">
                    <img src="./_img/zelda.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">The Legend of Zelda: Tears of the Kingdom</h2>
                <p class="data">Data: 12 de maio de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./_img/fast10.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">Velozes e Furiosos 10</h2>
                <p class="data">Data: 18 de maio de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./_img/starwars.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">Star Wars Visions Volume 2</h2>
                <p class="data">Data: 04 de maio de 2023</p>
            </article>
            <article>
                <div class="imagem1">
                    <img src="./_img/warhammer.png" width="200" alt="Imagem post" title="Imagem Post">
                </div>
                <h2 class="titulo">Warhammer 40k Boltgun</h2>
                <p class="data">Data: 23 de maio de 2023</p>
            </article>
        </section>
    </form>
</body>
</html>