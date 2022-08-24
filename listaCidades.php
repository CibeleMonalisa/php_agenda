<?php
session_start();
$verificaUsuarioLogado = $_SESSION['verificaUsuarioLogado'];
if ($verificaUsuarioLogado) {
    include "conectaBanco.php";

    if(isset($_GET['codigoEstado'])){
        $codigoEstado = $_GET['codigoEstado'];
    }   else{
        $codigoEstado = "";
    }

    if(isset($_GET['codigoCidade'])){
        $codigoCidade = $_GET['codigoCidade'];
    }   else{
        $codigoCidade = "";
    }

    $sqlCidades = "SELECT codigoCidade, nomeCidade FROM cidades WHERE codigoEstado=:codigoEstado";

    $sqlCidadesST = $conexao->prepare($sqlCidades);
    $sqlCidadesST->bindValue(':codigoEstado', $codigoEstado);

    $sqlCidadesST->execute();
    $resultadoCidades = $sqlCidadesST->fetchAll();

    echo "<option value=\"\">Escolha a cidade</option>\n";
        
    foreach ($resultadoCidades as list($codCidade, $nomeCidade)){
        if(!empty($codigoCidade) && $codigoCidade == $codCidade ){
            $selected = 'selected';
        }   else{
            $selected = "";
        }
        echo "<option value=\"$codCidade\" $selected>$nomeCidade</option>\n";
    }
}
?>