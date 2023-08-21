<?php

include_once("conexao.php");

$result_aluno = "SELECT * FROM estudante ";


use Dompdf\Dompdf;

require_once("dompdf/autoload.inc.php");

$dompdf = new DOMPDF();

$dompdf = load_html('<h1>Teste de imagem</h1>');
