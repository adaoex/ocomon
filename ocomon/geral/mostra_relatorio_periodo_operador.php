<?php 
 /*                        Copyright 2005 Fl�vio Ribeiro
  
         This file is part of OCOMON.
  
         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.
  
         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.
  
         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

?>

<HTML>
<BODY>

<?php 

        $query = "SELECT * FROM ocorrencias WHERE (";

        if (!empty($data_inicial) and !empty($data_final))
        {
                $data_inicial = datam($data_inicial);
                $data_final = datam($data_final);
                $query.="data_abertura>='$data_inicial' AND data_abertura<='$data_final'";
        }

        if (!empty($data_inicial) and empty($data_final))
        {
                if (strlen($query)>34)
                        $query.="AND ";
                $data_inicial = datam($data_inicial);
                $query.="data_abertura>='$data_inicial'";
        }

        if (empty($data_inicial) and !empty($data_final))
        {
                if (strlen($query)>34)
                        $query.="AND ";
                $data_final = datam($data_final);
                $query.="data_abertura<='$data_final'";
        }


        if (empty($data_inicial) and empty($data_final))
        {
                $data_inicial = datam("01/01/1990");
                $query.="data_abertura>='$data_inicial'";
        }


        $query_total = $query." ) ORDER BY operador";
        $resultado_total = mysql_query($query_total);
        $linhas_total = mysql_numrows($resultado_total);

        $query_operador = "SELECT * FROM usuarios ORDER BY login";
        $resultado_operador = mysql_query($query_operador);
        $linhas_operador = mysql_numrows($resultado_operador);

        if ($linhas_total == 0)
        {
                $aviso = "Nenhuma_ocorrencia_localizada.";
                $origem = "relatorio_periodo_operador.php";
                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
        }

        print "<BR><B>OcoMon - Relat�rio de ocorr�ncias por operador.</B> - <a href=relatorio_periodo_operador.php>Voltar</a><BR>";
        print "<HR>";
?>
<TABLE border="0"  align="center" width="100%">

        <TR>
        <TABLE border="0"  align="center" width="100%">
                <TD width="20%" align="left">Per�odo de:</TD>
                <TD width="20%" align="left"><?php print datab($data_inicial);?> a <?php print datab($data_final);?></TD>
                <TD width="40%" align="left">N�mero total de ocorr�ncias no per�odo:</TD>
                <TD width="20%" align="left"><?php print $linhas_total;?></TD>
        </TABLE>
        </TR>

        <?php 
        $i = 0;
        while ($i < $linhas_operador)
        {
                $oper = mysql_result($resultado_operador,$i,0);
                $query_oper = $query." AND operador='$oper') ORDER BY operador";
                $resultado_oper = mysql_query($query_oper);
                $linhas_oper = mysql_numrows($resultado_oper);
                ?>
                <TR>
                <TABLE border="0"  align="center" width="100%">
                        <TD width="20%" align="left"><?php print mysql_result($resultado_operador,$i,1);?>:</TD>
                        <TD width="30%" align="left"><?php print $linhas_oper;?></TD>
                        <TD width="20%" align="left">Percentual:</TD>
                        <TD width="30%" align="left"><?php print round(($linhas_oper*100)/$linhas_total);?>%</TD>
                </TABLE>
                </TR>
                <?php 
                $i++;
         }
         ?>



</TABLE>
<HR>

</BODY>
</HTML>


