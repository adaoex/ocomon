<?php 
print "<html><head><title>Configura��o do sistema</title>";

	print "<style type=\"text/css\"><!--";
	print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
	print "p{font-size:12px; text-align:justify; }";
	print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left;
		border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
	print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";
	print "--></STYLE>";
	print "</head><body class='corpo'>";

	print "AJUDA DO OCOMON - CONFIGURA��ES GERAIS";

	print "<p><b>Site para acesso ao Ocomon:</b></p>";
		print "<ul>";
		print "<li><p>Configure com o endere�o para acesso local ao Ocomon. Esse endere�o ser� utilizado pelas ".
				"vari�veis de ambiente do sistema. Exemplo: \"http://sua_intranet/ocomon\". N�o coloque o sinal \"/\" no ".
				"final do endere�o!</p></li>";
		print "</ul>";

	print "<p><b>Registros por p�gina:</b></p>";
		print "<ul>";
		print "<li><p>Quantidade de registros a serem exibidos nas telas que possuem bot�es de navega��o. ".
				"Por padr�o, 50 registros s�o exibidos.</p></li>";
		print "</ul>";

	print "<p><b>Configura��o de Upload de imagens nos chamados:</b></p>";
		print "<ul>";
		print "<li><p>TAMANHO M�XIMO: � o tamanho m�ximo (em bytes) do arquivo de imagem a ser feito o upload;</p></li>";
		print "<li><p>LARGURA M�XIMA: � a largura m�xima (em pixels) permitida para a imagem a ser feito o upload;</p></li>";
		print "<li><p>ALTURA M�XIMA: � a altura m�xima (em pixels) permitida para a imagem a ser feito o upload;</p></li>";
		print "</ul>";

	print "<p><b>Barra de formata��o de texto:</b></p>";
		print "<ul>";
		print "<li><p>Permite a utiliza��o de uma barra de forma��o para a edi��o de textos no mural de avisos e/ou nas telas ".
				"de edi��o de ocorr�ncias:</p> <img src='./img/toolbar.png'></li>";
		print "</ul>";

	print "<p><b>Categorias de problemas:</b></p>";
		print "<ul>";
		print "<li><p>� poss�vel criar at� 3 tipos de categorias para os tipos de problemas existentes no Ocomon. ".
				"Esse tipo de classifica��o facilita o agrupamento dos chamados por at� 3 crit�rios distintos. ".
				"Exemplo: Posso definir a categoria 1 quanto ao tipo de manuten��o: PREVENTIVA OU CORRETIVA.".
				" Posso definir a categoria 2 quanto ao objeto de atendimento: HARDWARE OU SOFTWARE. Etc...<br>".
				"Nessa tela voc� apenas ir� denominar cada uma das categorias a serem utilizadas. ".
				"Para criar os tipos dentro de cada categoria acesse: menu Admin->Ocorr�ncias->Problemas->Novo->Gerenciar</p></li>";
		print "</ul>";

	print "<p><b>Apar�ncia:</b></p>";
		print "<ul>";
		print "<li><p>COR DA SELE��O DE LINHAS: � a cor que destaca cada linha de registro quando o cursor do mouse est� sobre ela. ".
					"Voc� pode selecionar uma cor clicando no �cone de l�pis ou digitar diretamente o c�digo da cor.".
			"</li>";
		print "<li><p>COR DA MARCA��O DAS LINHAS: � a cor que destaca cada linha de registro quando � clicado em qualquer".
				" �rea da linha do registro. ".
				"Voc� pode selecionar uma cor clicando no �cone de l�pis ou digitar diretamente o c�digo da cor.</li>";
		print "</ul>";


print "</body></html>";

?>