<?php 
    print "<html><head><title>Configura��o global para envio de e-mails</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px; text-align:justify; }";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
   

        print "<p><b>Configura��o global para envio de e-mails pelo sistema</b></p>";
		print "<p>Nessa tela voc� poder� configurar as op��es globais para envio de e-mail pelo sistema Ocomon, as op��es s�o:</p>";
		print "<ul>";
		print "<li><p>Utiliza SMTP: essa op��o vem marcada por default, isso significa que os e-mails enviados pelo ".
					"sistema utilizar�o o endere�o SMTP especificado por voc�. Caso voc� desabilite essa op��o os e-mails ser�o ".
					"enviados utilizando a fun��o \"mail\" do PHP e o arquivo php.ini deve estar configurado corretamente para ".
					"funcionar de maneira adequada.</p></li>";
		print "<li><p>Endere�o SMTP: aqui voc� deve especificar o endere�o SMTP que dever� ser utilizado para o envio das ".
					"mensagens do sistema se a op��o \"Utiliza SMTP\" estiver habilitada.</p></li>";
		print "<li><p>Precisa de autentica��o: se o seu servidor de e-mail requerer autentica��o para envio de mensagens ".
					"voc� deve habilitar essa op��o aqui.</p></li>";
		print "<li><p>Usu�rio: usu�rio v�lido para autentica��o de envio de e-mail pelo SMTP definido por voc�. Tamb�m ".
					"� necess�rio digitar a senha para a autentica��o.</p></li>";
		print "<li><p>Endere�o de envio(FROM): endere�o que aparecer� como remetente das mensagens enviadas pelo sistema.</p></li>";
		print "<li><p>Conte�do HTML: se essa op��o estiver habilitada, o sistema aceitar� o envio de mensagens no formato HTML, ".
					"do contr�rio apenas mensagens texto ser�o enviadas.</p></li>";
		
		print "</ul>";

    print "</body></html>";

?>