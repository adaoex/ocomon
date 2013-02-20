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

	$queryInst = "SELECT * from instituicao order by inst_nome";
	$resultadoInst = mysql_query($queryInst);
	$linhasInst = mysql_num_rows($resultadoInst);

		print "<div id='Layer2' style='position:absolute; left:80%; top:176px; width:15%; height:40%; z-index:2; '>";//  <!-- Ver: overflow: auto    n�o funciona para o Mozilla-->
			print "<b>".TRANS('OCO_FIELD_UNIT').":</font></font></b>";
			print "<FORM name='form1' method='post' action='".$_SERVER['PHP_SELF']."'>";
			$sizeLin = $linhasInst+1;
			print "<select style='background-color: ".$cor3."; font-family:tahoma; font-size:11px;' name='instituicao[]' size='".$sizeLin."' multiple='yes'>";


			print "<option value='-1' selected>".TRANS('ALL')."</option>";
			while ($rowInst = mysql_fetch_array($resultadoInst))
			{
				print "<option value='".$rowInst['inst_cod']."'>".$rowInst['inst_nome']."</option>";
			}
			print "</select>";
			print "<br><input style='background-color: ".$cor1."' type='submit' class='button' value='".TRANS('BT_APPLY')."' name='OK'>";

			print "</form>";
		print "</div>";

		$saida="";
		if (isset ($_POST['instituicao'])) {
			for ($i=0; $i<count($_POST['instituicao']); $i++){
				$saida.= $_POST['instituicao'][$i].",";
			}
		}
		if (strlen($saida)>1) {
			$saida = substr($saida,0,-1);
		}

		$msgInst = "";
		if (($saida=="")||($saida=="-1")) {
			$clausula = "";
			$clausula2 = "";
			$msgInst = "".TRANS('ALL')."";
		} else {
			$sqlA ="select inst_nome as inst from instituicao where inst_cod in (".$saida.")";
			$resultadoA = mysql_query($sqlA);
			while ($rowA = mysql_fetch_array($resultadoA)) {
				$msgInst.= $rowA['inst'].', ';
			}
			$msgInst = substr($msgInst,0,-2);

			$clausula = " and comp_inst in (".$saida.")";
			$clausula2 = " and c.comp_inst in (".$saida.") ";
		}

		//TOTAL DE EQUIPAMENTOS COM OU SEM MEM�RIA
		$queryFull = "SELECT count(*) from equipamentos where comp_tipo_equip in (1,2,12,16) ".$clausula."";
		$resultadoFull = mysql_query($queryFull);
		$totalFull = mysql_result($resultadoFull,0);

		//TOTAL DE EQUIPAMENTOS COM MEM�RIA
		$queryB = "SELECT count(*) from equipamentos where comp_tipo_equip in (1,2,12,16) and ".
					"comp_memo is not null and comp_memo not in ('-1', 0) ".$clausula."";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);


		$percentualGeral = round($total*100/$totalFull,2);


		// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
		$query= "select count(*) as qtd, concat(count(*)/".$total."*100,'%') as porcento ,
				t.tipo_nome as equipamento, t.tipo_cod as tipo, m.mdit_cod as tipo_memo,
				 m.mdit_desc_capacidade as memoria from tipo_equip as t,
				modelos_itens as m, equipamentos as c where c.comp_memo = m.mdit_cod and
				m.mdit_tipo = 7 and c.comp_tipo_equip=t.tipo_cod ".$clausula2."  group by memoria,equipamento
				order by equipamento, qtd desc, memoria";
		//and (comp_tipo_equip=1 or comp_tipo_equip=2)
		$resultado = mysql_query($query);
		$linhas = mysql_num_rows($resultado);

		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";

		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td width='60%' align='center'><b>".TRANS('TTL_QTD_COMP_CLASS_FOR_MEMORY').".<p>".TRANS('OCO_FIELD_UNIT').": ".$msgInst."</p></b></td></tr>";


		print "<td class='line'>";
		print "<fieldset><legend>".TRANS('TTL_COMP_X_MEMORY')."</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";
		print "<TR><TD bgcolor='".$cor3."'><b>".TRANS('MNL_CAD_EQUIP')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('MNL_MEMO')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_QTD')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_PORCENTEGE')."</TD></tr>";
		$i=0;
		$j=2;

		while ($row = mysql_fetch_array($resultado)) {
			$color =  BODY_COLOR;
			$j++;
			print "<TR>";
				print "<TD bgcolor='".$color."'>".$row['equipamento']."</TD>";
				print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."&comp_memo=".$row['tipo_memo']."&ordena=local,etiqueta' title='".TRANS('HNT_LIST_EQUIP_CAD_QTD_MEMO')."'>".$row['memoria']." MB</a></TD>";
				print "<TD bgcolor='".$color."'>".$row['qtd']."</TD>";
				print "<TD bgcolor='".$color."'>".$row['porcento']."</TD>";
				print "</TR>";
				$i++;
		}
		print "<TR><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b>".TRANS('TOTAL').": <font color='red'>".$total."</font></TD><TD bgcolor='".$cor3."'><b>".$percentualGeral."%</b></TD></tr>";
		print "</TABLE>";
		print "</fieldset>";

		print "<TABLE width='60%' align='center'>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "</TABLE>";

		print "<TABLE width='60%' align='center'>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td width='80%' align='center'><b>".TRANS('SLOGAN_OCOMON')." <a href='http://www.unilasalle.edu.br' target='_blank'>".TRANS('COMPANY')."</a>.</b></td></tr>";
		print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>