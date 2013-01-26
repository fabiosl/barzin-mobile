function isArray(what) {
    return Object.prototype.toString.call(what) === '[object Array]';
}

function trim(str) {
	return str.replace(/^\s+|\s+$/g,"");
}

function indice_elemento_com_id_no_array(id, array) {
	for (var i = 0; i < array.length; i++) {
		var elemento = array[i];
		if (elemento.id == id) {
			return i;
		}
	}
	return -1;
}

function atualizar_pessoas(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas) {
	$.mobile.loading('show', {
								text: "Carregando", 
								textVisible: true,
								theme: 'a'
								});

	atualizar_pessoas_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas);
	$.mobile.loading('hide');
}

function atualizar_pessoas_frontend() {
	if (pessoas.length < 1) {
		$('#msg_pessoas').show();
		$('.nao-pessoas').addClass("ui-disabled");
	}
	else {
		$('#msg_pessoas').hide();
		$('#lista_pessoas').empty();
		$.each(pessoas, function(indice, pessoa) {
        	$('#lista_pessoas').append('' + 
    								'<li>' + 
    									'<a href="#">' + pessoa.nome + '</a>' + 
    									'<a href="#" data-theme="d" class="excluir_pessoa" data-idpessoa="' + pessoa.id + '" data-nomepessoa="' + pessoa.nome + '">Excluir</a>' + 
									'</li>');
        });
        $('#lista_pessoas').listview('refresh');
		setar_botoes_excluir();
		$('.nao-pessoas').removeClass("ui-disabled");
	}
}

function atualizar_pessoas_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas) {
	$.post(
		raiz_requisicao + 'pessoas/pessoas_na_mesa.php?', 
		{
			'codigo_mesa': codigo_mesa, 
			'ultima_atualizacao_pessoas': ultima_atualizacao_pessoas, 
			'random': Math.random()
		}, 
		function(retorno) {
			if (retorno.hasOwnProperty("erro")) {
				alert("Erro ao atualizar as pessoas: " + retorno.erro);
			}
			else if (retorno.hasOwnProperty("pessoas")) {
				pessoas = retorno.pessoas;
    			atualizar_pessoas_frontend();
	        	$('#ultima_atualizacao_pessoas').val(retorno.ultima_atualizacao_pessoas);
			}
		}, 
		'json'
	);
}

function atualizar_parcelas(elemento) {
	var preco = parseFloat(elemento.siblings('.preco_item').text());
	var quantidade = parseFloat(elemento.siblings('table').find('select').val());
	var quantasPessoas = elemento.siblings('fieldset').find('input:checked').size();

	var parcela = (quantidade * preco / quantasPessoas).toFixed(2);

	if (quantasPessoas > 0) {
		elemento.text("Parcela para cada: R$ " + parcela);
		elemento.css("visibility", "visible");
	}
	else {
		elemento.css("visibility", "hidden");
	}
}

function atualizar_pedidos(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos) {
	$.mobile.loading('show', {
								text: "Carregando", 
								textVisible: true,
								theme: 'a'
								});

	atualizar_pedidos_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos);
	$.mobile.loading('hide');
}

function atualizar_pedidos_backend(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos) {
	$.post(
		raiz_requisicao + 'pedidos/recuperar_pedidos.php?', 
		{
			'codigo_mesa': codigo_mesa, 
			'ultima_atualizacao_pedidos': ultima_atualizacao_pedidos, 
			'random': Math.random()
		}, 
		function(retorno) {
			if (retorno.hasOwnProperty("erro")) {
				alert("Sua mesa foi fechada, e você agora sairá do sistema. Obrigado por usar Barzin!");
				window.location = "sair.php";
				return;
			}
			else if (retorno.hasOwnProperty("pedidos")) {
				pedidos = retorno.pedidos;
				atualizar_conta_frontend();
				atualizar_pendentes_frontend(raiz_requisicao);
	        	$('#ultima_atualizacao_pedidos').val(retorno.ultima_atualizacao_pedidos);
			}
		}, 
		'json'
	);
}

function atualizar_conta_frontend() {
	var pessoas = $('#pessoas_marcar_conta');
	var pessoas_resumo = $('#pessoas_resumo');
	var elemento_num_selecionados = $('#num_selecionados');
	atualizar_informacoes_pessoas_conta(pessoas, pessoas_resumo, elemento_num_selecionados);

	var pessoas_selecionadas = pessoas.find('input:checkbox:checked');

	var pedidos_filtrados = [];

	for (var i = 0; i < pedidos.length; i++) {
		var pedido = pedidos[i];
		var quantidade_por_pessoa = parseInt(pedido.quantidade) / pedido.pessoas.length;
		for (var j = 0; j < pedido.pessoas.length; j++) {
			pessoa_pedido = pedido.pessoas[j];
			for (var k = 0; k < pessoas_selecionadas.length; k++) {
				if ($(pessoas_selecionadas[k]).val() == pessoa_pedido.id) {
					var id = pedido.id + "";
					if (pedido.estado == "Atendido" || pedido.estado == "Cancelado") {
						if (indice_elemento_com_id_no_array(id, pedidos_filtrados) == -1) {
							var array = [];
							array["id"] = id;
							array["pedido"] = pedido;
							array["quantidade"] = 0;
							array["estado"] = pedido.estado;
							pedidos_filtrados.push(array);
						}
						var indice = indice_elemento_com_id_no_array(id, pedidos_filtrados);
						pedidos_filtrados[indice]["quantidade"] += quantidade_por_pessoa;
					}
				}
			}
		}
	};

	var total = 0;

	$('#lista_pedidos_atendidos').children('tbody').empty();

	if (pedidos_filtrados.length == 0) {
		$('#pedidos_atendidos').hide();
	}
	else {
		$('#pedidos_atendidos').show();
	}

	for (var indice in pedidos_filtrados) {
		var pedido_filtrado = pedidos_filtrados[indice];

		if (pedido_filtrado["quantidade"] % 1 > 0) {
			var quantidade = pedido_filtrado["quantidade"].toFixed(1);
		}
		else {
			var quantidade = pedido_filtrado["quantidade"];
		}

		var parcela = parseFloat(pedido_filtrado["quantidade"]) * parseFloat(pedido_filtrado["pedido"].preco_item)

		var ultimoDoisPontos = pedido_filtrado["pedido"].hora.lastIndexOf(":")
		var hora = pedido_filtrado["pedido"].hora.substring(0, ultimoDoisPontos);

		if (pedido_filtrado["estado"] == 'Atendido') {
			total += parcela;
			$('#lista_pedidos_atendidos').children('tbody').append('' + 
																	'<tr>' +
																		'<td>' + pedido_filtrado["pedido"].item + '</td>' + 
																		'<td align="center">' + quantidade + '</td>' + 
																		'<td align="center">' + hora + '</td>' + 
																		'<td align="center">' + pedido_filtrado["pedido"].estado + '</td>' + 
																		'<td align="center" nowrap>R$ ' + parseFloat(pedido_filtrado["pedido"].preco_item).toFixed(2) + '</td>' + 
																		'<td align="center" nowrap>R$ ' + parcela.toFixed(2) + '</td>' + 
																	'</tr>');
		}
		else {
			$('#lista_pedidos_atendidos').children('tbody').append('' + 
																	'<tr class="cancelado">' +
																		'<td>' + pedido_filtrado["pedido"].item + '</td>' + 
																		'<td align="center">-</td>' + 
																		'<td align="center">' + hora + '</td>' + 
																		'<td align="center">' + pedido_filtrado["pedido"].estado + '</td>' + 
																		'<td align="center" nowrap>-</td>' + 
																		'<td align="center" nowrap>-</td>' + 
																	'</tr>');
		}

    	
    	$('#lista_pedidos_atendidos').trigger('create');
	}						

	$('#total_escondido').text(total);
	atualizar_total_com_taxa();
}

function atualizar_total_com_taxa() {
	var total = parseInt($('#total_escondido').text(), 10);

	var valor_taxa = $('#taxa').val() / 100 * total;
	if (valor_taxa > 0) {
		$('#valor_taxa').text('(R$ ' + valor_taxa.toFixed(2) + ' de taxa)');
	}
	else {
		$('#valor_taxa').text('');
	}

	$('#total').text((total + valor_taxa).toFixed(2));
}

function atualizar_pendentes_frontend(raiz_requisicao) {
	var pessoas = $('#pessoas_marcar_pendentes');
	var pessoas_resumo = $('#pessoas_resumo_pendentes');
	var elemento_num_selecionados = $('#num_selecionados_pendentes');
	atualizar_informacoes_pessoas_conta(pessoas, pessoas_resumo, elemento_num_selecionados);

	var pessoas_selecionadas = pessoas.find('input:checkbox:checked');

	var pedidos_pendentes = [];

	for (var i = 0; i < pedidos.length; i++) {
		var pedido = pedidos[i];
		var quantidade_por_pessoa = parseInt(pedido.quantidade) / pedido.pessoas.length;
		for (var j = 0; j < pedido.pessoas.length; j++) {
			pessoa_pedido = pedido.pessoas[j];
			for (var k = 0; k < pessoas_selecionadas.length; k++) {
				if ($(pessoas_selecionadas[k]).val() == pessoa_pedido.id) {
					var id = pedido.id + "";
					if (pedido.estado == "Pendente" || pedido.estado == "Cancelamento Solicitado") {
						if (indice_elemento_com_id_no_array(id, pedidos_pendentes) == -1) {
							var array = [];
							array["id"] = id;
							array["pedido"] = pedido;
							pedidos_pendentes.push(array);
						}
					}
				}
			}
		}
	};

	var total = 0;

	$('#lista_pedidos_pendentes').empty();

	if (pedidos_pendentes.length == 0) {
		$('#pedidos_pendentes').hide();
	}
	else {
		$('#pedidos_pendentes').show();
	}

	for (var indice in pedidos_pendentes) {
		var pedido = pedidos_pendentes[indice]["pedido"];

		valor = parseFloat(pedido.preco_item) * parseInt(pedido.quantidade)

		total += valor

		var ultimoDoisPontos = pedido.hora.lastIndexOf(":")
		var hora = pedido.hora.substring(0, ultimoDoisPontos);

		var texto_pessoas = "";
		for (var i = 0; i < pedido.pessoas.length; i++) {
			texto_pessoas = texto_pessoas + trim(pedido.pessoas[i].nome);
			if (i < pedido.pessoas.length - 2) {
				texto_pessoas = texto_pessoas + ", ";
			}
			else if (i == pedido.pessoas.length - 2) {
				texto_pessoas = texto_pessoas + " e ";
			}
		}

		var adicionar_classe = "";
		if (pedido.cancelamento_solicitado) {
			adicionar_classe = "ui-disabled";
		}
		
		$('#lista_pedidos_pendentes').append('' + 
												'<li data-icon="delete">' + 
													'<a href="#">' + 
														'<img src="' + raiz_requisicao + 'cardapio/recuperar_thumb_item.php?id_item=' + pedido.id_item + '"/>' + 
														'<div style="white-space: normal;">' + pedido.item + '</div>' + 
														'<div class=\"texto-pequeno\">' + pedido.quantidade + ' p/ ' + texto_pessoas + '</div>' + 
														'<div class=\"texto-pequeno\">Pedido às ' + hora + '</div>' + 
														'<div class=\"preco\">R$ ' + valor.toFixed(2) + '</div>' + 
													'</a>' + 
													'<a href="#" data-theme="d" data-id="' + pedido.id + '" data-pessoas="' + texto_pessoas + '" data-item="' + pedido.item + '" data-quantidade="' + pedido.quantidade + '" class="cancelar_pedido_pendente ' + adicionar_classe + '"></a>' + 
												'</li>');
		$('#lista_pedidos_pendentes').listview('refresh');
	}
						

	$('#total_pendentes').text(total.toFixed(2));
}

function atualizar_informacoes_pessoas_conta(pessoas, pessoas_resumo, elemento_num_selecionados) {
	var num_selecionados = "";
	var texto_pessoas = "";
	var num_pessoas_na_mesa = pessoas.children().size();
	var pessoas_selecionadas = pessoas.find('input:checkbox:checked');

	if (pessoas_selecionadas.length == num_pessoas_na_mesa) {
		texto_pessoas = "Todas as pessoas";
		num_selecionados = "Todos";
	}
	else if (pessoas_selecionadas.length == 0) {
		texto_pessoas = "Ninguém";
		num_selecionados = "Ninguém";
	}
	else {
		num_selecionados = pessoas_selecionadas.length + " sel.";
		
		pessoas_selecionadas.each(function(index, elemento) {
			texto_pessoas = texto_pessoas + trim($(elemento).parent().text());
			if (index < pessoas_selecionadas.length - 2) {
				texto_pessoas = texto_pessoas + ", ";
			}
			else if (index == pessoas_selecionadas.length - 2) {
				texto_pessoas = texto_pessoas + " e ";
			}
		});
	}

	pessoas_resumo.text(texto_pessoas);
	elemento_num_selecionados.text(num_selecionados);
}

function limpar_footer(id_pagina) {
	if (jQuery.inArray(id_pagina, ["cardapio", "pessoas", "conta", "pendentes"]) == -1) {
		id_pagina = "cardapio";
	}

	if (id_pagina == "pendentes") {
		id_pagina = "conta";
	}

	// <id_pagina>: [<botao_no_footer_pra_limpar>, <sub_menus_pra_esconder>]
	hash = {
		"cardapio": [".botao_pessoas_footer, .botao_conta_footer, .botao_outros_footer", ".sub_menu_conta, .sub_menu_outros"], 
		"pessoas": [".botao_cardapio_footer, .botao_conta_footer, .botao_outros_footer", ".sub_menu_conta, .sub_menu_outros"], 
		"conta": [".botao_pessoas_footer, .botao_cardapio_footer, .botao_outros_footer", ".sub_menu_conta, .sub_menu_outros"]
	}

	// Limpar submenu
	$(hash[id_pagina][1]).hide();

	// Limpar botoes no menu
	$(hash[id_pagina][0]).removeClass('ui-btn-active');
}

$('#conta, #pessoas, #cardapio, #pendentes').live("pagebeforeshow", function() {
	limpar_footer($(this).attr('id'));
});

$(function() {
	limpar_footer("cardapio");

	$('textarea[maxlength]').keyup(function(){  
	    //get the limit from maxlength attribute  
	    var limit = parseInt($(this).attr('maxlength'));  
	    //get the current text inside the textarea  
	    var text = $(this).val();  
	    //count the number of characters in the text  
	    var chars = text.length;  

	    //check if there are more characters then allowed  
	    if(chars > limit){  
	        //and if there are use substr to get the text before the limit  
	        var new_text = text.substr(0, limit);  

	        //and change the current text with the new text  
	        $(this).val(new_text);  
	    }  
	});  
});