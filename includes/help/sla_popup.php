<?php 
    print "<html><head><title>Indicadores de SLA</title>";

	print "<style type=\"text/css\"><!--";
	print "body.corpo {background-color:#F6F6F6;}";
	print "p{font-size:12px; text-align:center;}";
	print "table.pop {width:100%; margin-left:auto; margin-right: auto; text-align:left;
			border: 0px; border-spacing:1 ;background-color:#f6f6f6; padding-top:10px; }";
	print "tr.linha {font-family:helvetica; font-size:10px; line-height:1em; }";
	print "--></STYLE>";
print "</head><body class='corpo'>";



    if ($_GET['sla']=='r') {
        print "<p>SLA - Tempo de resposta: baseado no setor de origem do chamado.</p>";
        print "<table class='pop'>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td><td class='line'>Indica que o chamado ainda n�o teve resposta mas est� dentro do limite de tempo estipulado para o primeiro atendimento;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td><td class='line'>Indica que o chamado ainda n�o teve resposta e o tempo decorrido desde sua abertura est� at� 20% acima do estipulado para o primeiro atendimento;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td><td class='line'>Indica que o chamado ainda n�o teve resposta e j� ultrapassou 20% al�m do tempo m�ximo definido para resposta;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/checked.png'></td><td class='line'>Indica que o chamado j� teve um primeiro atendimento.</td></tr>";
        print "</table>";
    } else {
        print "<p>SLA - Tempo de soluc�o: baseado no tipo de problema do chamado.</p>";
        print "<table class='pop'>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td><td class='line'>Indica que o chamado ainda n�o foi conclu�do mas est� dentro do prazo estipulado para sua solu��o;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td><td class='line'>Indica que o chamado ainda n�o foi conclu�do e o tempo decorrido deste a sua abertura est� at� 20% acima do limite m�ximo estipulado para sua solu��o;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td><td class='line'>Indica que o chamado j� ultrapassou 20% al�m do tempo m�ximo estipulado para solu��o desse tipo de problema;</td></tr>";
        print "<tr class='linha'><td valign='top'><img height='14' width='14' src='../../includes/imgs/checked.png'></td><td class='line'>Indica que ainda n�o foi definido o tempo de solu��o limite para esse tipo de problema.</td></tr>";
        print "</table>";

    }
    print "</body></html>";

?>