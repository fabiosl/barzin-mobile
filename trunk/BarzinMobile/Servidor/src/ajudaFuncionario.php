<?php
require 'verifica.php';
include_once 'classes/design.php';

$design = new Design(".");
$design->imprimir_topo();
?>

<div class="titulo_secao">Ajuda</div><br/><br/>

<?php
echo "<center>".$design->get_imagem("ajudaGarcom.png", "Ajuda")."</center>";

$design->imprimir_fim();
?>
