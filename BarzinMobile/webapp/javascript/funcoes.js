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
    			atualizar_conta();
	        	$('#ultima_atualizacao_pedidos').val(retorno.ultima_atualizacao_pedidos);
			}
		}, 
		'json'
	);
}

function atualizar_conta() {
	var num_selecionados = "";
	var texto_pessoas = "";
	var num_pessoas_na_mesa = $('#pessoas_marcar_conta').children().size();

	var pessoas_selecionadas = $('#pessoas_marcar_conta').find('input:checkbox:checked');

	if (pessoas_selecionadas.length == num_pessoas_na_mesa) {
		texto_pessoas = "Todas as pessoas";
		num_selecionados = "Todos selecionados";
	}
	else if (pessoas_selecionadas.length == 0) {
		texto_pessoas = "Ninguém";
		num_selecionados = "Nenhum selecionado";
	}
	else {
		if (pessoas_selecionadas.length == 1) {
			num_selecionados = "1 selecionado";
		}
		else {
			num_selecionados = pessoas_selecionadas.length + " selecionados";
		}
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

	$('#pessoas_resumo').text(texto_pessoas);
	$('#num_selecionados').text(num_selecionados);

	var pedidos_filtrados = [];
	var pedidos_pendentes = [];

	for (var i = 0; i < pedidos.length; i++) {
		var pedido = pedidos[i];
		var quantidade_por_pessoa = parseInt(pedido.quantidade) / pedido.pessoas.length;
		for (var j = 0; j < pedido.pessoas.length; j++) {
			pessoa_pedido = pedido.pessoas[j];
			for (var k = 0; k < pessoas_selecionadas.length; k++) {
				if ($(pessoas_selecionadas[k]).val() == pessoa_pedido.id) {
					var id = pedido.id + "";
					if (pedido.estado == "Atendido") {
						if (indice_elemento_com_id_no_array(id, pedidos_filtrados) == -1) {
							var array = [];
							array["id"] = id;
							array["pedido"] = pedido;
							array["quantidade"] = 0;
							pedidos_filtrados.push(array);
						}
						var indice = indice_elemento_com_id_no_array(id, pedidos_filtrados);
						pedidos_filtrados[indice]["quantidade"] += quantidade_por_pessoa;
					}
					else if (pedido.estado == "Pendente") {
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

		total += parcela;
		
    	$('#lista_pedidos_atendidos').children('tbody').append('' + 
															'<tr>' +
																'<td>' + pedido_filtrado["pedido"].item + '</td>' + 
																'<td align="center">' + quantidade + '</td>' + 
																'<td align="center">' + pedido_filtrado["pedido"].hora + '</td>' + 
																'<td align="center" nowrap>R$ ' + parseFloat(pedido_filtrado["pedido"].preco_item).toFixed(2) + '</td>' + 
																'<td align="center" nowrap>R$ ' + parcela.toFixed(2) + '</td>' + 
															'</tr>');
    	$('#lista_pedidos_atendidos').trigger('create');
	}

	$('#lista_pedidos_pendentes').children('tbody').empty();

	if (pedidos_pendentes.length == 0) {
		$('#pedidos_pendentes').hide();
	}
	else {
		$('#pedidos_pendentes').show();
	}

	for (var indice in pedidos_pendentes) {
		var pedido = pedidos_pendentes[indice]["pedido"];

		var pessoas = "";
		for (var i = 0; i < pedido.pessoas.length; i++) {
			if (i != 0) {
				pessoas += '<br/>';
			}
			pessoas += (i + 1) + ". " + pedido.pessoas[i].nome;
		};
		$('#lista_pedidos_pendentes').children('tbody').append('' + 
															'<tr>' + 
																'<td>' + pedido.item + '</td>' + 
																'<td align="center">' + pedido.quantidade + '</td>' + 
																'<td align="center">' + pedido.hora + '</td>' + 
																'<td align="center" nowrap>R$ ' + (parseFloat(pedido.preco_item) * parseInt(pedido.quantidade)).toFixed(2) + '</td>' + 
																'<td>' + pessoas + '</td>' + 
															'</tr>');
		$('#lista_pedidos_pendentes').trigger('create');
	}
						

	$('#total').text(total.toFixed(2));

}