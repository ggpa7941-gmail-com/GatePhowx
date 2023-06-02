<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./_css/cadastro-c.css">
    <title>Cadastro de conteúdo</title>
</head>
<body>
    <div class="center">
        <h1>Cadastro de conteúdo</h1>
        <form method="post">
            <div class="url">
                <input type="url" name="url" pattern="https://.*">
                <span></span>
                <label>Link</label>
            </div>

                <textarea class="txtarea" name="area" placeholder="Texto" cols="30" rows="10"></textarea>
                <span></span>
                <!-- <label>Texto</label> -->

                <input type="file" id="uploadbtn" accept=".jpg, .gif, .png">
                <label for="uploadbtn" class="uploadBtn">Escolher Arquivo</label>

            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
</html>