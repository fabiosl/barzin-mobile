try { 
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); 
} catch (e) { 
    try { 
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); 
    } catch (E) { 
        xmlhttp = false; 
    } 
} 

if  (!xmlhttp && typeof  XMLHttpRequest != 'undefined' ) { 
    try  { 
        xmlhttp = new  XMLHttpRequest(); 
    } catch  (e) { 
        xmlhttp = false ; 
    }
} 

if(!Array.indexOf){
    Array.prototype.indexOf = function(obj){
        for(var i=0; i<this.length; i++){
            if(this[i]==obj){
                return i;
            }
        }
        return -1;
    }
}

function zebrarTodasTabelas() {
	var tabelas = document.getElementsByTagName("table");
	for (var i = 0; i < tabelas.length; i++) {
		if (tabelas[i].className == 'zebra') {
			zebrarTabela(tabelas[i]);
		}
	}
}

function zebrarTabela(tabela) {
	var linhas = tabela.getElementsByTagName("tr");
	var cinza = true;
	for (var i = 0; i < linhas.length; i++) {
		if (cinza) {
			linhas[i].style.backgroundColor = "#f3f3f3";
			cinza = false;
		}
		else {
			cinza = true;
		}
	}
}

function mostrar(id) {
	document.getElementById(id).style.display = "inline-block";
}

function esconder(id) {
	document.getElementById(id).style.display = "none";
}

function mostrar_tr(id) {
	document.getElementById(id).style.display = "";
}

function oQueTemNoCampo(objeto) {
	if (objeto.nodeName == "INPUT") {
		if (objeto.type == "text" || objeto.type == "hidden" || objeto.type == "password")
			return objeto.value;
		if (objeto.type == "radio") {
			var todos = document.getElementsByName(objeto.name);
			for (var i = 0; i < todos.length; i++) {
				if (todos[i].checked)
					return todos[i].value;
			}
		}
	}
	else if (objeto.nodeName == "TEXTAREA")
		return objeto.value;
	
	return "";
}

function erroNoCampo(objeto) {
	var campo = "";
	var ehOpcional = true;
	var tipo = "";
	var mascara = "";
	var opc = "";
	
	var array = objeto.className.split("|");
	campo = array[0];
	if (array.length > 1)
		opc = array[1];
	if (opc != "Opcional" && opc != "") 
		ehOpcional = false;
	if (array.length > 2)
		tipo = array[2];
	if (array.length > 3)
		mascara = array[3];
	
	var valor = oQueTemNoCampo(objeto);
	
	if (opc.indexOf("ObrigatorioCom") == 0) {
		var arrayAdicional = opc.split(".");
		var campoQueObriga = document.getElementsByName(arrayAdicional[1])[0];
		var valorQueObriga = arrayAdicional[2];
		var condicaoQueObriga = arrayAdicional[3];
		
		if (oQueTemNoCampo(campoQueObriga) == valorQueObriga && valor == "")
			return " - Como se trata de " + condicaoQueObriga + ", o campo " + campo + " é obrigatório\n";
		ehOpcional = true;
	}
	
	if (valor == "") {
		if (!ehOpcional)
			return " - Campo " + campo + " é obrigatório\n";
	}
	else {
		if (mascara.length > 0) {
			if (valor.length != mascara.length)
				return " - Campo " + campo + " preenchido incorretamente\n";
			
			numeros = "0123456789";
			letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			
			for (a = 0; a < mascara.length; a++) {
				if (mascara.charAt(a) == "#")
					continue;
				else if (mascara.charAt(a) == "L") {
					if (letras.indexOf(valor.charAt(a).toUpperCase()) == -1)
						return " - Campo " + campo + " preenchido incorretamente\n";
				}
				else if (mascara.charAt(a) == "N") {
					if (numeros.indexOf(valor.charAt(a)) == -1)
						return " - Campo " + campo + " preenchido incorretamente\n";	
				}
				else {
					if (valor.charAt(a) != mascara.charAt(a))
						return " - Campo " + campo + " preenchido incorretamente\n";
				}
			}
		}
		
		if (tipo == "SoNumeros") {
			numeros = "0123456789";
			for (b = 0; b < valor.length; b++) {
				if (numeros.indexOf(valor.charAt(b)) == -1)
					return " - Campo " + campo + " preenchido incorretamente\n";
			}
		}

		else if (tipo.indexOf("MenorOuIgual") == 0) {
			arrayAdicional = tipo.split(".");
			var outroNumero = parseFloat(arrayAdicional[1]);
			var numero = parseFloat(valor);
			if (numero > outroNumero)
				return " - O campo " + campo + " precisa ter valor menor ou igual a " + outroNumero + "\n";
		}
		
		else if (tipo.indexOf("Igual") == 0) {
			arrayAdicional = tipo.split(".");
			outroCampo = document.getElementsByName(arrayAdicional[1])[0];
			outroValor = oQueTemNoCampo(outroCampo);
			arrayOutro = outroCampo.className.split("|");
			nomeOutro = arrayOutro[0];
			if (valor != outroValor)
				return " - Os campos " + campo + " e " + nomeOutro + " devem ser iguais\n";
		}
		
		else if (tipo == "Usuario") {
			letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			caracteresPermitidos = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_.";
			if (letras.indexOf(valor.charAt(0).toUpperCase()) == -1)
				return " - O campo " + campo + " deve comecar com uma letra\n";
			else if (valor.length < 3)
				return " - O campo " + campo + " deve ter no minimo 3 caracteres\n";
			else {
				for (c = 0; c < valor.length; c++) {
					if (caracteresPermitidos.indexOf(valor.charAt(c).toUpperCase()) == -1)
						return " - O campo " + campo + " so pode conter letras, numeros, _ (sublinhado) e . (ponto)\n";
				}
			}
		}

		else if (tipo == "Senha") {
			caracteresPermitidos = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&_";
			if (valor.length < 3)
				return " - O campo " + campo + " deve ter no minimo 3 caracteres\n";
			else {
				for (var i = 0; i < valor.length; i++) {
					if (caracteresPermitidos.indexOf(valor.charAt(i).toUpperCase()) == -1)
						return " - O campo " + campo + " so pode conter letras, numeros, !, @, #, $, %, & e _\n";
				}
			}
		}
		
		else if (tipo.indexOf("Data") != -1 && valor != "") {
			diasNoMes = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
			elementos = valor.split("/");
			if (elementos.length != 3)
				return " - Campo " + campo + " preenchido incorretamente\n";
			else {
				dia = elementos[0];
				mes = elementos[1];
				ano = elementos[2];
				if (ano % 4 == 0)
					diasNoMes[1] = 29;
				if (dia < 1 || dia > diasNoMes[mes - 1])
					return " - Campo " + campo + " preenchido incorretamente\n";
				if (mes < 1 || mes > 12)
					return " - Campo " + campo + " preenchido incorretamente\n";
			}
		}
		
		else if (tipo == "Email") {
			var temArroba = false;
			var temPontoDepoisDaArroba = false;
			for (var i = 0; i < valor.length; i++) {
				if (valor.charAt(i) == '@') {
					if (temArroba)
						return " - Campo " + campo + " preenchido incorretamente\n";
					temArroba = true;
				}
				else if (valor.charAt(i) == '.' && temArroba)
					temPontoDepoisDaArroba = true;
			}
			if (!temArroba || !temPontoDepoisDaArroba)
				return " - Campo " + campo + " preenchido incorretamente\n";
		}
		
		else if (tipo.indexOf("ComprimentoMinimo") == 0) {
			var compMinimo = tipo.split(".")[1];
			if (valor.length < compMinimo)
				return " - O campo " + campo + " deve ter no minimo " + compMinimo + " caracteres\n";
		}
		
		if (tipo.indexOf("DataComparacao") == 0) {
			arrayAdicional = tipo.split(".");
			
			tempo = arrayAdicional[1];
			
			data = valor.split("/");
			dataComp = data[2] + "" + data[1] + "" + data[0];
			
			outroCampo = document.getElementsByName(arrayAdicional[2])[0];
			
			outraData = oQueTemNoCampo(outroCampo).split("/");
			outroDataComp = outraData[2] + "" + outraData[1] + "" + outraData[0];
		
			arrayOutro = outroCampo.className.split("|");
			nomeOutro = arrayOutro[0];
				
			if (tempo == "Antes" && dataComp >= outroDataComp)
				return " - O campo " + campo + " deve conter uma data anterior à do campo " + nomeOutro + "\n";
			else if (tempo == "Depois" && dataComp <= outroDataComp)
				return " - O campo " + campo + " deve conter uma data posterior à do campo " + nomeOutro + "\n";
		}
	}
		
	return "";	
}

function validar(idForm) {
	var primeiroObjeto = null;
	var erros = "";
	var form = document.getElementById(idForm);
	var tags = ['INPUT', 'TEXTAREA'];
	var numeros = "1234567890";
	for (var d = 0; d < form.length; d++) {
		if (tags.indexOf(form[d].nodeName) != -1) {
			if (erroNoCampo(form[d]) != "" && primeiroObjeto == null) {
				primeiroObjeto = form[d];
			}
			erros += erroNoCampo(form[d]);
		}
	}
	if (erros != "") {
		alert("Seu formulario nao foi submetido por causa dos seguintes erros:\n\n" + erros);
		primeiroObjeto.focus();
		return false;
	}
	return true;
}

function preencher(objeto, mascara, evento) {
	numeros = "1234567890";
	letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	if (mascara == "FLOAT") {
		var jaTemNumero = false;
		var jaTemPonto = false;
		deletar = [];
		for (var i = 0; i < objeto.value.length; i++) {
			var caractere = objeto.value.charAt(i);
			if (numeros.indexOf(caractere) != -1) {
				jaTemNumero = true;
			}
			else if (caractere == "," || caractere == ".") {
				if (jaTemPonto || !jaTemNumero) {
					deletar = deletar.concat([i]);
				}
				else {
					esquerda = objeto.value.substring(0, i);
					direita = objeto.value.substring(i + 1);
					objeto.value = esquerda + "." + direita;
					jaTemPonto = true;
				}
			}
			else if (numeros.indexOf(caractere) == -1) {
				deletar = deletar.concat([i]);	
			}
		}
		objeto.value = deletarDaString(objeto.value, deletar);
	}
	
	else if (mascara == "EMAIL") {
		var jaTemArroba = false;
		deletar = [];
		for (var i = 0; i < objeto.value.length; i++) {
			if (objeto.value.charAt(i) == '@') {
				if (jaTemArroba)
					deletar = deletar.concat([i]);
				jaTemArroba = true;
			}
		}
		objeto.value = deletarDaString(objeto.value, deletar);
	}
	
	else {
		unicode = evento.keyCode? evento.keyCode : evento.charCode;
		if (unicode != 8 && unicode != 46 && unicode != 9 && unicode != 16) {
			deletar = [];
			for (var e = 0; e < objeto.value.length; e++) {
				objeto.value = objeto.value.substring(0, mascara.length);
				if (mascara.charAt(e) == '#')
					continue;
				else if (mascara.charAt(e) == 'L' || mascara.charAt(e) == 'M') {
					if (letras.indexOf(objeto.value.charAt(e).toUpperCase()) == -1) {
						deletar = deletar.concat([e]);
					}
					else {
						esquerda = objeto.value.substring(0, e);
						direita = objeto.value.substring(e + 1);
						objeto.value = esquerda + objeto.value.charAt(e).toUpperCase() + direita;
					}
				}
				else if (mascara.charAt(e) == 'N') {
					if (numeros.indexOf(objeto.value.charAt(e)) == -1) {
						deletar = deletar.concat([e]);
					}
				}
				else if (objeto.value.charAt(e) != mascara.charAt(e)) {
					esquerda = objeto.value.substring(0, e);
					direita = objeto.value.substring(e);
					objeto.value = esquerda + mascara.charAt(e) + direita;
				}
			}
			objeto.value = deletarDaString(objeto.value, deletar);
			objeto.focus();
		}
	}
}

function deletarDaString(string, indices) {
	for (var i = 0; i < indices.length; i++) {
		var indice = indices[i];
		if (indice < string.length) {
			esquerda = string.substring(0, indice);
			direita = string.substring(indice + 1);
			string = esquerda + direita;
			for (var j = i + 1; j < indices.length; j++)
				indices[j] = indices[j] - 1;
		}
	}
	return string;
}

function menu(escrever) {
	div = document.getElementById("textoMenu");
	div.innerHTML = escrever;
}