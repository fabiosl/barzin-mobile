<?php
$categorias_maes = array();

function construir_maes($categoria_mae, $eh_cardapio) {
	global $categorias_maes, $cardapio;

	if ($eh_cardapio) {
		$nome_mae = null;
		$id_mae = null;
		$categoria_mae = $cardapio;
		$categorias_filhas = "categorias";
	}
	else {
		$nome_mae = $categoria_mae["nome"];
		$id_mae = $categoria_mae["id"];
		$categorias_filhas = "subcategorias";
	}

	if (isset($categoria_mae[$categorias_filhas])) {
		foreach ($categoria_mae[$categorias_filhas] as $categoria_filha) {
			$categorias_maes[$categoria_filha["id"]] = array(
														"nome" => $nome_mae, 
														"id" => $id_mae);
			construir_maes($categoria_filha, false);
		}
	}
}

function construir_categorias() {
	global $cardapio;

	if (isset($cardapio["categorias"])) {
		foreach ($cardapio["categorias"] as $categoria) {
			construir_categoria($categoria);
		}
	}
}

function construir_categoria($categoria) {
	global $nome_bar, $nome_mesa, $categorias_maes;
	
	echo "
     <div data-role=\"page\" id=\"categoria_".$categoria["id"]."\" data-theme=\"a\" >
    ";

	echo Layout::imprimir_header("cardapio");

	
	echo "
		<div data-role=\"content\">
			<h1>Cardápio</h1>
	"; 

	$navegacao = "";
	$mae_id = $categorias_maes[$categoria["id"]]["id"];
	$mae_nome = $categorias_maes[$categoria["id"]]["nome"];
	while ($mae_id != null) {
		$navegacao = "<a href=\"#categoria_".$mae_id."\" data-transition=\"slide\" data-direction=\"reverse\" class=\"breadcrumbs-cardapio\">".$mae_nome."</a>".$navegacao;
		$mae_nome = $categorias_maes[$mae_id]["nome"];
		$mae_id = $categorias_maes[$mae_id]["id"];
	}
	$navegacao = "<a href=\"#cardapio\" data-transition=\"slide\" data-direction=\"reverse\" class=\"breadcrumbs-cardapio\">Cardápio</a>".$navegacao;

	echo $navegacao;

	echo "
			<h2>".$categoria["nome"]."</h2>
    ";
   
    imprimir_categoria($categoria);

    echo "
     	</div>
	";

	echo "
	 </div>
	";

	if (isset($categoria["subcategorias"])) {
    	foreach ($categoria["subcategorias"] as $subcategoria) {
    		construir_categoria($subcategoria);
    	}
    }

    if (isset($categoria["itens"])) {
    	foreach ($categoria["itens"] as $item) {
    		construir_item($item);
    	}
    }
}

function imprimir_categoria($categoria_mae, $eh_cardapio = false) {
	global $cardapio;

	if ($eh_cardapio) {
		$categoria_mae = $cardapio;
		$categorias_filhas = "categorias";
	}
	else {
		$categorias_filhas = "subcategorias";
	}

	echo "<ul data-role=\"listview\" data-inset=\"true\">";

	if (isset($categoria_mae[$categorias_filhas])) {
		echo "
		 <li data-role=\"list-divider\">
		 	Categorias
         </li>
		";
		foreach ($categoria_mae[$categorias_filhas] as $categoria_filha) {
			echo "
			 <li>
			 	<a href=\"#categoria_".$categoria_filha["id"]."\" data-transition=\"slide\">
			 		".$categoria_filha["nome"]."
			 	</a>
			 </li>
			";
		}
	}

	if (isset($categoria_mae["itens"])) {
		echo "
		 <li data-role=\"list-divider\">
		 	Itens
         </li>
		";
		foreach ($categoria_mae["itens"] as $item) {
			$preco = sprintf("%.2f", floatval($item["preco"]));
			echo "
			 <li data-icon=\"search\">
			 	<a href=\"#item_".$item["id"]."\" data-transition=\"slide\">
			 		<img src=\"".Requisicoes::raiz_frontend."cardapio/recuperar_thumb_item.php?id_item=".$item["id"]."\" />
			 		<div style=\"white-space: normal;\">".$item["nome"]."</div>
			 		<div class=\"texto-pequeno\">".$item["descricao"]."</div>
			 		<div class=\"preco\">R$ $preco</div>
			 	</a>
			 	<a href=\"#\" data-theme=\"c\"></a>
			 </li>
			";
		}
	}

	echo "</ul>";
}

function construir_item($item) {
	global $nome_bar, $nome_mesa, $categorias_maes;
	
	echo "
     <div data-role=\"page\" id=\"item_".$item["id"]."\" data-theme=\"a\">
    ";

	echo Layout::imprimir_header("cardapio");

	
	echo "
		<div data-role=\"content\">
			<h1>Cardápio</h1>

			<a href=\"#\" data-rel=\"back\" class=\"voltar\" data-transition=\"slide\">Voltar</a>
			
			<h2>".$item["nome"]."</h2>

			<img src=\"".Requisicoes::raiz_frontend."cardapio/recuperar_thumb_item.php?id_item=".$item["id"]."\" class=\"imagem-item\"/>
			<span class=\"preco\">R$ ".sprintf("%.2f", floatval($item["preco"]))."</span><br/>
			<span class=\"texto-pequeno\">
				".$item["descricao"]."
			</span>
			
			<hr style=\"clear: both;\" />

			<table border=\"0\">
				<tr valign=\"middle\">
					<td align=\"right\">
                		Quant.:
            		</td>
            		<td align=\"left\">
            			<select class=\"quantidade\" data-inline=\"true\">
    "; 

    for ($i = 1; $i <= 10; $i++) { 
    	echo "<option value=\"$i\">$i</option>";
    }

    echo "
            			</select>
            		</td>
        		</tr>
        		<tr valign=\"middle\">
            		<td align=\"right\">
            			Marcar:
            		</td>
            		<td align=\"left\" nowrap>
						<a href=\"#\" data-role=\"button\" data-mini=\"true\" data-inline=\"true\" data-theme=\"b\" class=\"marcar_todos\">Todos</a>
						<a href=\"#\" data-role=\"button\" data-mini=\"true\" data-inline=\"true\" data-theme=\"b\" class=\"desmarcar_todos\">Ninguém</a>
					</td>
				</tr>
			</table>

            <fieldset data-role=\"controlgroup\" data-type=\"vertical\" class=\"pessoas_marcar\"></fieldset>        

            <div class=\"parcelas_pessoas\" style=\"visibility: hidden; padding-bottom: 20px;\">
            	R$ 
        	</div>

        	<label>Comentário</label>
        	<textarea class=\"comentario\" placeholder=\"Comentário opcional\" data-mini=\"true\"></textarea>

        	<span class=\"preco_item\" style=\"display: none;\">".$item["preco"]."</span>
        	<span class=\"id_item\" style=\"display: none;\">".$item["id"]."</span>

            <a data-role=\"button\" href=\"#\" data-icon=\"plus\" data-iconpos=\"right\" data-theme=\"c\" class=\"botao_fazer_pedido\">
	            Fazer pedido
	        </a>

		</div>

		<script type=\"text/javascript\">
			$('#item_".$item["id"]."').live('pagebeforeshow', function() {
				var elemento = $(this).find('.pessoas_marcar');

				$(elemento).empty();

			    $.each(pessoas, function(index, pessoa) {
			    	$(elemento).append('' +  
			    					'<label>' +
		    							'<input name=\"pessoas\" type=\"checkbox\" value=\"' + pessoa.id + '\" class=\"checkbox_pessoas\"/>' + 
                    					pessoa.nome + 
                					'</label>');
			    });

				$(elemento).find('input').removeAttr('checked');

				$(elemento).trigger('create');

				$(this).find('select').val('1');
				$(this).find('select').selectmenu('refresh');
				$(this).find('.parcelas_pessoas').text('');
				$(this).find('.comentario').val('');

				$('.checkbox_pessoas').on('change', function(event, ui) {
					var elementoParcelas = $(this).closest('fieldset').siblings('.parcelas_pessoas');
					atualizar_parcelas(elementoParcelas);
				});

				$('.quantidade').on('change', function(event, ui) {
					var elementoParcelas = $(this).closest('table').siblings('.parcelas_pessoas');
					atualizar_parcelas(elementoParcelas);
				});
			});
		</script>
    ";

	echo "
	 </div>
	";
}

construir_maes($cardapio, true);
construir_categorias();
?>

<div data-role="page" id="cardapio" data-theme="a" >
	
	<?php echo Layout::imprimir_header("cardapio"); ?>

	<div data-role="content">

		<h1>Cardápio</h1>

		<?php
		imprimir_categoria($cardapio, true);
		?>

    </div>
	
</div>

<script type="text/javascript">
	$('.marcar_todos').click(function() {
		$(this).closest('div').find('input[type=checkbox]').attr('checked', 'checked');
		$('input:checkbox').checkboxradio('refresh');

		var elementoParcelas = $(this).closest('table').siblings('.parcelas_pessoas');
		atualizar_parcelas(elementoParcelas);
	});

	$('.desmarcar_todos').click(function() {
		$(this).closest('div').find('input[type=checkbox]').removeAttr('checked');
		$('input:checkbox').checkboxradio('refresh');

		var elementoParcelas = $(this).closest('table').siblings('.parcelas_pessoas');
		atualizar_parcelas(elementoParcelas);
	});

	$('.botao_fazer_pedido').click(function() {
		var item_id = $(this).siblings('.id_item').text();
		var quantidade = $(this).siblings('table').find('select').val();

		var pessoas = new Array();
		$(this).siblings('.pessoas_marcar').find('input:checkbox:checked').each(function() {
			pessoas.push($(this).val());
		});

		var comentario = $(this).siblings('.comentario').val();

		if (pessoas.length < 1) {
			alert('O pedido deve ser feito para no mínimo uma pessoa.');
		}
		else {
			$.mobile.loading('show', {
									text: "Carregando", 
									textVisible: true,
									theme: 'a'
									});
			$.post(
				raiz_requisicao + 'pedidos/novo_pedido.php?', 
				{
					'item_id': item_id, 
					'quantidade': quantidade, 
					'pessoas': pessoas, 
					'comentario': comentario, 
					'random': Math.random()
				}, 
				function(retorno) {
					$.mobile.loading('hide');
					if (retorno.hasOwnProperty("erro")) {
						alert("Erro ao fazer pedido: " + retorno.erro);
					}
					else {
						alert("Pedido realizado com sucesso.");

						var ultima_atualizacao_pedidos = $('#ultima_atualizacao_pedidos').val();
						atualizar_pedidos(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos);

						location.hash = "#conta";
					}
				}, 
				'json'
			);
		}
	});

</script>