<?php
    session_start();

    $verificaUsuarioLogado = $_SESSION['verificaUsuarioLogado'];

    if (!$verificaUsuarioLogado) {
        header("Location: index.php?codMsg=003");
    } else {
        include "conectaBanco.php";
        $codigoUsuarioLogado = $_SESSION['codigoUsuarioLogado'];

        if(isset($_GET['codigoContato'])){
            $codigoContato = $_GET['codigoContato'];
            
            $sqlContato = "DELETE FROM contatos WHERE codigoContato=:codigoContato AND codigoUsuario=:codigoUsuario";

            $sqlContatoST = $conexao->prepare($sqlContato);
            $sqlContatoST->bindValue(':codigoContato', $codigoContato);
            $sqlContatoST->bindValue(':codigoUsuario', $codigoUsuarioLogado);

            $sqlContatoST->execute();

            header("Location: listaContatos.php");
        }
    }
?>