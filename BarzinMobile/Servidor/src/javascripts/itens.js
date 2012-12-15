function prepararEditarItem(idItem) {
	mostrar_tr('editar_item_' + idItem);
	esconder('item_' + idItem);
}

function cancelarEditarItem(idItem) {
	mostrar_tr('item_' + idItem); 
	esconder('editar_item_' + idItem);
}

function editarItem(idItem) {
	if (!validar('form_editar_item_' + idItem)) {
		return false;
	}
	
	var formulario = document.getElementById('form_editar_item_' + idItem);
	var nome = formulario.nome.value;
	var preco = formulario.preco.value;
	var disponivel = formulario.disponivel.checked;
	var descricao = formulario.descricao.value;
	var categoria_id = formulario.categoria_id.value;
	xmlhttp.open("get", "../scripts/servicos_item.php?operacao=alterar&id=" + idItem + "&nome=" + nome + "&preco=" + preco + "&disponivel=" + disponivel + "&descricao=" + descricao + "&categoria_id=" + categoria_id, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				window.location.href = 'editar_categoria.php?id=' + categoria_id;
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				esconder('salvando_item_' + idItem);
			}
		} else {
			esconder('editar_item_' + idItem);
			mostrar_tr('item_' + idItem);
			mostrar('salvando_item_' + idItem);
		}
	};
	xmlhttp.send(null);
	return false;
}

function prepararExcluirItem(idItem, nomeItem, idCategoria) {
	var msg = 'Tem certeza que deseja excluir o item ' + nomeItem + '?';
	if (confirm(msg)) {
		excluirItem(idItem, idCategoria);
	}
}

function excluirItem(idItem, idCategoria) {
	xmlhttp.open("get", "../scripts/servicos_item.php?operacao=excluir&id=" + idItem, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				location.href = 'editar_categoria.php?id=' + idCategoria + '&msg=Item excluído com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar_tr('link_editar_item_' + idItem);
				mostrar_tr('link_excluir_item_' + idItem);
			}
		} else {
			mostrar('excluindo_item_' + idItem);
			esconder('link_editar_item_' + idItem);
			esconder('link_excluir_item_' + idItem);
		}
	};
	xmlhttp.send(null);
	return false;
}

function prepararCriarItem() {
	esconder('link_novo_item');
	mostrar('novo_item');
}

function cancelarCriarItem() {
	var formulario = document.getElementById('form_novo_item');
	formulario.nome.value = "";
	formulario.preco.value = "";
	formulario.disponivel.checked = false;
	formulario.descricao.value = "";
	esconder('novo_item');
	mostrar('link_novo_item');
}

function criarItem() {
	if (!validar('form_novo_item')) {
		return false;
	}
	
	var formulario = document.getElementById('form_novo_item');
	var nome = formulario.nome.value;
	var preco = formulario.preco.value;
	var disponivel = formulario.disponivel.checked;
	var descricao = formulario.descricao.value;
	var categoria_id = formulario.categoria_id.value;
	var url = "../scripts/servicos_item.php?operacao=criar&nome=" + nome + "&preco=" + preco + "&disponivel=" + disponivel + "&descricao=" + descricao + "&categoria_id=" + categoria_id;
	xmlhttp.open("get", url, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				window.location.href = 'editar_categoria.php?id=' + categoria_id + '&msg=Item criado com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar('link_novo_item');
				esconder('salvando_novo_item');
			}
		} else {
			esconder('novo_item');
			mostrar('salvando_novo_item');
		}
	};
	xmlhttp.send(null);
	return false;	
}
