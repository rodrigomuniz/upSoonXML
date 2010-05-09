<?php
/*
 * UpSoonXML v 0.1
 * http://wenetus.com
 *
 * Copyright 2010, Rodrigo Muniz - Wenetus Interactive
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */

require_once('functions.php');

// ** CONFIGURACOES ** //
/** 1. Nome do arquivo XML que sera usado como db para armazenar os assinantes */
/** IMPORTANTE: por questoes de seguranca voce DEVE renomear o arquivo para um nome que nao seja facil de descobrir e informar o novo nome abaixo */
$dbname = "subscribers.xml";

/** 2. Chave de seguranca */
/** Acesse http://www.adamek.biz/md5-generator.php e gere sua chave */
/** Usado para que POSTs externos nao consigam inserir nada no seu xml */
$yourkey = "5989f2c25d559f0acf08e541944182dd";

/** 3. Nome do site ou da empresa */
$sitename = "ACME";

/** 4. Campo de Nome obrigatorio? */
/** false = (Opcional) true = (Obrigatorio) */
$nreq = false;

/** 5. Mostrar Logotipo? */
/** false = (Oculta) true = (Mostra) */
$logo = true;

/** Pronto, pode parar de editar o PHP */

// mensagens
$e = false;
$sent = false;

//checa se xml ta pronto
if(!file_exists($dbname)) {
	$e .= "<li>ATENÇÃO. O arquivo XML para armazenar os assinantes não foi encontrado.</li>";
} else { //xml encontrado
		$secret = auth_token($yourkey);
	if(isset($_POST['send'])) {
		$token = $_POST['token'];
	if(is_token_valid($token, $yourkey)) {
		$email = trim($_POST['email']);
		$name = trim($_POST['name']);
		$xml = new SimpleXMLElement($dbname, 0, true);
		
		//email em branco?
		if($email == "") {
			$e.= "<li>Por favor, preencha o campo de e-mail</li>";
		} else { //email preenchido
			//email valido?
			if( !check_email_address($email) ){
				$e .= "<li>Por favor, verifique a digitação. <strong>$email</strong> não é um endereço de e-mail válido.</li>";
			}

			//email ja cadastrado?
			foreach($xml->user as $u) {
				if($email == $u->email) {
					$e.= "<li>O email <strong>$email</strong> já consta na nossa lista e será avisado.</li>";
				}
			}
		}

		//nome em branco?
		if ($nreq==true) {//opcao de nome obrigatorio
			if($name == "") {
				$e.= "<li>Por favor, preencha o campo de nome</li>";
			}
		}

		//se nao ha erros entao adiciona o email e o nome
		if($e == false){
			$xml = new SimpleXMLElement($dbname, 0, true);
			$user = $xml[0]->addChild('user');
			$user->addChild('email', $email);
			$user->addChild('realname', $name);
			$user->addChild('time', time());
			$xml->asXML($dbname);

			$sent = true;
		}
	} //token
	}//se submeteu o form

}//se xml existe
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Novo site em breve... - <?=$sitename?></title>
<meta name="author" content="Wenetus Interactive - www.wenetus.com" />
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
<meta http-equiv="imagetoolbar" content="no" />

<? /*CSS no header para nao precisar de mais um arquivo no servidor*/ ?>
<style type="text/css">
html{
	border-top: 10px solid #f0f0f0;
}
body {
	background-color: #FFF;
	font-family: Helvetica, Arial, sans-serif;
	font-size: 19px;
	color:#555;
	margin: 0 auto;
	padding: 5px 0;
	text-align: center;
	max-width: 100%;
	_width: 960px;
}
img{border:0}
form {
	text-align: left;
	margin: 0 auto;
	padding: 25px 3px; 
	width: 450px;
		display: none; /*jquery*/
}
fieldset {
	border: 1px solid #ddd;
		-moz-border-radius: 10px;
		-khtml-border-radius: 10px;
		-webkit-border-radius: 10px;
		border-radius: 10px;
	padding: 20px 0;
}
legend{
	color: #aaa;
	text-align: center;
	margin: 0 auto;
	padding: 10px 0 0 0
}
fieldset ul {
	list-style: none;
	padding: 0;
	margin: 0
}
fieldset li {
	float: left;
	clear: both;
	padding:6px 0;
	position: relative;
	width: 100%
}
fieldset label {
	display: block;
	font-size: .8em;
	font-weight: bold;
	text-align: right;
	padding: 6px 4px 0 0;
	width: 100px
}
fieldset label, input {
	float: left;
	vertical-align: middle;
}
#email, #name {
	font-size: .9em;
	width: 260px
}
form .sub {	
	clear: both;
	display: block;
	float: right;
	cursor: pointer;
	font-size: 12px;
	font-weight: bold;
	margin-right: 73px
}
#alert {
	border-bottom: 4px solid #f0f0f0;
	background-color:#ffd55c;
	color: #000;
	font-size: .9em;
	margin: 0 auto;
	padding: 10px;
	padding-bottom: 0;
	position: fixed;
	top:0;
	left: -180px;
	margin-left: 50%;
	text-align: left;
	width: 360px;
	cursor: pointer
}
ul#alert {list-style: none}
ul#alert li {padding-bottom: 10px}
.priv {
	font-size: 12px;
	padding: 6px 30px 6px 103px;
	margin: 0 auto;
	width: 311px;
	background: #f6f6f6;
	float: left;
	clear: both;
	display: none; /*jquery*/
}
h1, h2 {
	font-family: 'Helvetica Neue UltraLight', 'HelveticaNeue-UltraLight', 'Century Gothic', Helvetica, Arial, sans-serif;
	font-weight: normal;
	margin: -5px 0 -10px 0;
	padding: 0;
}
h1 {
	color: #aaa;
	font-size: 65px;
	text-transform: uppercase;
}
h2{font-size: 28px}
h2 strong {
	color: #000;
}
.opc {display: none; /*jquery*/}
.hint {
	display: block;
	position: absolute;
	top: 14px;
	right: 84px;
	font-size: 12px;
	cursor: text;
	color: #8e8e8e;
	z-index: 100;
}

.form-e {display:block}
</style>

</head>

<body>

<?php if($e){ //escreve mensagens de erro ?>
	<ul id="alert" title="Fechar">
	<?=$e?>
	</ul>
<? } ?>

<?php if($logo){ //insere logo ?>
	<img src="logo.png" alt="Logotipo" />
<? } ?>

<h1>Falta pouco!</h1>
	<h2>O novo site <strong><?=$sitename?></strong> está quase pronto.</h2>

<?php if($sent==false) { //oculta form depois de assinado ?>

<form action="index.php" method="post"<?php if($e){?> class="form-e"<? } ?>>
	<fieldset>
	<legend>Quer ser avisado quando o site for lançado?</legend>

	<ul>
		<li>
			<label for="email">E-mail</label>
			<input type="text" name="email" id="email" value="<?=$email?>" />
			<p class="priv">Garantimos usar seu e-mail apenas para o aviso. Também odiamos SPAM.</p>
		</li>
		<li>
			<label for="name">Nome <? if($nreq==false) {?><span class="opc">(Opcional)</span><? } ?></label>
			<input type="hidden" name="token" value="<?=$secret;?>" />
			<input type="text" name="name" id="name" value="<?=$name?>" />
		</li>
	</ul>
	<input type="submit" name="send" value="Sim. Quero ser avisado." class="sub" />
	</fieldset>
</form>

<? } else { // mensagem de sucesso ?>
	<p>Oba! Você está inscrito e será avisado no lançamento.</p>
<? } ?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

<?php if(!$e){ //animacao inicial, nao executa quando a validacao retorna erro ?>
function toFade() {//leva o bg para o pixel 1024
	$(".priv").animate({opacity: '+=-0.5'}, 2000, function() {
			resetFade();
	});
}
function resetFade(){//seta o bg para o lugar de origem e reinicia a animacao
	$(".priv").animate({opacity: '+=1.5'}, 1000, function(){
	toFade();
});
}

$("form").slideDown(600,function(){
	$(".priv").slideDown(400, function(){
			toFade();//inicia respiracao
	});
});
<? } ?>
	
<?php if($e){ //escreve mensagens de erro ?>
$("#alert").animate({left: '+=-16'}, 70)
	.animate({left: '+=8'}, 70)
	.animate({left: '+=-8'}, 70)
	.animate({left: '+=8'}, 70)
	.animate({left: '+=-8'}, 70)
	.animate({left: '+=4'}, 70);
$("#alert").click(function(){
	$(this).fadeOut('fast');
	$('#email').focus();
});
<? } ?>

<? if($nreq==false) {//hint ?>
$(".opc").addClass('hint').click(function(){
	$("#name").focus();
});

if($("#name").val().length == 0){
	$(".opc").addClass('hint');
}
$("#name").ready(function(){
	if($("#name").val().length > 0) {
		hideHint();
	}
});

function hideHint(){$(".hint").fadeOut();}
function showHint(){$(".hint").fadeIn();}

$("#name").focus(function(){
	if($("#name").val().length == 0) {
		$(".hint").css('opacity', 0.4);
	}
});
$("#name").blur(function(){
	if($("#name").val().length == 0) {
		showHint();
	}
});
$("#name").keyup(function(){
	if($("#name").val().length == 0) {
		showHint();
	}
	if($("#name").val().length > 0) {
		hideHint();
	}
});

<? } ?>
});
</script>
</body>
</html>