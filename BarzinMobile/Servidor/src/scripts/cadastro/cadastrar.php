<?php
include_once '../../classes/dao.php';
include_once '../../classes/bar.php';
include_once '../../classes/cardapio.php';
include_once '../../classes/erro.php';

$banco = new DAO();

$nome = $_REQUEST["nome"];
$email = $_REQUEST["email"]; 
$rua = $_REQUEST["rua"]; 
$numero = $_REQUEST["numero"]; 
$complemento = $_REQUEST["complemento"]; 
$bairro = $_REQUEST["bairro"]; 
$cidade = $_REQUEST["cidade"]; 
$estado = $_REQUEST["estado"];
$cep = $_REQUEST["cep"]; 
$telefone1 = $_REQUEST["telefone1"]; 
$telefone2 = $_REQUEST["telefone2"]; 
$admin_login = $_REQUEST["admin_login"];
$admin_senha = $_REQUEST["admin_senha"]; 
$func_login = $_REQUEST["func_login"]; 
$func_senha = $_REQUEST["func_senha"];

$bar = new Bar($nome, $rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $telefone1, $telefone2, $email, $admin_login, $func_login);

echo $banco->cadastrar_bar($bar, $admin_senha, $func_senha);
exit;
?>