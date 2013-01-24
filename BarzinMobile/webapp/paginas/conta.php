<div data-role="page" id="conta" data-theme="a" >
	
	<?php echo Layout::imprimir_header("conta"); ?>

	<div data-role="content">
		<h1>Conta</h1>

		<input type="hidden" id="ultima_atualizacao_pedidos" value="0" />

		<a href="#" class="atualizar" id="atualizar_conta">Atualizar conta</a>

		<p/>

	    <div id="escolher_pessoas" data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="e">
	    	<h3>
	    		Escolher Pessoas (<span id="num_selecionados"></span>)
	    	</h3>
	    	Marcar: 
	    	<a href="#" data-role="button" data-mini="true" data-inline="true" data-theme="b" id="marcar_todos">Todos</a>
			<a href="#" data-role="button" data-mini="true" data-inline="true" data-theme="b" id="desmarcar_todos">Ninguém</a>
	    	<fieldset data-role="controlgroup" data-type="vertical" id="pessoas_marcar_conta"></fieldset> 
	    </div>

		<h2>
			Total: 
			<span class="laranja">R$ <span id="total"></span></span>
			<div class="texto-pequeno">(<span id="pessoas_resumo"></span>)</span>
		</h2>

		<div id="pedidos_atendidos" style="margin-top: 20px;">
			Pedidos Atendidos
			<table data-role="table" id="lista_pedidos_atendidos" data-mode="reflow" class="tabela_pedidos_atendidos texto-pequeno">
			 	<thead>
			    	<tr>
				      <th>Item</th>
				      <th>Quant.</th>
				      <th>Hora</th>
				      <th>Preço Unid.</th>
				      <th>Subtotal</th>
			    	</tr>
			  	</thead>
				<tbody>
				</tbody>
			</table>
		</div>

		<div id="pedidos_pendentes" style="margin-top: 20px;">
			Pedidos Pendentes
			<table data-role="table" id="lista_pedidos_pendentes" data-mode="reflow" class="tabela_pedidos_pendentes texto-pequeno">
			 	<thead>
			    	<tr>
				      <th>Item</th>
				      <th>Quant.</th>
				      <th>Hora</th>
				      <th>Subtotal</th>
				      <th>Pessoas</th>
			    	</tr>
			  	</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
    </div>
	
</div>





<script type="text/javascript">
$('#conta').live("pagebeforeshow", function() {
	$('#escolher_pessoas').trigger("collapse");

	$('#pessoas_marcar_conta').empty();

    $.each(pessoas, function(index, pessoa) {
    	$('#pessoas_marcar_conta').append('' +  
			    					'<label>' +
										'<input name="pessoas" type="checkbox" value="' + pessoa.id + '" class="checkbox_pessoa_conta"/>' + 
			        					pessoa.nome + 
			    					'</label>');
    });

	$('#pessoas_marcar_conta').find('input').attr('checked', true);

	$('#pessoas_marcar_conta').trigger('create');

	atualizar_conta();
});

$('#marcar_todos').click(function() {
	$(this).siblings('fieldset').find('input[type=checkbox]').attr('checked', 'checked');
	$('input:checkbox').checkboxradio('refresh');

	atualizar_conta();
});

$('#desmarcar_todos').click(function() {
	$(this).siblings('fieldset').find('input[type=checkbox]').removeAttr('checked');
	$('input:checkbox').checkboxradio('refresh');

	atualizar_conta();
});

$('.checkbox_pessoa_conta').live("change", function() {
	atualizar_conta();
});

$('#atualizar_conta').click(function() {
	var ultima_atualizacao_pedidos = $('#ultima_atualizacao_pedidos').val();
	atualizar_pedidos(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos);
});
</script>