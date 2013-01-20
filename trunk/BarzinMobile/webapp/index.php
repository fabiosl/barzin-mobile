<?php
include_once "classes/layout.php";
include_once "classes/sessao.php";
include_once "classes/requisicoes.php";

if (!Sessao::tem("codigo_mesa") || !Sessao::tem("id_bar")) {
	header('Location: conectar.php');
    exit;
}

$nome_mesa = Sessao::get("nome_mesa");
$nome_bar = Sessao::get("nome_bar");
$codigo_mesa = Sessao::get("codigo_mesa");
$cardapio = Sessao::get("cardapio");
?>

<!DOCTYPE html>
 <html>
	<head>
		<meta name="format-detection" content="telephone=no">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Barzin Mobile Web App</title>
		<link rel="stylesheet" href="css/barzin.css" />
		<link rel="stylesheet" href="css/jquery.mobile.structure-1.2.0.min.css" />
		<link rel="stylesheet" href="css/estilo.css" />
		<script src="javascript/jquery-1.8.2.min.js"></script>
		<script src="javascript/jquery.mobile-1.2.0.min.js"></script>
		<script src="javascript/jquery.session.js"></script>
		<script src="javascript/funcoes.js"></script>
		<script type="text/javascript">
		var raiz_requisicao = "<?php echo Requisicoes::raiz_frontend; ?>";
		var codigo_mesa = "<?php echo $codigo_mesa; ?>";

		function atualizar_pessoas_automaticamente() {
			var ultima_atualizacao_pessoas = $('#ultima_atualizacao_pessoas').val();
			atualizar_pessoas_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas);
		}

		function atualizar_conta_automaticamente() {
			var ultima_atualizacao_pedidos = $('#ultima_atualizacao_pedidos').val();
			atualizar_pedidos_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos);
		}

		location.hash = "#pessoas";
		
		var pedidos = new Array();
		var pessoas = new Array();
		
		$(function() {
			$.mobile.loading('show', {
									text: "Carregando", 
									textVisible: true,
									theme: 'a'
									});
			atualizar_pessoas_automaticamente();
			atualizar_conta_automaticamente();
			$.mobile.loading('hide');

		    window.setInterval("atualizar_pessoas_automaticamente()", 10 * 60 * 1000); // de 10 em 10 minutos
		    window.setInterval("atualizar_conta_automaticamente()", 30 * 1000); // de 30 em 30 segundos
		});

		
		var ultimo_chamado_garcom = 0;
		$('.chamar_garcom').live('click', function() {
			var agora = new Date().getTime() / 1000;

			if (agora - ultimo_chamado_garcom > 5 * 60) {
				if (confirm("Deseja realmente chamar o garçom?")) {
					$.post(
						raiz_requisicao + 'garcom/chamar_garcom.php', 
						{
							'codigo_mesa': codigo_mesa
						}, 
						function(retorno) {
							if (retorno.hasOwnProperty('erro')) {
								alert(retorno.erro);
							}
							else if (retorno.hasOwnProperty('id')) {
								alert('Chamado ao garçom realizado.');
								ultimo_chamado_garcom = agora;
							}
						}, 
						'json'
					);
				}
			}
			else {
				alert('Aguarde um pouco para fazer um novo chamado ao garçom.');
			}
		});

		$('.sair').live('click', function() {
			return confirm('Tem certeza que deseja sair do sistema? Seus pedidos e suas informações NÃO serão apagadas.');
		});
						
		</script>
	</head>
	<body>

        <?php include "paginas/cardapio.php"; ?>

        <?php include "paginas/pessoas.php"; ?>

        <?php include "paginas/conta.php"; ?>

	</body>
</html>