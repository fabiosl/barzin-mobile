<div data-role="page" id="pendentes" data-theme="a" >

	<?php echo Layout::imprimir_header("Pedidos Pendentes", $nome_mesa, $nome_bar, $codigo_mesa); ?>

	<div data-role="content">

		<a href="#" class="atualizar" id="atualizar_conta_pendentes">Atualizar conta</a>

		<p/>

	    <div id="escolher_pessoas_pendentes" data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="e">
	    	<h3>
	    		Escolher Pessoas (<span id="num_selecionados_pendentes"></span>)
	    	</h3>
	    	Marcar: 
	    	<a href="#" data-role="button" data-mini="true" data-inline="true" data-theme="b" id="marcar_todos_pendentes">Todos</a>
			<a href="#" data-role="button" data-mini="true" data-inline="true" data-theme="b" id="desmarcar_todos_pendentes">Ninguém</a>
	    	<fieldset data-role="controlgroup" data-type="vertical" id="pessoas_marcar_pendentes"></fieldset> 
	    </div>

		<h2>
			Total: 
			<span class="laranja">R$ <span id="total_pendentes"></span></span>
			<div class="texto-pequeno">(<span id="pessoas_resumo_pendentes"></span>)</span>
		</h2>

		<div id="pedidos_pendentes" style="margin-top: 20px;">
			<ul data-role="listview" data-inset="true" id="lista_pedidos_pendentes"></ul>
		</div>
    </div>

    <?php echo Layout::imprimir_footer("pendentes"); ?>
	
</div>

<script type="text/javascript">
$('#pendentes').live("pagebeforeshow", function() {
	$('#escolher_pessoas_pendentes').trigger("collapse");

	$('#pessoas_marcar_pendentes').empty();

    $.each(pessoas, function(index, pessoa) {
    	$('#pessoas_marcar_pendentes').append('' +  
			    					'<label>' +
										'<input name="pessoas" type="checkbox" value="' + pessoa.id + '" class="checkbox_pessoa_pendentes"/>' + 
			        					pessoa.nome + 
			    					'</label>');
    });

	$('#pessoas_marcar_pendentes').find('input').attr('checked', true);

	$('#pessoas_marcar_pendentes').trigger('create');

	atualizar_pendentes_frontend(raiz_requisicao);
});

$('#marcar_todos_pendentes').click(function() {
	$(this).siblings('fieldset').find('input[type=checkbox]').attr('checked', 'checked');
	$('input:checkbox').checkboxradio('refresh');

	atualizar_pendentes_frontend(raiz_requisicao);
});

$('#desmarcar_todos_pendentes').click(function() {
	$(this).siblings('fieldset').find('input[type=checkbox]').removeAttr('checked');
	$('input:checkbox').checkboxradio('refresh');

	atualizar_pendentes_frontend(raiz_requisicao);
});

$('.checkbox_pessoa_pendentes').live("change", function() {
	atualizar_pendentes_frontend(raiz_requisicao);
});

$('#atualizar_conta_pendentes').click(function() {
	var ultima_atualizacao_pedidos = $('#ultima_atualizacao_pedidos').val();
	atualizar_pedidos(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos);
});

$('.cancelar_pedido_pendente').live("click", function() {
	var plural = "";
	if ($(this).data("quantidade") > 1) {
		plural = "s";
	}
	if (confirm("Tem certeza que deseja solicitar o cancelamento do pedido de " + $(this).data("quantidade") + " unidade" + plural + " de " + $(this).data("item") + " para " + $(this).data("pessoas") + "?")) {
		$.mobile.loading('show', {
									text: "Carregando", 
									textVisible: true,
									theme: 'a'
									});
		$.post(
			raiz_requisicao + "pedidos/solicitar_cancelamento.php", 
			{
				"pedido_id": $(this).data("id")
			}, 
			function (retorno) {
				$.mobile.loading('hide');
				if (retorno.hasOwnProperty("erro")) {
					alert(retorno.erro);
				}
				else {
					alert("Solicitação de cancelamento realizada com sucesso. Para que o pedido seja cancelado, o garçom ainda precisa aceitar a solicitação.");
				}
			}, 
			"json"
		);
	}
	return false;

});
</script>