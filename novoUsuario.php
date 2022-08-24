<?php
include "conectaBanco.php";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <tittle>Agenda de contatos</tittle>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <!-- scripts de validação java script (o último é o script que contém o 
        medidor se a senha é forte ou fraca)-->
    <script src="js/jquery-3.3.1.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/messages_pt_BR.js"></script>
    <script src="js/pwstrength-bootstrap.js"></script>

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
    </style>

</head>

<body>
    <!--no style do boddy, overflow-x: hidden oculta a barra de rolagem -->
    <!-- No head usamos center fixed para a imagem de fundo ficar fixa ao rolar a página -->
    <!-- usar classe container em uma div principal para usar o bootstrap -->
    <!--essa classe h-100 expande a altura da div para 100%-->
    <div class="h-100 row align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-sm"></div>
                <div class="col-sm-10">
                    <?php
                    $flagErro = False;
                    if (isset($_POST['acao'])) {
                        $acao = $_POST['acao'];

                        if ($acao == 'salvar') {
                            $nomeUsuario = $_POST['nomeUsuario'];
                            $mailUsuario = $_POST['mailUsuario'];
                            $mail2Usuario = $_POST['mail2Usuario'];
                            $senhaUsuario = $_POST['senhaUsuario'];
                            $senha2Usuario = $_POST['senha2Usuario'];


                            if (!empty($nomeUsuario) && !empty($mailUsuario) && !empty($mail2Usuario)
                                && !empty($senhaUsuario) && !empty($senha2Usuario)) {

                                if ($mailUsuario == $mail2Usuario && $senhaUsuario == $senha2Usuario) {
                                    if (strlen($nomeUsuario) >= 5 && strlen($senhaUsuario) >= 8) {

                                        $sqlUsuarios = "SELECT codigoUsuario FROM usuarios WHERE mailUsuario= :mailUsuario";

                                        $sqlUsuariosST = $conexao->prepare($sqlUsuarios);

                                        $sqlUsuariosST->bindValue(':mailUsuario', $mailUsuario);

                                        $sqlUsuariosST->execute();

                                        $quantidadeUsuarios = $sqlUsuariosST->rowCount();

                                        if ($quantidadeUsuarios == 0) {
                                            $senhaUsuarioMD5 = md5($senhaUsuario);

                                            $sqlNovoUsuario = "INSERT INTO usuarios (nomeUsuario, mailUsuario, senhaUsuario) VALUES 
                                                            (:nomeUsuario, :mailUsuario, :senhaUsuario)";

                                            $sqlNovoUsuarioST = $conexao->prepare($sqlNovoUsuario);
                                            $sqlNovoUsuarioST->bindValue(':nomeUsuario', $nomeUsuario);
                                            $sqlNovoUsuarioST->bindValue(':mailUsuario', $mailUsuario);
                                            $sqlNovoUsuarioST->bindValue(':senhaUsuario', $senhaUsuarioMD5);

                                            if ($sqlNovoUsuarioST->execute()) {
                                                $mensagemAcao = "Novo usuário cadastrado com sucesso";
                                            } else {
                                                $flagErro = True;
                                                $mensagemAcao = "Código erro: " . $sqlNovoUsuarioST->errorCode();
                                            }
                                        } else {
                                            $flagErro = True;
                                            $mensagemAcao = "E-mail já cadastrado";
                                        } 
                                    } else{
                                        $flagErro = True;
                                        $mensagemAcao = "Quantidade mínima de caracteres não atingida: nome (5), senha (8)";
                                    }
                                } else {
                                    $flagErro = True;
                                    $mensagemAcao = "Os campos de confirmação não se correspondem";
                                }
                                } else {
                                    $flagErro = True;
                                    $mensagemAcao = "Preencha todos os campos obrigatórios (*)";
                            }



                            if (!$flagErro) {
                                $classeMensagem = "alert-success";
                            } else {
                                $classeMensagem = "alert-danger";
                            }

                            echo "<div class=\"alert $classeMensagem alert-dismissible fade show\" role=\"alert\">
                                    $mensagemAcao
                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Fechar\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                 </div>";
                        }
                    }
                    ?>
                    <!--border -primary deixou as bordas do card azul-->
                    <div class="card border-primary">
                        <!-- cria-se uma div para o componente card do bootstrap -->
                        <div class="card-header bg-primary text-white">
                            <h5>Cadastro de novo usuário</h5>
                            <!-- elemento alt serve para a acessibilidade da página, com descrição da imagem para deficientes visuais -->
                        </div>
                        <div class="card-body">
                            <form id="novoUsuario" method="post" action="novoUsuario.php">
                                <input type="hidden" name="acao" value="salvar">
                                <!--esse mb- determina o espaçamento entres os campos. O número determina o tamanho do espaçamento (3 é o padrão)-->
                                <div class="form-group">
                                    <label for="nomeUsuario">Nome*</label>
                                    <!-- a div abaixo serve pra adicionar um ícone ao campo do formulário -->
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <!--class bi adiciona um ícone ao campo, person-fill é o nome do ícone-->
                                            <div class="input-group-text"><i class="bi-person-fill"></i></div>
                                        </div>
                                        <!-- required deixa o campo com preenchimento obrigatório-->
                                        <input type='text' class="form-control" id="nomeUsuario" name="nomeUsuario" placeholder="Digite seu nome" value="<?= ($flagErro) ? $nomeUsuario : "" ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="mailUsuario">E-mail*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-at"></i></div>
                                                </div>
                                                <input type='email' class="form-control" id="mailUsuario" name="mailUsuario" placeholder="Digite seu e-mail" value="<?= ($flagErro) ? $mailUsuario : "" ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="mail2Usuario">Repita o e-mail*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-at"></i></div>
                                                </div>
                                                <input type='email' class="form-control" id="mail2Usuario" name="mail2Usuario" placeholder="Repita seu e-mail" value="<?= ($flagErro) ? $mail2Usuario : "" ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="senhaUsuario">Senha*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-key-fill"></i></div>
                                                </div>
                                                <input type='password' class="form-control" id="senhaUsuario" name="senhaUsuario" placeholder="Digite uma senha" value="<?= ($flagErro) ? $senhaUsuario : "" ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="senha2Usuario">Repita a senha*</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="bi-key-fill"></i></div>
                                                </div>
                                                <input type='password' class="form-control" id="senha2Usuario" name="senha2Usuario" placeholder="Repita a senha" value="<?= ($flagErro) ? $senha2Usuario : "" ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="campo_senha">
                                    <div class="col-sm barra_senha"></div>
                                    <div class="col-sm"></div>
                                </div>
                                <div class="row ">
                                    <div class="col-sm text-right">
                                        <button type="submit" class="btn btn-primary">Cadastrar</button>
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
            $("#novoUsuario").validate({
                rules: {
                    nomeUsuario: {
                        minlength: 5
                    },
                    mail2Usuario: {
                        equalTo: "#mailUsuario"
                    },
                    senha2Usuario: {
                        equalTo: "#senhaUsuario"
                    },
                    senhaUsuario: {
                        minlength: 8
                    }
                }
            });

            jQuery(document).ready(function() {
                "use strict";
                var options = {};
                options.ui = {
                    container: "#campo_senha",
                    viewports: {
                        progress: ".barra_senha"
                    },
                    showVerdictsInsideProgressBar: true
                };
                $('#senhaUsuario').pwstrength(options);
            });
        });
    </script>
    <!-- a função ready acima serve para validação do código como campo obrigatório-->
</body>


</html>