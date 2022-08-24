<?php

   function gerarSenha($comprimentoSenha = 8, $habilitarMaiusculas = True, $habilitarNumeros = True, 
               $habilitarSimbolos = True) {

      $bancoMinusculas = 'abcdefghijklmnopqrstuvwxyz';
      $bancoMaiusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $bancoNumeros = '1234567890';
      $bancoSimbolos = ' !@#$%*- ';

      $senhaGerada = '';
      $bancoCaracteres = "";

      $bancoCaracteres .= $bancoMinusculas;

      if ($habilitarMaiusculas) {
         $bancoCaracteres .= $bancoMaiusculas;
      }
      if ($habilitarNumeros) {
         $bancoCaracteres .= $bancoNumeros;
      }
      if ($habilitarSimbolos) {
         $bancoCaracteres .= $bancoSimbolos;
      }

      $comprimentoTotal = strlen($bancoCaracteres);
      for ($i = 1; $i <= $comprimentoSenha; $i++) {
         $caractereAleatorio = mt_rand(0, $comprimentoTotal);
         $senhaGerada .= $bancoCaracteres[$caractereAleatorio -1];
      }
      return $senhaGerada;
   }
   
   echo gerarSenha(8);

?>