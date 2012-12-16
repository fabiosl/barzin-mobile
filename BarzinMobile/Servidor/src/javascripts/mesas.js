function prepararFecharMesa(idMesa, nomeMesa, numeroPendentes) {
	var msg = 'Tem certeza que deseja fechar a mesa ' + nomeMesa + '?';
	if (numeroPendentes == 1) {
		msg += '\nA conta ainda possui ' + numeroPendentes + ' pedido pendente, que será apagado.';
	}
	else if (numeroPendentes > 1) {
		msg += '\nA conta ainda possui ' + numeroPendentes + ' pedidos pendentes, que serão apagados.';
	}
	
	msg += '\n\nDeseja continuar?';
	if (confirm(msg)) {
		fecharMesa(idMesa);
	}
}

function fecharMesa(idMesa) {
	xmlhttp.open("GET", "../scripts/servicos_mesa.php?operacao=excluir&id=" + idMesa, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				location.href = '../mesas/mesa.php?id=' + idMesa + '&msg=Mesa fechada com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar('link_fechar');
				esconder('carregando_fechar');
			}
		} else {
			esconder('link_fechar');
			mostrar('carregando_fechar');
		}
	};
	xmlhttp.send(null);
	return false;
}

function prepararCriarMesa() {
	esconder('link_nova_mesa');
	mostrar('nova_mesa');
}

function cancelarCriarMesa() {
	var formulario = document.getElementById('form_nova_mesa');
	formulario.nome.value = "";
	esconder('nova_mesa');
	mostrar('link_nova_mesa');
}

function criarMesa() {
	if (!validar('form_nova_mesa')) {
		return false;
	}
	
	var formulario = document.getElementById('form_nova_mesa');
	var nome = formulario.nome.value;
	var bar_id = formulario.bar_id.value;
	var url = "../scripts/servicos_mesa.php?operacao=criar&nome=" + nome + "&bar_id=" + bar_id;
	xmlhttp.open("get", url, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				window.location.href = 'index.php?msg=Mesa criada com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar('link_nova_mesa');
				esconder('salvando_nova_mesa');
			}
		} else {
			esconder('nova_mesa');
			mostrar('salvando_nova_mesa');
		}
	};
	xmlhttp.send(null);
	return false;	
}

function prepararEditarMesa(idMesa) {
	mostrar('form_mesa');
	esconder('links_mesa');
}

function cancelarEditarMesa(nomeMesa) {
	mostrar('links_mesa'); 
	esconder('form_mesa');
}

function editarMesa() {
	if (!validar('form_mesa')) {
		return false;
	}
	
	var nomeMesa = document.getElementById('nome_mesa');
	var formulario = document.getElementById('form_mesa');
	var idMesa = formulario.id.value;
	var novoNome = formulario.nome.value;
	xmlhttp.open("get", "../scripts/servicos_mesa.php?operacao=alterar&id=" + idMesa + "&nome=" + novoNome, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				nomeMesa.innerHTML = novoNome;
				var excluir = document.getElementById('nomeMesa');
				excluir.innerHTML = novoNome;
				esconder('carregando_mesa');
				mostrar('links_mesa');
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar('links_mesa');
			}
		} else {
			esconder('form_mesa');
			mostrar('carregando_mesa');
		}
	};
	xmlhttp.send(null);
	return false;
}

function prepararExcluirMesa(idMesa, numeroContas) {
	var nomeMesa = document.getElementById('nomeMesa').innerHTML;
	var msg = 'Tem certeza que deseja excluir a mesa ' + nomeMesa + '?';
	if (numeroContas > 0 ) {
		msg += '\nA mesa possui:';
		if (numeroContas > 0) {
			if (numeroContas == 1) {
				msg += '\n- 1 conta registrada, que também será excluída.';
			}
			else {
				msg += '\n- ' + numeroContas + ' contas registradas, que também serão excluídas.';
			}
		}
	}
	msg += '\n\nDeseja continuar?';
	if (confirm(msg)) {
		excluirMesa(idMesa);
	}
}

function excluirMesa(idMesa) {
	xmlhttp.open("get", "../scripts/servicos_mesa.php?operacao=excluir_mesa&id=" + idMesa, true);
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 & xmlhttp.status == 200) {
			if (xmlhttp.responseText == "ok") {
				location.href = 'index.php?msg=Mesa excluída com sucesso';
			}
			else {
				alert("Sua operação não foi completada por causa do seguinte erro no banco de dados:\n\n" + xmlhttp.responseText);
				mostrar('links_mesa');
				esconder('carregando_mesa');
			}
		} else {
			esconder('form_mesa');
			esconder('links_mesa');
			mostrar('carregando_mesa');
		}
	};
	xmlhttp.send(null);
	return false;
}