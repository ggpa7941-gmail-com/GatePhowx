<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <?php 
        # para trabalhar com sessões sempre iniciamos com session_start.
        session_start();
        
        # inclui o arquivo header e a classe de conexão com o banco de dados.
        require_once "../db/conexao.php";
        
        # verifica se existe sessão de usuario e se ele é administrador.
        # se não existir redireciona o usuario para a pagina principal com uma mensagem de erro.
        # sai da pagina.
        if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] != 'ADM') {
            header("Location: ./home.php?error=Usuário não tem permissão para acessar esse recurso");
            exit;
        }

        # verifica se uma variavel id foi passada via GET 
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        # cria a variavel $dbh que vai receber a conexão com o SGBD e banco de dados.
        $dbh = Conexao::getInstance();

        # verifica se os dados do formulario foram enviados via POST 
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            # cria variaveis (email, nome, perfil, status) para armazenar os dados passados via método POST.
            $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
            $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
            $genero = isset($_POST['genero']) ? $_POST['genero'] : '';
            $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
            $senha =md5($senha);

            # cria uma consulta banco de dados atualizando um usuario existente. 
            # usando como parametros os campos nome e password.
            $query = "UPDATE `gate`.`usuario` SET `nome` = :nome,
                        `email` = :email, `cidade` = :cidade, 
                        `estado` = :estado, `genero` = :sexo,
                        `senha` = :senha
                        WHERE id = :id";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':sexo', $genero);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':id', $id);
            
            # executa a consulta banco de dados para inserir o resultado.
            $stmt->execute();

            # verifica se a quantiade de registros inseridos é maior que zero.
            # se sim, redireciona para a pagina de admin com mensagem de sucesso.
            # se não, redireciona para a pagina de cadastro com mensagem de erro.
            if($stmt->rowCount()) {
                header('location: login.php?success=Usuário atualizado com sucesso!');
            } else {
                $error = $dbh->errorInfo();
                var_dump($error);
                header('location: userUpd.php?error=Erro ao atualizar o usuário!');
            }

            # destroi a conexao com o banco de dados.
            $dbh = null;
        }
        
        # cria uma consulta banco de dados buscando todos os dados da tabela usuarios 
        # filtrando pelo id do usuário.
        $query = "SELECT * FROM `gate`.`usuarios` WHERE id=:id LIMIT 1";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':id', $id);

        # executa a consulta banco de dados e aguarda o resultado.
        $stmt->execute();
        
        # Faz um fetch para trazer os dados existentes, se existirem, em um array na variavel $row.
        # se não existir retorna null
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        
        # se o resultado retornado for igual a NULL, redireciona para a pagina de listar usuario.
        # se não, cria a variavel row com dados do usuario selecionado.
        if(!$row){
            header('location: usuario_admin_list.php?error=Usuário inválido.');
        }
        
        # destroi a conexao com o banco de dados.
        $dbh = null;
    ?>
<body>
    <?php
        # verifca se existe uma mensagem de erro enviada via GET.
        # se sim, exibe a mensagem enviada no cabeçalho.
        if(isset($_GET['error'])) { ?>
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Usuários',
                text: '<?=$_GET['error'] ?>', })
                </script>
            <?php } ?>
            <div class="container">
    <div class="form-image">
            <img src="../IMG/logo10.png.gif">
    </div>
    <div class="form">
        <form onsubmit='senhaOK();' method="post">
            <div class="form-header">
            <div class="title">
                <h1>Cadastre-se</h1>
            </div>

            <button class="login-button"><a href="login.php">Entrar</a></button>

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
                        <label for="cidade">Cidade</label>
                        <input id="cidade" type="text" name="cidade" placeholder="Digite sua cidade" required>
                    </div>

                    <div class="input-box">
                        <label for="estado">Estado</label>
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
                            <div class="gender-input" style="margin-left: 15px;">
                                <input type="radio" id="feminino" name="genero" value="f">
                                <label for="feminino">Feminino</label>
                            </div>

                            <div class="gender-input" style="margin-left: 15px;">
                                <input type="radio" id="masculino" name="genero" value="m">
                                <label for="masculino">Masculino</label>
                            </div>

                            <div class="gender-input" style="margin-left: 15px;">
                                <input type="radio" id="outro" name="genero" value="o">
                                <label for="outro">Outro</label>
                            </div>

                            <div class="gender-input" style="margin-left: 15px;">
                                <input type="radio" id="none" name="genero" value="n"  checked>
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
