<?php
$raiz = "http://23.21.144.229/barzin/propaganda/";

$propagandas = array();
if ($handle = opendir("../../propaganda")) {
	while (false !== ($item = readdir($handle))) {
		if ($item != "." && $item != "..") {
			$propagandas[] = $raiz.$item;
		}
	}
}

echo json_encode($propagandas);
?>