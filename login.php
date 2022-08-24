<?php
    //início da sessão:
    session_start();
    if (isset($_POST['mailUsuario']) && isset($_POST['senhaUsuario'])) {
       include 'conectaBanco.php';
       //bloco de código para verificar se existe um e-mail e senha no banco
       $mailUsuario = $_POST['mailUsuario'];
       $senhaUsuario = $_POST['senhaUsuario'];
       $senhaUsuario = MD5($senhaUsuario);
 
       $sqlUsuario = "SELECT codigoUsuario, nomeUsuario FROM usuarios WHERE mailUsuario=:mailUsuario AND 
                            senhaUsuario=:senhaUsuario LIMIT 1";
       $sqlUsuarioST = $conexao->prepare($sqlUsuario);

       $sqlUsuarioST->bindValue(':mailUsuario', $mailUsuario);
       $sqlUsuarioST->bindValue(':senhaUsuario', $senhaUsuario);
 
       $sqlUsuarioST->execute();
 
       $quantidadeUsuarios = $sqlUsuarioST->rowCount();
       //registrar usuário na secção

       if ($quantidadeUsuarios == 1) {
            $resultadoUsuario = $sqlUsuarioST->fetchAll();

            list($codigoUsuario, $nomeUsuario) = $resultadoUsuario[0];
 
            $_SESSION['verificaUsuarioLogado'] = True;
            $_SESSION['codigoUsuarioLogado'] = $codigoUsuario;
            $nomeCompletoUsuario = explode(' ', $nomeUsuario);
            $_SESSION['nomeUsuarioLogado'] = $nomeCompletoUsuario[0];
            //quando o usuário estiver logado ele será enviado para main.php

            header("Location: main.php");
       }    else { //usuario ou senha incorreto
            header("Location: index.php?codMsg=002");
       }
    }   else { //usuario não informado
        header("Location: index.php?codMsg=001");
    }
?>