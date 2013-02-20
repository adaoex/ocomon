<?php 
    print "<html><head><title>Configura��o de abertura de chamados</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px; text-align:left; }";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:justify; 
					border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
   

        print "<p><b>Configura��o de mensagens para envio de e-mail pelo Ocomon:</b></p>";
		print "<p>Voc� pode customizar as mensagens de e-mail enviadas pelo Ocomon em qualquer um dos eventos adequados do sistema:</p>";
		print "<p>Os eventos poss�veis s�o:</p>";
		print "<ul>";
		print "<li><p><b>abertura-para-usuario:</b> E-mail enviado para o usu�rio-final no momento em que um chamado � aberto no sistema;</p></li>";
		print "<li><p><b>abertura-para-area:</b> E-mail enviado para a �rea de atendimento no momento em que um chamado � aberto no sistema;</p></li>";
		print "<li><p><b>encerra-para-area:</b> E-mail enviado para a �rea de atendimento no momento em que o um chamado � encerrado no sistema;</p></li>";
		print "<li><p><b>encerra-para-usuario:</b> E-mail enviado para o usu�rio-final no momento em que o um chamado � encerrado no sistema;</p></li>";
		print "<li><p><b>edita-para-area:</b> E-mail enviado para a �rea de atendimento no momento em que o um chamado � editado no sistema;</p></li>";
		print "<li><p><b>edita-para-usuario:</b> E-mail enviado para o usu�rio-final no momento em que o um chamado � editado no sistema;</p></li>";
		print "<li><p><b>edita-para-operador:</b> E-mail enviado para o operador t�cnico no momento em que o um chamado � editado no sistema;</p></li>";
		print "<li><p><b>cadastro-usuario:</b> E-mail enviado para o usu�rio-final para confirma��o de cadastro para abertura de chamados no sistema.</p></li>";
		print "<li><p><b>cadastro-usuario-from-admin:</b> E-mail enviado para o usu�rio-final para confirma��o de cadastro quando o cadastro for confirmado diretamente atrav�s da interface administrativa do sistema.</p></li>";
		print "</ul>";
		print "<br>";
		print "<p>As op��es de configura��o s�o:</p>";
		print "<ul>";
		print "<li><p><b>FROM:</b> Ser� o \"name\" do endere�o de e-mail que aparecer� como remetente da mensagem;</p></li>";
		print "<li><p><b>Responder para:</b> endere�o de resposta do e-mail;</p></li>";
		print "<li><p><b>Assunto:</b> ser� o campo \"assunto\" da mensagem enviada;</p></li>";
		print "<li><p><b>Mensagem HTML:</b> texto que ser� enviado nas mensagens de e-mail se a op��o de conte�do HTML estiver habilitada; </p></li>";
		print "<li><p><b>Mensagem alternativa:</b> texto que ser� enviado nas mensagens de e-mail se a op��o de conte�do HTML estiver desabilitada.</p></li>";
		print "</ul>";
		print "<br>";

		print "<p>Voc� pode utilizar vari�veis de ambiente para customizar as mensagens de e-mail:</p>";
		print "<p>As vari�veis poss�veis s�o:</p>";
		print "<ul>";
		print "<li><p><b>%area%</b>: �rea t�cnica para atendimento do chamado;</p></li>";
		print "<li><p><b>%assentamento%</b>: assentamento definido durante uma edi��o do chamado;</p></li>";
		print "<li><p><b>%contato%</b>: campo contato;</p></li>";
		print "<li><p><b>%descricao%</b>: campo descri��o do chamado;</p></li>";
		print "<li><p><b>%editor%</b>: usu�rio logado que est� editando um chamado;</p></li>";
		print "<li><p><b>%linkconfirma%</b>: link para confirma��o de cadastro de usu�rio somente abertura;</p></li>";
		print "<li><p><b>%login%</b>: s� tem valor se for utilizado na mensagem de confirma��o de cadastro;</p></li>";
		print "<li><p><b>%numero%</b>: n�mero do chamado;</p></li>";
		print "<li><p><b>%operador%</b>: operador t�cnico do chamado;</p></li>";
		print "<li><p><b>%problema%</b>: problema classificado para o chamado;</p></li>";
		print "<li><p><b>%ramal%</b>: telefone de contato do usu�rio que solicitou a abertura do chamado;</p></li>";
		print "<li><p><b>%setor%</b>: local/departamento do usu�rio que solicitou a abertura do chamado;</p></li>";
		print "<li><p><b>%site%</b>: endere�o do sistema Ocomon na sua empresa (definido no arquivo config.inc.php);</p></li>";
		print "<li><p><b>%solucao%</b>: solu��o adotada para o chamado;</p></li>";
		print "<li><p><b>%usuario%</b>: dependendo do evento ser� o pr�prio usu�rio-final que abriu o chamado;</p></li>";
		print "<li><p><b>%versao%</b>: vers�o do Ocomon.</p></li>";
		print "</ul>";
		
		
    print "</body></html>";

?>