<?php 

# Inlcuir coment�rios e informa��es sobre o sistema
#
################################################################################
#                                  CHANGELOG                                   #
################################################################################
#  incluir um changelog
################################################################################
    	include ("../../includes/include_geral.inc.php");
		include ("../../includes/include_geral_II.inc.php");

        //$hoje = datab(time());

?>

<HTML>
<BODY bgcolor=<?php print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - M�dulo de Ocorr�ncias</b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Relat�rio de ocorr�ncias por operador</B>
<BR>

<FORM method="POST" action=mostra_relatorio_operador.php>
<TABLE border="1"  align="center" width="100%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Operador:</TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='operador' size=1>";
                print "<option value=-1 selected>-  Selecione um operador -</option>";
                $query = "SELECT * from usuarios order by nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,0);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
                <TD width="15%" align="left" bgcolor=<?php print TD_COLOR?>>Per�odo de:</TD>
                <TD width="15%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print "<INPUT type=text name=data_inicial value=\"$hoje\" size=15 maxlength=15>";?></TD>
                <TD width="5%" align="left" bgcolor=<?php print TD_COLOR?>>a:</TD>
                <TD width="15%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print "<INPUT type=text name=data_final value=\"$hoje\" size=15 maxlength=15>";?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok" onclick="ok=sim">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
        </TABLE>
        </TR>

</TABLE>
</FORM>


</BODY>
</HTML>

