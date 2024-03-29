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
$ultima_msg = Sessao::get("ultima_msg");
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
		<script src="javascript/menu_footer.js"></script>
		<script type="text/javascript">
		var raiz_requisicao = "<?php echo Requisicoes::raiz_frontend; ?>";
		var codigo_mesa = "<?php echo $codigo_mesa; ?>";
		var ultima_msg = "<?php echo $ultima_msg; ?>";

		function setar_botoes_excluir() {
			$('.excluir_pessoa').click(function(e) {
				e.preventDefault();

				var ultima_atualizacao_pessoas = $('#ultima_atualizacao_pessoas').val();
				var nome_pessoa = $(this).data('nomepessoa');
				var id_pessoa = $(this).data("idpessoa");
				
				if (!confirm("Tem certeza que quer remover " + nome_pessoa + " da mesa?")) {
					return false;
				}

				$.mobile.loading('show', {
											text: "Carregando", 
											textVisible: true,
											theme: 'a'
											});
				$.post(
					'<?php echo Requisicoes::raiz_frontend; ?>pessoas/remover_pessoa.php?', 
					{
						'id_pessoa': id_pessoa, 
						'random': Math.random()
					}, 
					function(retorno) {
						$.mobile.loading('hide');
						if (retorno == "ok") {
							atualizar_pessoas(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas);
						}
						else {
							alert("Não foi possível excluir pessoa: " + retorno);
						}
					}, 
					"text"
				);
			});
		}
		

		function atualizar_pessoas_automaticamente() {
			var ultima_atualizacao_pessoas = $('#ultima_atualizacao_pessoas').val();
			atualizar_pessoas_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas);
		}

		function atualizar_conta_automaticamente() {
			var ultima_atualizacao_pedidos = $('#ultima_atualizacao_pedidos').val();
			atualizar_pedidos_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos);
		}

		function atualizar_msg_automaticamente() {
			$.post(
				raiz_requisicao + "mensagens/recuperar_mensagens.php",
				{
					"codigo_mesa": codigo_mesa, 
					"ultima_hora_mensagem": ultima_msg,
					'random': Math.random()
				}, 
				function (retorno) {
					if (retorno.hasOwnProperty("mensagens")) {
						if (retorno.mensagens.length > 0) {
							ultima_msg = retorno.ultima_hora_mensagem;
							$.post(
                                'sessao/setar_sessao.php',
                                {
                                    'ultima_msg': retorno.ultima_hora_mensagem,
                                    'random': Math.random()
                                }, 
                                function () {}
                            );
                            $.each(retorno.mensagens, function(index, valor) {
                            	alert(valor);
                            });
						}
					}
				}, 
				"json"
			);
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
		    window.setInterval("atualizar_msg_automaticamente()", 30 * 1000); // de 30 em 30 segundos
		});

		
		var ultimo_chamado_garcom = 0;
		$('.chamar_garcom').live('click', function(event) {			
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
							$('.sub_menu_outros').hide();
							limpar_footer($(this).closest('div[data-role=page]').attr('id'));
						}, 
						'json'
					);
				} 
				else {
					$('.sub_menu_outros').hide();
					limpar_footer($(this).closest('div[data-role=page]').attr('id'));
				}
			}
			else {
				alert('Aguarde um pouco para fazer um novo chamado ao garçom.');
				$('.sub_menu_outros').hide();
				limpar_footer($(this).closest('div[data-role=page]').attr('id'));
			}
			
		});

		var ultima_solicitacao_conta = 0;
		$('.pedir_conta').live('click', function(event) {			
			var agora = new Date().getTime() / 1000;

			if (agora - ultima_solicitacao_conta > 5 * 60) {
				if (confirm("Deseja realmente solicitar a conta para o garçom?")) {
					$.post(
						raiz_requisicao + 'garcom/solicitar_conta.php', 
						{
							'codigo_mesa': codigo_mesa
						}, 
						function(retorno) {
							if (retorno.hasOwnProperty('erro')) {
								alert(retorno.erro);
							}
							else if (retorno.hasOwnProperty('id')) {
								alert('Solicitação de conta realizada.');
								ultima_solicitacao_conta = agora;
							}
							$('.sub_menu_outros').hide();
							limpar_footer($(this).closest('div[data-role=page]').attr('id'));
						}, 
						'json'
					);
				} 
				else {
					$('.sub_menu_outros').hide();
					limpar_footer($(this).closest('div[data-role=page]').attr('id'));
				}
			}
			else {
				alert('Aguarde um pouco para fazer uma nova solicitação de conta');
				$('.sub_menu_outros').hide();
				limpar_footer($(this).closest('div[data-role=page]').attr('id'));
			}
			
		});

		$('.sair').live('click', function() {
			if (confirm('Tem certeza que deseja sair do sistema? Seus pedidos e suas informações NÃO serão apagadas.')) {
				return true;
			}
			else {
				$('.sub_menu_outros').hide();
				limpar_footer($(this).closest('div[data-role=page]').attr('id'));	
				return false;
			}
		});

		</script>
	</head>
	<body>

        <?php include "paginas/cardapio.php"; ?>

        <?php include "paginas/pessoas.php"; ?>

        <?php include "paginas/conta.php"; ?>

        <?php include "paginas/pendentes.php"; ?>

	</body>
</html>