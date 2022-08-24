<?php
session_start();

$verificaUsuarioLogado = $_SESSION['verificaUsuarioLogado'];

if (!$verificaUsuarioLogado) {
    header("Location: index.php?codMsg=003");
} else {
    include "conectaBanco.php";
    include "common/formataData.php";

    $codigoUsuarioLogado = $_SESSION['codigoUsuarioLogado'];
    $nomeUsuarioLogado = $_SESSION['nomeUsuarioLogado'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <tittle>Agenda de contatos</tittle>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/messages_pt_BR.js"></script>
    <script src="js/dateITA.js"></script>
    <script src="js/jquery.mask.js"></script>

    <style>
        html {
            height: 100%;
        }

        body {
            background: url('img/dark-blue-background.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100%;
            overflow-x: hidden;
        }

        .custom-file-input~.custom-file-label::after {
            content: "Selecionar";
        }
    </style>
    <!--o after significa que depois dessa classe, vai alterar o conteúdo de brownser para selecionar -->

</head>

<body>
    <nav class=" navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img/icone.svg" width="30" height="30" alt="Agenda de contatos">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <!-- lista não ordenada (ul)-->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="menuCadastros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi-card-list"></i> Cadastros</a>
                        <div class="dropdown-menu" aria-labelledby="menuCadastros">
                            <a class="dropdown-item" href="cadastroContato.php"><i class="bi-person-fill"></i> Novo
                                contato</a>
                            <a class="dropdown-item" href="listaContatos.php"><i class="bi-list-ul"></i> Lista de
                                contatos</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="menuConta" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi-gear-fill"></i> Minha conta</a>
                        <div class="dropdown-menu" aria-labelledby="menuConta">
                            <a class="dropdown-item" href="alterarDados.php"><i class="bi-pencil-square"></i> Alterar
                                dados</a>
                            <a class="dropdown-item" href="logout.php"><i class="bi-door-open-fill"></i> Sair</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#modalSobreAplicacao">
                            <i class="bi-info-circle"> </i> Sobre</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" method="get" action="listaContatos.php">
                    <input class="form-control mr-sm-2" type="search" name="busca" placeholder="Pesquisar">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Pesquisar</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="h-100 row align-items-center pt-5">
        <div class="container">
            <div class="row">
                <div class="col-sm"></div>
                <!--o conteúdo do container é dividido em 12 colunas, onde aqui o formulário ocupa as 12-->
                <div class="col-sm-12">
                    <?php
                    $flagErro = False;
                    $flagSucesso = False;
                    $mostrarMensagem = False;

                    $dadosContato = array(
                        'codigoContato', 'nomeContato', 'nascimentoContato',
                        'sexoContato', 'mailContato', 'fotoContato', 'fotoAtualContato', 'telefone1Contato',
                        'telefone2Contato', 'telefone3Contato', 'telefone4Contato', 'logradouroContato',
                        'complementoContato', 'bairroContato', 'estadoContato', 'cidadeContato'
                    );

                    foreach ($dadosContato as $campo) {
                        $$campo = "";
                    }

                    if (isset($_POST['codigoContato'])) { //formulário submetido (salvar)

                        $codigoContato = $_POST['codigoContato'];
                        $nomeContato = addslashes($_POST['nomeContato']);
                        $nascimentoContato = $_POST['nascimentoContato'];

                        if (isset($_POST['sexoContato'])) {
                            $sexoContato = $_POST['sexoContato'];
                        } else {
                            $sexoContato = "";
                        }
                        $mailContato = $_POST['mailContato'];
                        $fotoContato = $_FILES['fotoContato'];
                        $fotoAtualContato = $_POST['fotoAtualContato'];
                        $telefone1Contato = $_POST['telefone1Contato'];
                        $telefone2Contato = $_POST['telefone2Contato'];
                        $telefone3Contato = $_POST['telefone3Contato'];
                        $telefone4Contato = $_POST['telefone4Contato'];
                        $logradouroContato = addslashes($_POST['logradouroContato']);
                        $complementoContato = addslashes($_POST['complementoContato']);
                        $bairroContato = addslashes($_POST['bairroContato']);
                        $estadoContato = $_POST['estadoContato'];
                        $cidadeContato = $_POST['cidadeContato'];

                        $telefonesContato =  array(
                            $telefone1Contato, $telefone2Contato,
                            $telefone3Contato, $telefone4Contato
                        );
                        //a seguir filtro os telefones em branco e após isso, aplico a mascara                  
                        $telefonesFiltradosContato = array_filter($telefonesContato);
                        $telefonesValidadosdosContato = preg_grep('/^\(\d{2}\)\s\d{4,5}\-\d{4}$/', $telefonesContato);

                        if ($telefonesFiltradosContato === $telefonesValidadosdosContato) {
                            $erroTelefones = False;
                        } else {
                            $erroTelefones = True;
                        }

                        if (
                            empty($nomeContato) || empty($sexoContato) || empty($mailContato) ||
                            empty($telefone1Contato) || empty($logradouroContato) || empty($complementoContato) ||
                            empty($bairroContato) || empty($cidadeContato) || empty($estadoContato)
                        ) {

                            $flagErro = True;
                            $mensagemAcao = "Preencha todos os campos obrigatórios (*)";
                        } else if (strlen($nomeContato) < 5) {
                            $flagErro = True;
                            $mensagemAcao = "O campo nome precisa de pelo menos 5 caracteres";
                        } else if (!preg_match(
                            '/^(0?[1-9]|[1,2][0-9]|3[0,1])[\/](0?[1-9]|1[0,1,2])[\/]\d{4}$/',
                            $nascimentoContato
                        )) { //validação data de nascimento
                            $flagErro = True;
                            $mensagemAcao = "A data de nascimento do contato deve ser no formato DD/MM/AAAA";
                        } else if (!preg_match(
                            "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/",
                            $mailContato
                        )) {
                            $flagErro = True;
                            $mensagemAcao = "Verifique o e-mail informado";
                        } else if ($fotoContato['error'] != 4) { //validação da foto
                            if (
                                !in_array($fotoContato['type'], array('image/jpg', 'image/jpeg', 'image/png')) ||
                                $fotoContato['size'] > 2000000
                            ) {
                                //esse 2kk representa 2 mb em bytes
                                $flagErro = True;
                                $mensagemAcao = 'A foto do contato deve ser nos formatos JPG, JPEG ou PNG e ter no máximo 2MB';
                            } else {
                                list($larguraFoto, $alturaFoto) = getimagesize($fotoContato['tmp_name']);
                                // 500 e 200 são em pixels da foto
                                if ($larguraFoto > 200 || $alturaFoto > 500) {
                                    $flagErro = True;
                                    $mensagemAcao = "As dimensões da foto devem ser de no máximo 500x200 pixels";
                                }
                            }
                        } else if ($erroTelefones) { //validação do telefone
                            $flagErro = True;
                            $mensagemAcao = 'Os campos de telefone devem ser no formato (xx)xxxxx-xxxx';
                        }

                        //empty verifica se a variavel está vazia

                        // o ! significa "se flagerro == false
                        if (!$flagErro) {
                            if (empty($codigoContato)) { //se estiver vazia é inclusão de contato
                                $sqlContato = "INSERT INTO contatos (codigoUsuario, nomeContato, nascimentoContato, 
                                                sexoContato, mailContato, fotoContato, telefone1Contato, 
                                                telefone2Contato, telefone3Contato, telefone4Contato, logradouroContato,
                                                complementoContato, bairroContato, cidadeContato, estadoContato) VALUES (:codigoUsuario, 
                                                :nomeContato, :nascimentoContato, :sexoContato, :mailContato, :fotoContato, :telefone1Contato, 
                                                :telefone2Contato, :telefone3Contato, :telefone4Contato, :logradouroContato,
                                                :complementoContato, :bairroContato, :cidadeContato, :estadoContato)";

                                $sqlContatoST = $conexao->prepare($sqlContato);
                                $sqlContatoST->bindValue(':codigoUsuario', $codigoUsuarioLogado);
                                $sqlContatoST->bindValue(':nomeContato', $nomeContato);

                                $nascimentoContato = formataData($nascimentoContato);
                                $sqlContatoST->bindValue(':nascimentoContato', $nascimentoContato);

                                $sqlContatoST->bindValue(':sexoContato', $sexoContato);
                                $sqlContatoST->bindValue(':mailContato', $mailContato);
                                $sqlContatoST->bindValue(':telefone1Contato', $telefone1Contato);
                                $sqlContatoST->bindValue(':telefone2Contato', $telefone2Contato);
                                $sqlContatoST->bindValue(':telefone3Contato', $telefone3Contato);
                                $sqlContatoST->bindValue(':telefone4Contato', $telefone4Contato);
                                $sqlContatoST->bindValue(':logradouroContato', $logradouroContato);
                                $sqlContatoST->bindValue(':complementoContato', $complementoContato);
                                $sqlContatoST->bindValue(':bairroContato', $bairroContato);
                                $sqlContatoST->bindValue(':cidadeContato', $cidadeContato);
                                $sqlContatoST->bindValue(':estadoContato', $estadoContato);

                                if ($fotoContato['error'] == 0) {
                                    $extensaoFoto = pathinfo($fotoContato['name'], PATHINFO_EXTENSION);
                                    //gera um nome para a foto com dia, mês e ano, hora, segundos e minutos c+ codigo do usuário na sessão + extensão da foto
                                    $nomeFoto = "fotos/" . strtotime(date("Y-m-d H:i:s")) . $codigoUsuarioLogado . '.' . $extensaoFoto;

                                    if (copy($fotoContato['tmp_name'], $nomeFoto)) {
                                        $fotoEnviada = True;
                                    } else {
                                        $fotoEnviada = False;
                                    }

                                    $sqlContatoST->bindValue(':fotoContato', $nomeFoto);
                                } else {
                                    $sqlContatoST->bindValue(':fotoContato', '');
                                    $fotoEnviada = False;
                                }

                                if ($sqlContatoST->execute()) {
                                    $flagSucesso = True;
                                    $mensagemAcao = "Novo contato cadastrado com sucesso";
                                } else {
                                    $flagErro = True;
                                    $mensagemAcao = "Houve um erro ao cadastrar novo contato. Código do erro: $sqlContatoST->errorCode()";

                                    $nascimentoContato = formataData($nascimentoContato);

                                    if ($fotoEnviada) {
                                        unlink($nomeFoto);
                                    }
                                }
                            } else { //edição de contato já existente 
                                $sqlContato = "UPDATE contatos SET nomeContato=:nomeContato, nascimentoContato=:nascimentoContato, 
                                                sexoContato=:sexoContato, mailContato=:mailContato, fotoContato=:fotoContato, telefone1Contato=:telefone1Contato, 
                                                telefone2Contato=:telefone2Contato, telefone3Contato=:telefone3Contato, telefone4Contato=:telefone4Contato, logradouroContato=:logradouroContato,
                                                complementoContato=:complementoContato, bairroContato=:bairroContato, cidadeContato=:cidadeContato, estadoContato=:estadoContato 
                                                WHERE codigoContato=:codigoContato AND codigoUsuario=:codigoUsuario";

                                $sqlContatoST = $conexao->prepare($sqlContato);
                                $sqlContatoST->bindValue(':codigoContato', $codigoContato);
                                $sqlContatoST->bindValue(':codigoUsuario', $codigoUsuarioLogado);
                                $sqlContatoST->bindValue(':nomeContato', $nomeContato);

                                $nascimentoContato = formataData($nascimentoContato);
                                $sqlContatoST->bindValue(':nascimentoContato', $nascimentoContato);

                                $sqlContatoST->bindValue(':sexoContato', $sexoContato);
                                $sqlContatoST->bindValue(':mailContato', $mailContato);
                                $sqlContatoST->bindValue(':telefone1Contato', $telefone1Contato);
                                $sqlContatoST->bindValue(':telefone2Contato', $telefone2Contato);
                                $sqlContatoST->bindValue(':telefone3Contato', $telefone3Contato);
                                $sqlContatoST->bindValue(':telefone4Contato', $telefone4Contato);
                                $sqlContatoST->bindValue(':logradouroContato', $logradouroContato);
                                $sqlContatoST->bindValue(':complementoContato', $complementoContato);
                                $sqlContatoST->bindValue(':bairroContato', $bairroContato);
                                $sqlContatoST->bindValue(':cidadeContato', $cidadeContato);
                                $sqlContatoST->bindValue(':estadoContato', $estadoContato);

                                if ($fotoContato['error'] == 0) {
                                    $extensaoFoto = pathinfo($fotoContato['name'], PATHINFO_EXTENSION);
                                    //gera um nome para a foto com dia, mês e ano, hora, segundos e minutos c+ codigo do usuário na sessão + extensão da foto
                                    $nomeFoto = "fotos/" . strtotime(date("Y-m-d H:i:s")) . $codigoUsuarioLogado . '.' . $extensaoFoto;

                                    if (copy($fotoContato['tmp_name'], $nomeFoto)) {
                                        $fotoEnviada = True;
                                    } else {
                                        $fotoEnviada = False;
                                    }

                                    $sqlContatoST->bindValue(':fotoContato', $nomeFoto);
                                } else {
                                    $sqlContatoST->bindValue(':fotoContato', $fotoAtualContato);
                                    $fotoEnviada = False;
                                }

                                if ($sqlContatoST->execute()) {
                                    if($fotoEnviada && !empty($fotoAtualContato)){
                                        unlink($fotoAtualContato);
                                    }

                                    $flagSucesso = True;
                                    $mensagemAcao = "Contato editado com sucesso";

                                    $nascimentoContato = formataData($nascimentoContato);
                                } else {
                                    $flagErro = True;
                                    $mensagemAcao = "Houve um erro ao editar novo contato. Código do erro: $sqlContatoST->errorCode()";

                                    $nascimentoContato = formataData($nascimentoContato);

                                    if ($fotoEnviada) {
                                        unlink($nomeFoto);
                                    }
                                }
                            }
                        }
                    } else { //carregar dados
                        if (isset($_GET['codigoContato'])) { //abrir contato já existente
                            $codigoContato = $_GET['codigoContato'];

                            $sqlContato = "SELECT * FROM contatos WHERE codigoContato=:codigoContato AND codigoUsuario=:codigoUsuario";
                            $sqlContatoST = $conexao->prepare($sqlContato);
                            $sqlContatoST-> bindValue(':codigoContato', $codigoContato  );
                            $sqlContatoST-> bindValue(':codigoUsuario', $codigoUsuarioLogado );
                            $sqlContatoST->execute();

                            $quantidadeContatos = $sqlContatoST->rowCount();
                            
                            if($quantidadeContatos == 1){
                                $resultadoContato = $sqlContatoST->fetchAll();

                                list($codigoContato, $codigoUsuario, $nomeContato, $nascimentoContato, $sexoContato,
                                        $mailContato, $fotoContato, $telefone1Contato, $telefone2Contato, $telefone3Contato,
                                        $telefone4Contato, $logradouroContato, $complementoContato, $bairroContato, $estadoContato,
                                            $cidadeContato) = $resultadoContato[0];

                                $fotoAtualContato = $fotoContato;
                                
                                $nascimentoContato = formataData($nascimentoContato);
                            }   else{
                                $flagErro = True;
                                $mensagemAcao = "Contato não cadastrado";
                            }
                        }
                    }
                    //implementação da flag de erro ou sucesso
                    if ($flagErro) {
                        $classeMensagem = "alert-danger";
                        $mostrarMensagem = True;
                    } else if ($flagSucesso) {
                        $classeMensagem = "alert-success";
                        $mostrarMensagem = True;
                    }

                    if ($mostrarMensagem) {
                        echo "<div class=\"alert $classeMensagem alert-dismissible fade show my-5\" role=\"alert\">
                                    $mensagemAcao
                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                 </div>";
                    }
                    ?>
                    <!--border -primary deixou as bordas do card azul-->
                    <!--my-5 é um espaçamento de margem supeior e inferior do card-->
                    <div class="card border-primary my-5">
                        <!-- cria-se uma div para o componente card do bootstrap -->
                        <div class="card-header bg-primary text-white">
                            <h5>Cadastro de contato</h5>
                            <!-- elemento alt serve para a acessibilidade da página, com descrição da imagem para deficientes visuais -->
                        </div>
                        <div class="card-body">
                            <form id="cadastroContato" method="post" enctype="multipart/form-data" action="cadastroContato.php">
                                <input type="hidden" name="codigoContato" value="<?= $codigoContato ?>">
                                <input type="hidden" name="fotoAtualContato" value="<?= $fotoAtualContato ?>">
                                <h5 class="text-primary">Dados pessoais</h5>
                                <hr>
                                <!-- essa coluna é a da esquerda -->
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nomeContato">Nome*</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-person-circle"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control" type="text" name="nomeContato" id="nomeContato" placeholder="Digite um nome" value="<?= $nomeContato ?>" required>
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="nascimentoContato">Data de nascimento</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-calendar-date"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control" type="text" name="nascimentoContato" id="nascimentoContato" placeholder="DD/MM/AAAA" value="<?= $nascimentoContato ?>">
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="sexoContato">Sexo*</label>
                                                    <div class="input-group">
                                                        <div class="form-check form-check-inline">
                                                            <?php
                                                            if ($sexoContato == 'M') {
                                                                $checkedMasculino = 'checked';
                                                                $checkedFeminino = '';
                                                            } else if ($sexoContato == 'F') {
                                                                $checkedFeminino = 'checked';
                                                                $checkedMasculino = '';
                                                            } else {
                                                                $checkedMasculino = '';
                                                                $checkedFeminino = '';
                                                            }
                                                            ?>
                                                            <input class="form-check-input" type="radio" name="sexoContato" value="M" <?= $checkedMasculino ?> id="sexoMasculino">
                                                            <label class="form-check-label" for="sexoMasculino">Masculino</label>
                                                            <!-- espaço em branco no html-->
                                                            &nbsp; &nbsp;
                                                            <input class="form-check-input" type="radio" name="sexoContato" value="F" <?= $checkedFeminino ?> id="sexoFeminino">
                                                            <label class="form-check-label" for="sexoFeminino">Feminino</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="mailContato">E-mail*</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-at"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control" type="email" name="mailContato" id="mailContato" placeholder="Digite um e-mail" value="<?= $mailContato ?>" required>
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- essa coluna é a da direita -->
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="row">
                                                    <div class="col-sm">
                                                        <div class="form-group">
                                                            <label for="fotoContato">Foto</label>
                                                            <div class="input-group">
                                                                <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <!-- tag <i> adiciona o ícone -->
                                                                        <i class="bi-file-earmark-person"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="custom-file">
                                                                    <input class="custom-file-input" type="file" name="fotoContato" id="fotoContato">
                                                                    <label class="custom-file-label" for="fotoContato">Escolha uma foto...</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <h5 class="text-primary">Telefones</h5>
                                <hr>
                                <div class="row">
                                    <!-- essa coluna é a da esquerda -->
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone1Contato">Telefone*</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control mascara-telefone" type="text" name="telefone1Contato" id="telefone1Contato" placeholder="(xx) xxxxx-xxxx" value="<?= $telefone1Contato ?>" required>
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone2Contato">Telefone</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control mascara-telefone" type="text" name="telefone2Contato" id="telefone2Contato" placeholder="(xx) xxxxx-xxxx" value="<?= $telefone2Contato ?>">
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- essa coluna é a da direita -->
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone3Contato">Telefone</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control mascara-telefone" type="text" name="telefone3Contato" id="telefone3Contato" placeholder="(xx) xxxxx-xxxx" value="<?= $telefone3Contato ?>">
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="telefone4Contato">Telefone</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control mascara-telefone" type="text" name="telefone4Contato" id="telefone4Contato" placeholder="(xx) xxxxx-xxxx" value="<?= $telefone4Contato ?>">
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="text-primary">Endereço</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="logradouroContato">Logradouro*</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-map"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control" type="text" name="logradouroContato" id="logradouroContato" placeholder="Rua, avenida, travessa e outros" value="<?= $logradouroContato ?>" required>
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="complementoContato">Complemento*</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-phone"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control" type="text" name="complementoContato" id="complementoContato" placeholder="Número, quadra, lote e outros" value="<?= $complementoContato ?>">
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="estadoContato">Estado*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-globe"></i>
                                                            </div>
                                                        </div>
                                                        <select class="form-control" name="estadoContato" id="estadoContato" required>
                                                            <option value="">Escolha o estado</option>

                                                            <!-- -------------------------------------------------------------------------PHP------------------------------------------------------------------------------------- -->
                                                            <?php
                                                            $sqlEstados = "SELECT codigoEstado, nomeEstado FROM estados";
                                                            /*fetchALL uma matriz os dados armazenados em sqlEstados e manda pro vetor resultadoEstados*/
                                                            $resultadoEstados = $conexao->query($sqlEstados)->fetchALL();
                                                            /*foreach percorre todo o vetor ou matriz e joga a primeira linha dentro de cod estado e a segunda em nome estado*/
                                                            foreach ($resultadoEstados as list($codigoEstado, $nomeEstado)) {
                                                                if ($estadoContato == $codigoEstado) {
                                                                    $selected = 'selected';
                                                                } else {
                                                                    $selected = "";
                                                                }
                                                                /*O /n é quebra de linha e o /t é tabulação (distancia da margem) */
                                                                echo "<option value=\"$codigoEstado\" $selected>$nomeEstado</option> \n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="bairroContato">Bairro*</label>
                                                    <div class="input-group">
                                                        <!-- input-group pretend concatena o ícone com a caixinha (input) -->
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <!-- tag <i> adiciona o ícone -->
                                                                <i class="bi-globe"></i>
                                                            </div>
                                                        </div>
                                                        <!-- required deixa o campo como obrigatório a ser preenchido -->
                                                        <input class="form-control" type="text" name="bairroContato" id="bairroContato" placeholder="Digite o bairro" value="<?= $bairroContato ?>">
                                                        <!-- lembrando que placeholder é para aparecer aquela frase dentro do input -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="cidadeContato">Cidade*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="bi-globe"></i>
                                                            </div>
                                                        </div>
                                                        <select class="form-control" name="cidadeContato" id="cidadeContato" required>
                                                            <option value="">Escolha a cidade</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ">
                                    <div class="col-sm text-right">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalSobreAplicacao" tabindex="-1" role="dialog" aria-labelledby="sobreAplicacao" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sobreAplicacao">Sobre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="img/logo.jpg">
                    <hr>
                    <p>Agenda de contatos</p>
                    <p>Versão 1.0</p>
                    <p>Todos os direitos reservados &copy; 2021</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery.validator.setDefaults({
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, erroClass, ValidClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $(document).ready(function() {
            $("#cadastroContato").validate({
                rules: {
                    nomeContato: {
                        minlength: 5
                    },
                    nascimentoContato: {
                        dateITA: true
                    },
                    sexoContato: {
                        required: true
                    }
                }
            });
            $("#nascimentoContato").mask("00/00/0000");


            var SPMaskBehavior = function(val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('.mascara-telefone').mask(SPMaskBehavior, spOptions);

            $("#estadoContato").change(function() {
                $("#cidadeContato").html('<option>Carregando...</option>');
                $("#cidadeContato").load('listaCidades.php?codigoEstado=' + $("#estadoContato").val());
            });

            <?php
            if (!empty($estadoContato) && !empty($cidadeContato)) {
                echo "$(\"#cidadeContato\").html('<option>Carregando...</option>');
                    $(\"#cidadeContato\").load('listaCidades.php?codigoEstado="
                    . $estadoContato . "&codigoCidade=" . $cidadeContato . "');";
            }


            ?>
        });
    </script>
    <!-- Acima está sendo aplicada a máscara na data de nascimento-->
</body>


</html>