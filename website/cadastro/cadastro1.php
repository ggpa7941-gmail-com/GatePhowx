<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./_css/cadastro1.css">
    <title>Cadastro</title>
</head>
<?php
    # para trabalhar com sessões sempre iniciamos com session_start.
    session_start();
   
    # inclui o arquivo header e a classe de conexão com o banco de dados.
    require_once "../db/conexao.php";
   
    # verifica se os dados do formulario foram enviados via POST
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
        $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
        $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
        $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
        $perfil = 'USU';
        $senha =md5($senha);
        // $status = 1;
       
        // echo '<pre>';var_dump($_POST); exit;
        # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
        $dbh = Conexao::getInstance();



        
        # cria uma consulta banco de dados verificando se o usuario existe
        # usando como parametros os campos nome e password.
        $query = "INSERT INTO `gate`.`usuario` (`nome`,`email`, `sexo`, `senha`, `perfil`)
                    VALUES ( :nome, :email, :sexo, :senha, :perfil)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':perfil', $perfil);
        $stmt->bindParam(':senha', $senha);




        # executa a consulta banco de dados para inserir o resultado.
        $stmt->execute();


        $idUsuario = $dbh->lastInsertId();
        

        $query = "INSERT INTO `gate`.`endereco` (`cidade`, `estado`, `usuario_id_user`)
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
    <div class="container">
        <div class="form-image">
            <img src="./_img/logo10.png.gif">
        </div>
        <div class="form">
            <form onsubmit='senhaOK();' method="post">
                <div class="form-header">
                    <div class="title">
                        <h1>Cadastre-se</h1>
                    </div>

                    <button class="login-button"><a href="../login/login.php">Entrar</a></button>

                </div>

                <div class="input-group">
                    <div class="input-box">
                        <label for="nome">Nome</label>
                        <input id="nome" type="text" name="nome" placeholder="Digite seu nome" required>
                    </div>

                    <div class="input-box">
                        <label for="nome">Email</label>
                        <input id="email" type="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-box">
                        <label>Cidade</label>
                        <input id="cidade" type="text" name="cidade" placeholder="Digite sua cidade" required>
                    </div>

                    <div class="select-container">
                        <select class="select-box" id="estado" name="estado" required>
                            <option value="">Selecione seu estado</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                            <option value="EX">Estrangeiro</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha</label>
                        <input id="senha" type="password" name="senha" onchange='confereSenha();' placeholder="Digite sua senha" required>
                    </div>

                    <div class="input-box">
                        <label for="confSenha">Confirme sua senha</label>
                        <input id="confSenha" type="password" name="confSenha" onchange='confereSenha();' placeholder="Confirme sua senha" required>
                    </div>

                    <div class="gender-inputs">
                        <div class="gender-title">
                            <h6>Gênero</h6>
                        </div>

                        <div class="gender-group">
                            <div class="gender-input">
                                <input type="radio" id="feminino" name="genero" value="f">
                                <label for="feminino">Feminino</label>
                            </div>

                            <div class="gender-input">
                                <input type="radio" id="masculino" name="genero" value="m">
                                <label for="masculino">Masculino</label>
                            </div>

                            <div class="gender-input">
                                <input type="radio" id="outro" name="genero" value="o">
                                <label for="outro">Outro</label>
                            </div>

                            <div class="gender-input">
                                <input type="radio" id="none" name="genero" value="n">
                                <label for="none">Prefiro não informar</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="continue-button">
                    <button type="submit" class="btn_format">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function confereSenha(){
            const senha = document.querySelect('input[name=senha]');
            const confSenha = document.querySelect('input{name=confSenha]');
                if(confSenha.value === senha.value){
                    confSenha.setCustomValidity('');
                }else{
                    confSenha.setCustomValidity('As Senhas Não Conferem');
                }
            function senhaOK(){
                alert("Senha Conferem")
            }    
        }
    </script>

</body>
</html>