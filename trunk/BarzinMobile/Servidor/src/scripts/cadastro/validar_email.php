<?php
include_once '../../classes/dao.php';
include_once '../../classes/bar.php';
include_once '../../classes/cardapio.php';
include_once '../../classes/erro.php';

function check_email_address($email) {
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
			return false;
		}
	}
	if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
				return false;
			}
		}
	}

	return true;
}



$banco = new DAO();

$email = $_REQUEST["email"];

if (array_key_exists("id_bar", $_REQUEST)) {
	$id_bar = $_REQUEST["id_bar"];
}

if (!check_email_address($email)) {
	echo "Formato inválido para email.";
	exit;
}

if (isset($id_bar)) {
	if ($banco->consulta_existe_email_em_outro_bar($email, $id_bar)) {
		echo "O email já está cadastrado.";
		exit;
	}
}
elseif ($banco->consulta_existe_email($email)) {
	echo "O email já está cadastrado.";
	exit;
}

echo "ok";

?>