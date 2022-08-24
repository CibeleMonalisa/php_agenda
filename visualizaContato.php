<?php
  session_start();

  $verificaUsuarioLogado = $_SESSION['verificaUsuarioLogado'];

  if (!$verificaUsuarioLogado) {
      header("Location: index.php?codMsg=003");
  }   else {
      include 'conectaBanco.php';
      include 'common/formataData.php';
      $codigoUsuarioLogado = $_SESSION['codigoUsuarioLogado'];
  }  
  
  if  (isset($_GET['codigoContato'])){
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
      
      $nascimentoContato = formataData($nascimentoContato);

      if($sexoContato == 'M'){
        $sexoContato = "Masculino";
      } else{
        $sexoContato = "Feminino";
      }

      $sqlEndereco = "SELECT c.nomeCidade, e.nomeEstado FROM cidades as c, estados as e WHERE
                        c.codigoCidade=:cidadeContato AND e.codigoEstado=:estadoContato";

      $sqlEnderecoST = $conexao->prepare($sqlEndereco);
      $sqlEnderecoST->bindValue(':cidadeContato', $cidadeContato );
      $sqlEnderecoST->bindValue(':estadoContato', $estadoContato);
      $sqlEnderecoST->execute();

      $resultadoEndereco = $sqlEnderecoST->fetchAll();
      list($cidadeContato, $estadoContato) = $resultadoContato[0];

      echo "<h5 class=\"text-primary\">Dados pessoais</h5>
            <hr>
            <div class=\"row\">
                <div class=\"col-sm\">
                    <div class=\"row\">
                        <div class=\"col-sm\">
                          <p>$nomeContato</p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$nascimentoContato</p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$sexoContato</p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$mailContato</p>
                        </div>
                    </div>
                </div>
                <div class=\"col-sm\">
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <div class=\"row\">
                                <div class=\"col-sm\">
                                    <img src=\"$fotoContato\" alt=\"$nomeContato\">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h5 class=\"text-primary\">Telefones</h5>
            <hr>
            <div class=\"row\">
                <!-- essa coluna é a da esquerda -->
                <div class=\"col-sm\">
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$telefone1Contato</p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$telefone2Contato</p>
                        </div>
                    </div>
                </div>
                <div class=\"col-sm\">
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$telefone3Contato</p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-sm\">       
                            <p>$telefone4Contato</p>
                        </div>
                    </div>
                </div>
            </div>
            <h5 class=\"text-primary\">Endereço</h5>
            <hr>
            <div class=\"row\">
                <div class=\"col-sm\">
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$logradouroContato</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-sm\">
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$complementoContato</p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$estadoContato</p>
                        </div>
                    </div>
                </div>
                <div class=\"col-sm\">
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$bairroContato</p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col-sm\">
                            <p>$cidadeContato</p>
                        </div>
                    </div>
                </div>
            </div>";
      
    }
  }
?>