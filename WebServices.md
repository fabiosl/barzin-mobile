# bares/informacoes.php #

  * Recebe: id\_bar
  * Retorna: JSON contendo informações do bar

# bares/recuperar\_bares.php #

  * Recebe: nada
  * Retorna: JSON contendo um array com os ids e nomes de todos os bares cadastrados

# cardapio/recuperar\_cardapio.php #

  * Recebe: codigo\_mesa, versao\_cardapio (versão do cardápio que o dispositivo já tem)
  * Retorna: JSON do cardápio caso a versão do dispositivo não for a mais atual, 0 caso contrário

# cardapio/recuperar\_imagem\_item.php #

  * Recebe: id\_item
  * Retorna: Imagem do item

# cardapio/recuperar\_thumb\_item.php #

  * Recebe: id\_item
  * Retorna: Imagem do thumb do item

# contas/conta\_por\_pessoas.php #
  * Recebe: pessoas`[]` (ids das pessoas)
  * Retorna: JSON contendo o "total", os pedidos daquela pessoa (com a quantidade dela e a parcela)

# garcom/chamar\_garcom.php #
  * Recebe: codigo\_mesa
  * JSON do chamado, ou JSON de erro

# garcom/solicitar\_conta.php #
  * Recebe: codigo\_mesa
  * JSON da solicitação, ou JSON de erro

# mensagens/nova\_mensagem.php #

  * Recebe: codigo\_mesa, mensagem
  * Retorna: JSON de erro ou JSON da mensagem

# mensagens/recuperar\_mensagens.php #

  * Recebe: codigo\_mesa, ultima\_hora\_mensagem
  * Retorna: JSON de erro ou JSON contendo array de mensagens e ultima\_hora\_mensagem do banco

# mesas/conectar\_a\_mesa.php #

  * Recebe: codigo\_mesa, id\_bar
  * Retorna: JSON de erro ou JSON contendo mesa, nome\_bar e cardapio

# pedidos/novo\_pedido.php #

  * Recebe: item\_id, quantidade, comentario, pessoas`[]` (Pessoas tem que ser um array de ID de pessoas. Pra passar um array, é só colocar vários parametros com o mesmo nome, assim: pessoas`[]`)
  * Retorna: JSON do pedido ou JSON de erro

# pedidos/recuperar\_pedidos.php #

  * Recebe: codigo\_mesa, ultima\_atualizacao\_pedidos
  * Retorna: JSON de erro ou JSON contendo pedidos (propriedades já processadas) e ultima\_atualizacao\_pedidos

# pedidos/solicitar\_cancelamento.php #

  * Recebe: pedido\_id
  * Retorna: JSON do pedido ou JSON de erro

# pessoas/adicionar\_pessoa.php #
  * Recebe: nome\_pessoa, codigo\_mesa
  * Retorna: JSON da pessoa adicionada, ou JSON de erro

# pessoas/alterar\_pessoa.php #
  * Recebe: nome\_pessoa, id\_pessoa
  * Retorna: JSON da pessoa alterada, ou JSON de erro

# pessoas/pessoas\_na\_mesa.php #
  * Recebe: codigo\_mesa, ultima\_atualizacao
  * Retorna: "0" caso ultima\_atualizacao já seja a mais atual, JSON de array de pessoas caso haja dados mais atuais que ultima\_atualizacao, ou JSON de erro

# pessoas/remover\_pessoa.php #
  * Recebe: id\_pessoa
  * Retorna: "ok" se remover, string mostrando o erro caso contrário