<?php
require 'verifica.php';
include_once 'classes/design.php';

$design = new Design(".");
$design->imprimir_topo();
?>

<div class="titulo_secao">Ajuda</div><br/><br/>

<li><a href="#1">Configurando os tablets</a></li>
<li><a href="#4">Cadastrando/alterando mesas</a></li>
<li><a href="#5">Conferindo uma conta</a></li>
<li><a href="#6">Fechando uma conta</a></li>
<li><a href="#2">Cadastrando/alterando cardápio</a></li>
<li><a href="#3">Inserindo/alterando foto de item</a></li>

<br/>
<ul>
<li><a name="1" class="preto">Configurando os tablets</a>
<p/>
O gerente deve, primeiro, cadastrar as mesas de seu estabelecimento pela interface web. Feito isso, 
essas mesas estarão disponíveis para serem associadas aos tablets. Caso o tablet não esteja associado a nenhuma mesa, será pedido a senha do gerente. Em 
seguida, as mesas disponíveis estarão dispostas para a seleção e associação. Uma vez que um tablet é associado 
a uma mesa, essa mesa não estará mais disponível para outra associação.
</li>
<br/>
<li><a name="4" class="preto">Cadastrando/alterando mesas</a>
<p/>
Ao entrar na área de Controle de Mesas, são mostradas as mesas já cadastradas. Para inserir uma nova mesa, basta que se clique em "Nova mesa", preencha o seu nome 
e clique em "OK". A partir desse momento, essa mesa estará disponível para associação nos tablets.
<p/>
Ao clicar no nome da mesa na lista, será mostrado o histórico de contas nessa mesa (incluindo alguma conta aberta, se houver), além de links para alterar seu nome 
(ícone de lápis) e excluí-la (ícone de x vermelho).
<p/>
<b><font color="#ff0000">Obs.:</font> A mesa só poderá ser alterada ou excluída se não houver conta aberta na mesma.</b><br/>  
</li>
<br/>
<li><a name="5" class="preto">Conferindo uma conta</a>
<p/>
Ao entrar na página de alguma mesa (como visto no ponto anterior), é mostrada a lista de contas daquela mesa. Para ver a 
conta, basta clicar na desejada. Para cada conta são mostradas a hora de abertura da mesma, hora de fechamento (se a conta já 
estiver fechada), e a lista dos pedidos realizados. A lista de pessoas na conta só é disponibilizada no tablet.
</li>
<br/>
<li><a name="6" class="preto">Fechando uma conta</a>
<p/>
Para fechar uma conta, o usuário deve entrar na página de informações dessa conta (como visto no ponto anterior), e clicar no link 
"Fechar conta". Obviamente, esse link só aparece quando se vê a página de uma conta ainda aberta. Ao se fechar a conta, os pedidos pendentes 
para aquela conta são removidos, além das informações de pessoas e pedidos no tablet. Ou seja, só se deve fechar uma conta após a conclusão do pagamento 
da conta e a ida das pessoas da mesa. 
</li>
<br/>
<li><a name="2" class="preto">Cadastrando/alterando cardápio</a>
<p/>
<b><font color="#ff0000">Obs.:</font> O cardápio não pode ser alterado enquanto houver alguma conta aberta no estabelecimento.</b>
<p/>
Ao entrar na área de Controle de Cardápio, são mostradas as categorias já cadastradas no cardápio do estabelecimento. Os itens são cadastrados pertencendo 
a categorias, ou seja, pelo menos uma categoria obrigatoriamente tem que ser cadastrada. Para criar uma nova categoria, basta clicar em "Nova categoria", 
preencher seu nome (obrigatório) e clicar em "OK".
<p/>
Clicando em alguma categoria, o usuário poderá ver as subcategorias e itens cadastrados naquela categoria. Para cadastrar uma nova subcategoria, mais uma vez 
basta que o usuário clique em "Nova subcategoria", preencha o nome (obrigatório) e clique em "OK". Para cadastrar um item nessa categoria, o usuário deve clicar 
em "Novo item", preencher suas informações (sendo o nome do item e seu preço informações obrigatórias), e clicar em "OK".
<p/>
Para alterar ou excluir uma categoria ou item, o usuário deve clicar no ícone de lápis ao seu lado para alterar, e no x vermelho para excluir.
</li>
<br/>
<li><a name="3" class="preto">Inserindo/alterando foto de item</a>
<p/>
Ao se cadastrar um novo item, ele fica inicialmente sem foto. Para inserir ou alterar a foto de um item, você deve clicar na sua foto, na lista de itens da categoria. 
Será aberta uma janela com a foto maior, e um campo para a escolha de uma nova foto. Depois de escolher, a foto será mostrada ao lado, <b>mas ainda não terá sido salva</b>. 
Para salvar, o usuário deverá clicar em "Salvar". Clicando em "Cancelar", a foto não será alterada.
</li>
</ul>

<?php 
$design->imprimir_fim();
?>
