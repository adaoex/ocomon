<?php 

# Inlcuir coment�rios e informa��es sobre o sistema
#
################################################################################
#                                  CHANGELOG                                   #
################################################################################
#  incluir um changelog
################################################################################
        include ("var_sessao.php");      // Tem que estar em primeiro por causa do header!
        include ("funcoes.inc");
        include ("config.inc.php");
        include ("logado.php");

        $hoje = date("Y-m-d H:i:s");

?>

<HTML>
<BODY bgcolor=<?php print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - controle de invent�rio  -  Usu�rio: <font color=red>$s_usuario</font></b></TD>";
                        if ($s_nivel==1)
                        {
                                
								echo menu_usuario_admin(TD_COLOR);
                        } 
						else
						        echo menu_usuario();
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>


        <BR>
        <B>Cadastro de resolu��o de Scanners:</B>
        <BR>
<?php 


        print "<TD align=right bgcolor=$cor1><a href=incluir_resolucao.php>Incluir Resolu��o</a></TD><BR>";
        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;


        $query = "SELECT * FROM resolucao ORDER BY resol_nome";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);   // $resultado-1 porque n�o quero contar a linha N/A

        if ($linhas == 0)
        {
                echo mensagem("N�o foi encontrado nenhuma resolucao de scanner cadastrada no sistema.");
                exit;
        }
        if ($linhas>1)
                print "<TR><TD bgcolor=$cor1><B>Foram encontrados $linhas resolu��es de scanner cadastradas no sistema. </B></TD></TR>";
        else
                print "<TR><TD bgcolor=$cor1><B>Foi encontrado somente 1 resolu��o de scanner cadastrado no sistema.</B></TD></TR>";
        print "</TD>";

        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
        print "<TR><TD bgcolor=$cor1><b>C�digo</TD><TD bgcolor=$cor1><b>fabricante</TD><TD bgcolor=$cor1><b>Alterar</TD><TD bgcolor=$cor1><b>Excluir</TD>";
        $i=0;
        $j=2;
        while ($i < $linhas)
        {
                if ($j % 2)
                {
                        $color =  BODY_COLOR;
                }
                else
                {
                        $color = white;
                }
                $j++;
                ?>
                <TR>
                <TD bgcolor=<?php print $color;?>><a href=mostra_consulta.php?=emBreve<?php print mysql_result($resultado,$i,0);?>><?php print mysql_result($resultado,$i,0);?></a></TD>
                <td bgcolor=<?php print $color;?>><?php  print mysql_result($resultado,$i,1);?></td>
                <TD bgcolor=<?php print $color;?>><a href=altera_dados_resolucao.php?resol_cod=<?php print mysql_result($resultado,$i,0);?>>Alterar</a></TD>
                <TD bgcolor=<?php print $color;?>><a href=exclui_dados_resolucao.php?resol_cod=<?php print mysql_result($resultado,$i,0);?>>Excluir</a></TD>


                <?php 
                  /*      $problemas = mysql_result($resultado,$i,1);
                        $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                        $resultado3 = mysql_query($query);   */
                print "</TR>";
                $i++;
        }
        print "</TABLE>";


        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='$cor3'>";
        print "<TR width=100%>";
        print "&nbsp;";
        print "</TR>";

        print "<td class='line'>";


?>
</BODY>
</HTML>
