<div data-role="page" id="conta" data-theme="a" >
	
	<?php echo Layout::imprimir_header("Conta", $nome_mesa, $nome_bar, $codigo_mesa); ?>

	<div data-role="content">

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
			<span class="laranja">R$ <span id="total"></span> <span id="valor_taxa" class="texto-pequeno" style="color: #777777;"></span></span>
			<div class="texto-pequeno">(<span id="pessoas_resumo"></span>)</span>
		</h2>
	
		<div style="display: none;" id="total_escondido"></div>

		<div style="float: left; margin-right: 10px; margin-top: 10px;">Taxa:</div>
		<select data-inline="true" data-mini="true" id="taxa" style="float: left; display: inline-block;">
			<option value="0">Sem taxa</option>
			<option value="5">5%</option>
			<option value="10" selected="selected">10%</option>
			<option value="15">15%</option>
			<?php
				for ($i = 20; $i <= 100; $i += 10) { 
			    	echo "<option value=\"$i\">$i%</option>";
			    }
			?>
		</select>

		<div id="pedidos_atendidos" style="margin-top: 20px;">
			Pedidos Atendidos
			<table data-role="table" id="lista_pedidos_atendidos" data-mode="reflow" class="tabela_pedidos_atendidos texto-pequeno">
			 	<thead>
			    	<tr>
				      <th>Item</th>
				      <th>Qtd.</th>
				      <th>Hora</th>
				      <th>Estado</th>
				      <th>Preço Unid.</th>
				      <th>Valor</th>
			    	</tr>
			  	</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
    </div>

    <?php echo Layout::imprimir_footer("conta"); ?>
	
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

	atualizar_conta_frontend();
});

$('#marcar_todos').click(function() {
	$(this).siblings('fieldset').find('input[type=checkbox]').attr('checked', 'checked');
	$('input:checkbox').checkboxradio('refresh');

	atualizar_conta_frontend();
});

$('#desmarcar_todos').click(function() {
	$(this).siblings('fieldset').find('input[type=checkbox]').removeAttr('checked');
	$('input:checkbox').checkboxradio('refresh');

	atualizar_conta_frontend();
});

$('.checkbox_pessoa_conta').live("change", function() {
	atualizar_conta_frontend();
});

$('#atualizar_conta').click(function() {
	var ultima_atualizacao_pedidos = $('#ultima_atualizacao_pedidos').val();
	atualizar_pedidos(raiz_requisicao, codigo_mesa, ultima_atualizacao_pedidos);
});

$('#taxa').live("change", function () {
	atualizar_total_com_taxa();
});
</script>