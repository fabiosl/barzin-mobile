<?php
include_once '../../classes/dao.php';
include_once '../../classes/bar.php';
include_once '../../classes/cardapio.php';
include_once '../../classes/erro.php';

$banco = new DAO();

$id_bar = $_REQUEST["id_bar"];
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

$bar = $banco->recupera_bar($id_bar);

if (get_class($bar) == "Erro") {
	echo $bar->get_json();
	exit;
}

$bar->set_nome($nome);
$bar->set_email($email);
$bar->set_rua($rua);
$bar->set_numero($numero);
$bar->set_complemento($complemento);
$bar->set_bairro($bairro);
$bar->set_cidade($cidade);
$bar->set_estado($estado);
$bar->set_cep($cep);
$bar->set_telefone1($telefone1);
$bar->set_telefone2($telefone2);

echo $banco->salvar_bar($bar);
exit;
?>