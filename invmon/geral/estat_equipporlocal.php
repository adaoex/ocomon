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
  */session_start();
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title(TRANS('TTL_INVMON'));

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hoje = date("Y-m-d H:i:s");


	$cor  = TD_COLOR;
	$cor1 = TD_COLOR;
	$cor3 = BODY_COLOR;

	$queryB = "SELECT count(*) from equipamentos";
	$resultadoB = mysql_query($queryB);
	$total = mysql_result($resultadoB,0);

	// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
	$query = "select count(l.local)as qtd, count(*)/".$total."*100 as porcento,
		l.local as local, l.loc_id as tipo_local, t.tipo_nome as equipamento, t.tipo_cod as tipo
		from equipamentos as c,
		tipo_equip as t, localizacao as l where c.comp_tipo_equip = t.tipo_cod
		and c.comp_local = l.loc_id  group by local, equipamento order by qtd desc ,
		local asc";

	$resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);

	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='".$cor3."'>";

		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td width='80%' align='center'><b>".TRANS('TTL_TOTAL_EQUIP_CAD_FOR_LOCAL').":</b></td></tr>";


		print "<td class='line'>";
		print "<fieldset><legend>".TRANS('TTL_EQUIP_X_SECTOR')."</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='".$cor3."'>";
		print "<TR><TD bgcolor='".$cor3."'><b>".TRANS('COL_LOCAL')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('MNL_CAD_EQUIP')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_QTD')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_PORCENTEGE')."</TD></tr>";
		$i=0;
		$j=2;

		while ($row = mysql_fetch_array($resultado)) {
			$color = BODY_COLOR;
			$j++;
			print "<TR>";
			print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."&comp_local=".$row['tipo_local']."&ordena=modelo,etiqueta' title='".TRANS('HNT_SHOW_LIST_EQUIP_CAD_LOCAL')."'>".$row['local']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['equipamento']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['qtd']."</TD>";
			print "<TD bgcolor='".$color."'>".round($row['porcento'],2)."%</TD>";
			print "</TR>";
			$i++;
		}
		print "<TR><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b>".TRANS('TOTAL').": $total</TD><TD bgcolor='".$cor3."'></TD></tr>";
		print "</TABLE>";
		print "</fieldset>";

		print "<TABLE width='80%' align='center'>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";

		//print "<tr><td width=80% align=center><b><a href=relatorio_geral.php>Relat�rio Geral</a>.</b></td></tr>";
		print "</TABLE>";


		print "<TABLE width='80%' align='center'>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		
		print "<tr><td class='line' align='center'><a href='".$_SERVER['PHP_SELF']."' target='_blank')\">".TRANS('NEW_SCREEN')."</a></TD></tr>";

		print "<tr><td width='80%' align='center'><b>".TRANS('SLOGAN_OCOMON')." <a href='http://www.unilasalle.edu.br' target='_blank'>".TRANS('COMPANY')."</a>.</b></td></tr>";
		print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>