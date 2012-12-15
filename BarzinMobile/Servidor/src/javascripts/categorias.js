function prepararEditarCategoria(idCategoria) {
	mostrar('form_categoria');
	esconder('links_categoria');
}

function cancelarEditarCategoria(nomeCategoria) {
	mostrar('links_categoria'); 
	esconder('form_categoria');
	var formulario = document.getElementById('form_categoria');
	formulario.nome.value = nomeCategoria;
}

function editarCategoria() {
	if (!validar('form_categoria')) {
		return false;
	}
	
	var nomeCategoria = document.getElementById('nome_categoria');
	var formulario = document.getElementById('form_categoria');
	var idCategoria = formulario.id.value;
	var novoNome = formulario.nome.value;
	xmlhttp.open("get", "../scripts/servicos_categoria.php?operacao=alterar&id=" + idCategoria + "&nome=" + novoNome, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				nomeCategoria.innerHTML = novoNome;
				esconder('carregando_categoria');
				mostrar('links_categoria');
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar('links_categoria');
			}
		} else {
			esconder('form_categoria');
			mostrar('carregando_categoria');
		}
	};
	xmlhttp.send(null);
	return false;
}

function prepararCriarSubcategoria(idCategoriaMae) {
	mostrar('form_nova_subcategoria');
	esconder('link_nova_subcategoria');
}

function cancelarCriarSubcategoria() {
	var formulario = document.getElementById('form_nova_subcategoria');
	formulario.nome.value = "";
	esconder('form_nova_subcategoria');
	mostrar('link_nova_subcategoria');
}

function criarSubcategoria() {
	if (!validar('form_nova_subcategoria')) {
		return false;
	}
			
	var formulario = document.getElementById('form_nova_subcategoria');
	var nome = formulario.nome.value;
	var idCategoriaMae = formulario.id_mae.value;
	xmlhttp.open("get", "../scripts/servicos_categoria.php?operacao=criar&nome=" + nome + "&categoria_mae_id=" + idCategoriaMae, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				location.href = '../cardapio/editar_categoria.php?id=' + idCategoriaMae + '&msg=Subcategoria criada com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				esconder('carregando_nova_subcategoria');
				mostrar('link_nova_subcategoria');
			}
		} else {
			esconder('form_nova_subcategoria');
			mostrar('carregando_nova_subcategoria');
		}
	};
	xmlhttp.send(null);
	return false;
}

function prepararCriarCategoria() {
	mostrar('form_nova_categoria');
	esconder('link_nova_categoria');
}

function cancelarCriarCategoria() {
	var formulario = document.getElementById('form_nova_categoria');
	formulario.nome.value = "";
	esconder('form_nova_categoria');
	mostrar('link_nova_categoria');
}

function criarCategoria() {
	if (!validar('form_nova_categoria')) {
		return false;
	}
			
	var formulario = document.getElementById('form_nova_categoria');
	var nome = formulario.nome.value;
	var barId = formulario.bar_id.value;
	xmlhttp.open("get", "../scripts/servicos_categoria.php?operacao=criar&nome=" + nome + "&bar_id=" + barId, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				location.href = '..//cardapio/index.php?msg=Categoria criada com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				esconder('carregando_nova_categoria');
				mostrar('link_nova_categoria');
			}
		} else {
			esconder('form_nova_categoria');
			mostrar('carregando_nova_categoria');
		}
	};
	xmlhttp.send(null);
	return false;
}

function prepararExcluirCategoria(idCategoria, nomeCategoria, numeroSubcategorias, numeroItens) {
	var msg = 'Tem certeza que deseja excluir a categoria ' + nomeCategoria + '?';
	if (numeroSubcategorias + numeroItens > 0 ) {
		msg += '\nA categoria possui:';
		if (numeroSubcategorias > 0) {
			msg += '\n- ' + numeroSubcategorias + ' subcategoria';
			if (numeroSubcategorias > 1) {
				msg += 's';
			}
			msg += ';';
		}
		if (numeroItens > 0) {
			msg += '\n- ' + numeroItens + ' ite';
			if (numeroItens == 1) {
				msg += 'm;';
			}
			else {
				msg += 'ns;';
			}
		}
		if (numeroSubcategorias + numeroItens > 1) {
			msg += '\nque também serão excluídos.';
		}
		else {
			msg += '\nque também será excluído.';
		}
	}
	msg += '\n\nDeseja continuar?';
	if (confirm(msg)) {
		excluirCategoria(idCategoria);
	}
}

function excluirCategoria(idCategoria) {
	xmlhttp.open("get", "../scripts/servicos_categoria.php?operacao=excluir&id=" + idCategoria, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				location.href = '../cardapio/index.php?msg=Categoria excluída com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar('links_categoria');
			}
		} else {
			esconder('form_categoria');
			esconder('links_categoria');
			mostrar('carregando_categoria');
		}
	};
	xmlhttp.send(null);
	return false;
}