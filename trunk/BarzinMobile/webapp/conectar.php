<?php
	include_once "classes/requisicoes.php";
    include_once "classes/sessao.php";

    if (Sessao::tem("codigo_mesa") && Sessao::tem("id_bar")) {
        header('Location: index.php');
        exit;
    }

	$bares = json_decode(Requisicoes::fazer_requisicao("bares/recuperar_bares.php"));
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
		<script src="javascript/jquery-1.8.2.min.js"></script>
		<script src="javascript/jquery.mobile-1.2.0.min.js"></script>
		<script src="javascript/jquery.session.js"></script>
	</head>
	<body>
        <!-- Home -->
        <div data-role="page" data-theme="a">
            <div data-role="content">
                <div style=" text-align:center">
                    <img style="height: 80px;" src="img/logo.png" />
                </div>
                <div data-role="fieldcontain">
                    <label for="id_bar">
                        Bar:
                    </label><br/>
                    <select id="id_bar" name="">
                    	<?php
                    		foreach ($bares as $bar) {
                    			echo "
                    			 <option value=\"".$bar->id."\">
                    				".$bar->nome."
                    			 </option>
                    			";
                    		}
                        ?>
                    </select>
                </div>
                <div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                        <label for="codigo_mesa" style="white-space: nowrap;">
                            Código da Mesa:
                        </label><br/>
                        <input name="" id="codigo_mesa" placeholder="" value="" type="tel" style="text-align: center;"/>
                    </fieldset>
                </div>
                <a data-role="button" href="#" data-icon="arrow-r" data-iconpos="right" data-theme="b" id="botao_conectar">
                    Conectar
                </a>
            </div>
        </div>
        <script>
            $(function() {
            	$('#botao_conectar').click(function() {
            		var codigo_mesa = $('#codigo_mesa').val();
            		var id_bar = $('#id_bar').val();
            		if (codigo_mesa.length != 4) {
            			alert('Código inválido');
            		}
            		else {
            			$.mobile.loading('show', {
            										text: "Conectando", 
            										textVisible: true,
            										theme: 'a'
            									});
            			$.post(
            				'<?php echo Requisicoes::raiz_frontend ?>mesas/conectar_a_mesa.php', 
            				{
            					'codigo_mesa': codigo_mesa,
            					'id_bar': id_bar, 
                                'random': Math.random()
            				}, 
            				function(retorno) {
            					$.mobile.loading('hide');
                                if (retorno.hasOwnProperty("erro")) {
                                    alert(retorno.erro);
                                }
            					else if (retorno.hasOwnProperty("mesa") && retorno.hasOwnProperty("bar") && retorno.hasOwnProperty("cardapio")) {
            						$.post(
                                        'sessao/setar_sessao.php',
                                        {
                                            'codigo_mesa': retorno.mesa.codigo, 
                                            'id_bar': retorno.bar.id, 
                                            'nome_mesa': retorno.mesa.nome, 
                                            'nome_bar': retorno.bar.nome, 
                                            'cardapio': retorno.cardapio,
                                            'random': Math.random()
                                        },
                                        function(retornoSessao) {
                                            if (retornoSessao == 'ok') {
                                                window.location = "index.php";
                                            }
                                            else {
                                                alert(retornoSessao);
                                            }
                                        }
                                    );
            					}
            					else {
            						alert("Não retornou nem mesa nem erro! Tem que ver isso aí.");
            					}
            				},
            				"json"
            			);
            		}
            	});
            });
        </script>
    </body>
</html>