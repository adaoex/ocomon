<?php 
    print "<html><head><title>Configura��o de abertura de chamados</title>"; 
    
			print "<style type=\"text/css\"><!--";
			print "body.corpo {background-color:#F6F6F6; font-family:helvetica;}";
            print "p{font-size:12px; text-align:justify; }";
            print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left; 
					border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
			print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";			
			print "--></STYLE>";			    
    print "</head><body class='corpo'>";
    
   

        print "<p><b>Configura��o de abertura de chamados pelo usu�rio final:</b></p>";
		print "<p>Para a abertura de chamados funcionar adequadamente � necess�rio observar os seguintes pontos:</p>";
		print "<ul>";
		print "<li><p>Cadastre uma nova �rea de atendimento, e desmarque a op��o \"Presta atendimento\". ".
				"Essa �rea ser� criada especificamente p�ra abertura de chamados. O e-mail dessa �rea n�o ".
				"precisa ser um e-mail v�lido pois n�o ser� utilizado pelo sistema.</p></li>";
		print "<li><p>Configure a �rea criada como \"�rea de n�vel somente abertura\".</p></li>";
		print "<li><p>Para cadastrar usu�rios como somente abertura de chamados, utilize o auto-cadastro ".
				"na tela de login do sistema. Se for cadastrar manualmente cada usu�rio de abertura observe que o n�vel deve ser ".
				"definido como \"Somente abertura\" e a �rea deve ser a �rea criada para abertura de chamados sem defini��es de �reas secund�rias.</p></li>";
		
		print "</ul>";

    print "</body></html>";

?>