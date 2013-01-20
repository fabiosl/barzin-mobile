<div data-role="page" id="pessoas" data-theme="a" >

	<?php echo Layout::imprimir_header($nome_bar, $nome_mesa, "Pessoas"); ?>


	<div data-role="content">
		
		<a href="#" class="atualizar" id="atualizar_pessoas">Atualizar pessoas</a>
		
		<p/>

		<div id="lista_pessoas_container">
			<span id="msg_pessoas" style="display: none;">
				O sistema será liberado quando ao menos uma pessoa for adicionada, o que pode ser feito no campo abaixo.
			</span>
			<ul data-role="listview" data-inset="true" data-split-icon="minus" id="lista_pessoas"></ul>
		</div>

        <p/>


        <div data-role="controlgroup">
            <label for="nova_pessoa">
                Nome da nova pessoa:
            </label>
            <input type="hidden" id="ultima_atualizacao_pessoas" value="0" />
            <input  name="" id="nova_pessoa" placeholder="" value="" type="text" maxlength="15"/>
            <a data-role="button" href="#" data-icon="plus" data-iconpos="right" data-theme="c" id="botao_adicionar_pessoa">
	            Adicionar
	        </a>
        </div>
        

    </div>


    <?php echo Layout::imprimir_footer("pessoas"); ?>
	
</div>

<script type="text/javascript">
var raiz_requisicao = "<?php echo Requisicoes::raiz_frontend; ?>";
var codigo_mesa = "<?php echo $codigo_mesa; ?>";

function setar_botoes_excluir() {
	$('.excluir_pessoa').click(function(e) {
		e.preventDefault();

		var ultima_atualizacao_pessoas = $('#ultima_atualizacao_pessoas').val();
		var nome_pessoa = $(this).data('nomepessoa');
		var id_pessoa = $(this).data("idpessoa");
		
		if (!confirm("Tem certeza que quer remover " + nome_pessoa + " da mesa?")) {
			return false;
		}

		$.mobile.loading('show', {
									text: "Carregando", 
									textVisible: true,
									theme: 'a'
									});
		$.post(
			'<?php echo Requisicoes::raiz_frontend; ?>pessoas/remover_pessoa.php?', 
			{
				'id_pessoa': id_pessoa, 
				'random': Math.random()
			}, 
			function(retorno) {
				$.mobile.loading('hide');
				if (retorno == "ok") {
					atualizar_pessoas(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas);
				}
				else {
					alert("Não foi possível excluir pessoa: " + retorno);
				}
			}, 
			"text"
		);
	});
}

$(function() {
	$('#atualizar_pessoas').click(function(e) {
		var ultima_atualizacao_pessoas = $('#ultima_atualizacao_pessoas').val();

		atualizar_pessoas(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas);
	});

	$('#botao_adicionar_pessoa').click(function() {
		var nova_pessoa = $('#nova_pessoa').val();

		if (nova_pessoa.length < 3) {
			alert('O nome da pessoa deve ter no mínimo 3 caracteres');
		}
		else {
			$.mobile.loading('show', {
									text: "Carregando", 
									textVisible: true,
									theme: 'a'
									});
			$.post(
				raiz_requisicao + 'pessoas/adicionar_pessoa.php?', 
				{
					'codigo_mesa': "<?php echo $codigo_mesa; ?>", 
					'nome_pessoa': nova_pessoa, 
					'random': Math.random()
				}, 
				function(retorno) {
					$.mobile.loading('hide');
					if (retorno.hasOwnProperty("erro")) {
						alert("Erro ao adicionar pessoa: " + retorno.erro);
					}
					else if (retorno.hasOwnProperty("pessoa")) {
						var ultima_atualizacao_pessoas = $('#ultima_atualizacao_pessoas').val();

						atualizar_pessoas(raiz_requisicao, codigo_mesa, ultima_atualizacao_pessoas);

						$('#nova_pessoa').val('');
					}
					else {
						alert(retorno);
					}
				}, 
				'json'
			);
		}
	});
});
</script>