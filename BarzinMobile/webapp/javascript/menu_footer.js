$('.botao_conta_footer, .botao_outros_footer').live("click", function() {
	var meuId = $(this).attr('id');

	hash = {
		"botao_conta_footer": {
								"meu_sub_menu": ".sub_menu_conta", 
								"outro_sub_menu": ".sub_menu_outros"
								}, 
		"botao_outros_footer": {
								"meu_sub_menu": ".sub_menu_outros", 
								"outro_sub_menu": ".sub_menu_conta"
								}
	}

	

	$(hash[meuId]["outro_sub_menu"]).hide();
	$(hash[meuId]["meu_sub_menu"] + ' a').removeClass('ui-btn-active');
	$(hash[meuId]["meu_sub_menu"]).toggle();

	var eh_pra_remover_classe = $(this).closest('.footer').find(hash[meuId]["meu_sub_menu"]).is(':hidden')

	if (eh_pra_remover_classe) {
		var elemento = $(this);
		window.setTimeout(function() {
			elemento.removeClass('ui-btn-active');
		}, 1);
	}
});

$('.botao_cardapio_footer').live("click", function() {
	limpar_footer("cardapio");
});

$('.botao_pessoas_footer').live("click", function() {
	limpar_footer("pessoas");
});