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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

        if ($_SESSION['s_nivel']!=1)
        {
                print "<script>window.open('../../index.php','_parent','')</script>";
		exit;
	}

	print "<html><head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";


	//$hoje = date("Y-m-d H:i:s");
        $hoje2 = date("d/m/Y");

        if (isset($_POST['numero'])) {
        	$numero = $_POST['numero'];
        } else
        if (isset($_GET['numero'])) {
        	$numero = $_GET['numero'];
        }


	$query = "select o.*, u.* from ocorrencias o, usuarios u where o.operador=u.user_id and numero=".$numero."";
        $resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);
        $linhas = mysql_numrows($resultado);

	$data_atend = $row['data_atendimento']; //Data de atendimento!!!

	$query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and a.ocorrencia='".$numero."'";
	$resultado2 = mysql_query($query2);
	$linhas2 = mysql_numrows($resultado2);

	print "<HTML><BODY bgcolor='".BODY_COLOR."'>";
	$auth = new auth;
	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


	if (!isset($_POST['submit'])) {
		print "<BR><B>".TRANS('FIELD_EDIT_OCCO').":</B><BR>";

		print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";

			print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_NUMBER').":</TD>";
				print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='disable' value='".$row['numero']."' disabled ></TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_STATUS').":</TD>";
				print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";

					if ($row['status'] == 4){$stat_flag="";} else $stat_flag =" where stat_id<>4 ";

					print "<SELECT class='select' name='status' id='idStatus' size=1>";
						print "<option value= '-1'>".TRANS('SEL_STATUS')."</option>";
						$query_stat = "SELECT * from status ".$stat_flag." order by status";
						$exec_stat = mysql_query($query_stat);
						while ($row_stat = mysql_fetch_array($exec_stat))
						{
							print "<option value=".$row_stat['stat_id']."";
								if ($row_stat['stat_id'] == $row['status']) {
									print " selected";
								}
							print " >".$row_stat['status']." </option>";
						}
						print "</select>";
				print "</TD>";
			print "</TR>";
			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_PROB').":</TD>";
				print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
					print "<SELECT class='select' name='problema' id='idProblema' size=1>";
					print "<option value= '-1'>".TRANS('OCO_SEL_PROB')."</option>";
					$query = "SELECT * from problemas order by problema";
					$exec_prob = mysql_query($query);
					while ($row_prob = mysql_fetch_array($exec_prob))
					{
						print "<option value=".$row_prob['prob_id']."";
							if ($row_prob['prob_id'] == $row['problema']) {
								print " selected";
							}
						print " >".$row_prob['problema']." </option>";
					}
					print "</select>";
				print "</TD>";

				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_AREA').":</TD>";
				print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
					print "<SELECT class='select' name='sistema' id='idArea' size=1>";
					print "<option value= '-1'>".TRANS('OCO_SEL_AREA')."</option>";
					$query = "SELECT * from sistemas order by sistema";
					$exec_sis = mysql_query($query);
					while ($row_sis = mysql_fetch_array($exec_sis))
					{
						print "<option value=".$row_sis['sis_id']."";
							if ($row_sis['sis_id'] == $row['sistema']) {
								print " selected";
							}
						print " >".$row_sis['sistema']." </option>";
					}
					print "</select>";
				print "</TD>";

			print "</TR>";
			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('OCO_DESC').":</TD>";
				print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";

				if (!$_SESSION['s_formatBarOco']) {
					print "<TEXTAREA class='textarea' name='descricao' id='idDescricao'>".nl2br($row['descricao'])."</textarea>";
				} else
					print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
				?>
				<script type="text/javascript">
					var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
					if (bar ==1) {
						var oFCKeditor = new FCKeditor( 'descricao' ) ;
						oFCKeditor.BasePath = '../../includes/fckeditor/';
						oFCKeditor.Value =  '<?php print $row['descricao'];?>';
						oFCKeditor.ToolbarSet = 'ocomon';
						oFCKeditor.Width = '570px';
						oFCKeditor.Height = '100px';
						oFCKeditor.Create() ;
					}
				</script>
				<?php 

				print "</TD>";
			print "</TR>";
			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_UNIT').":</TD>";
				print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

				$instituicao = $row['instituicao'];
				if ($instituicao != null)
				{
					$query = "SELECT * FROM instituicao WHERE inst_cod=".$instituicao."";
				}
				else
				{
					$query = "SELECT * FROM instituicao WHERE inst_cod is null";
				}
				$resultado3 = mysql_query($query);
				$nomeinst = "";
				if (mysql_numrows($resultado3) > 0)
				{
					$nomeinst=mysql_result($resultado3,0,1);
				}
				print "<select  class='select' name='institui' size='1'>";
				$query_todas="select * from instituicao order by inst_cod";
				$result_todas=mysql_query($query_todas);

				if ($nomeinst=="")
				{
					print "<option value='' selected> </option>";
				}

				while($row_todas=mysql_fetch_array($result_todas))
				{
					if ($row_todas['inst_cod']==$instituicao)
					{
						$s='selected ';
					}
					else
					{
						$s='';
					}
						print "<option value=".$row_todas['inst_cod']." $s>".$row_todas['inst_nome']."</option>";
				} // while
				print "</select>";
				print "</TD>";


				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_TAG_EQUIP').":</TD>";
				print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>".
						"<INPUT type='text' class='text' name='etiq' id='idEtiqueta' value ='".$row['equipamento']."' size='15'></TD>";
			print "</TR>";
			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_CONTACT').":</TD>";
				print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>"."
						<input type='text' class='text' name='contato' id='idContato' value='".$row['contato']."'></TD>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_PHONE').":</TD>";
				print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>".
						"<input type='text' class='text' name='ramal' id='idRamal' value='".$row['telefone']."'></TD>";
			print "</TR>";
			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_LOCAL').":</TD>";
					print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
						print "<SELECT  class='select' name='local' id='idLocal' size=1>";
						print "<option value= '-1'>".TRANS('OCO_SEL_LOCAL')."</option>";
						$query = "SELECT * from localizacao order by local";
						$exec_loc = mysql_query($query);
						while ($row_loc = mysql_fetch_array($exec_loc))
						{
							print "<option value=".$row_loc['loc_id']."";
								if ($row_loc['loc_id'] == $row['local']) {
									print " selected";
								}
							print " >".$row_loc['local']." </option>";
						}
						print "</select>";
				print "</TD>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_OPERATOR').":</TD>";
				print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
					print "<SELECT class='select' name='operador' size='1'>";
					print "<option value=".$row['user_id']." selected>".$row['nome']."</option>";
					$query = "SELECT * from usuarios order by nome";
					$exec_oper = mysql_query($query);
					while ($row_oper = mysql_fetch_array($exec_oper))
					{
						print "<option value=".$row_oper['user_id'].">".$row_oper['nome']." ";
						print "</option>";
					}
					print "</SELECT>";
				print "</TD>";
			print "</TR>";

			$antes = $row['status'];

			if ($_SESSION['s_allow_date_edit'] == 0){
				$allowDateEdit = "readonly";
			} else {
				$allowDateEdit = "";
			}

			if ($row['status'] == 4) //Encerrado
			{
				$antes = 4;
				print "<TR>";
					print "<TD align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
					print "<TD align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text' name='data_abertura' id='idDataAbertura' ".$allowDateEdit." value='".formatDate($row['data_abertura'])."'></TD>";
					print "<TD align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_DATE_CLOSING').":</TD>";
					print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text' name='data_fechamento'  ".$allowDateEdit." value='".formatDate($row['data_fechamento'])."'></TD>";
				print "</TR>";
			}
				else //chamado n�o encerrado
			{
				print "<TR>";
					print "<TD align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
					print "<TD colspan='5' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text' name='data_abertura'  ".$allowDateEdit." value='".formatDate($row['data_abertura'])."'></TD>";
				print "</TR>";
			}

			//fecha o if

			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('FIELD_NESTING').":</TD>";
				print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";

				if (!$_SESSION['s_formatBarOco']) {
					print "<TEXTAREA class='textarea' name='assentamento' id='idAssentamento'>".TRANS('TXTAREA_OCCO_DIRECT_MODIFY')." ".$_SESSION['s_usuario']."</textarea>";
				}
				?>
				<script type="text/javascript">
					var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
					if (bar ==1) {
						var oFCKeditor = new FCKeditor( 'assentamento' ) ;
						oFCKeditor.BasePath = '../../includes/fckeditor/';
						oFCKeditor.Value = '<?php print "".TRANS('TXTAREA_OCCO_DIRECT_MODIFY')." ".$_SESSION['s_usuario']."";?>';
						oFCKeditor.ToolbarSet = 'ocomon';
						oFCKeditor.Width = '570px';
						oFCKeditor.Height = '100px';
						oFCKeditor.Create() ;
					}
				</script>
				<?php 
				if ($data_atend =="") {
					print "<input type='checkbox' value='ok' name='resposta' checked title='".TRANS('HNT_NOT_MARK_OPT_FIRST_REPLY_CALL')."'>".TRANS('FIELD_FIRST_REPLY')."";
				}
				print "</TD>";
			print "</TR>";

			$qryTela = "select * from imagens where img_oco = ".$row['numero']."";
			$execTela = mysql_query($qryTela) or die (TRANS('MSG_ERR_NOT_INFO_IMAGE'));
			//$rowTela = mysql_fetch_array($execTela);
			$isTela = mysql_num_rows($execTela);
			$cont = 0;

			while ($rowTela = mysql_fetch_array($execTela)) {
			//if ($isTela !=0) {
				$cont++;
				print "<tr>";
				$size = round($rowTela['img_size']/1024,1);
				print "<TD  bgcolor='".TD_COLOR."' >Anexo ".$cont."&nbsp;[".$rowTela['img_tipo']."]<br>(".$size."k):</td>";

				if(eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $rowTela["img_tipo"])) {
					$viewImage = "&nbsp;<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?".
						"file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\" ".
						"title='View the file'><img src='../../includes/icons/kghostview.png' width='16px' height='16px' border='0'></a>";
				} else {
					$viewImage = "";
				}
				print "<td colspan='5' ><a onClick=\"redirect('../../includes/functions/download.php?".
						"file=".$row['numero']."&cod=".$rowTela['img_cod']."')\" title='Download the file'>".
						"<img src='../../includes/icons/attach2.png' width='16px' height='16px' border='0'>".
						"".$rowTela['img_nome']."</a>".$viewImage."".
						"<input type='checkbox' name='delImg[".$cont."]' value='".$rowTela['img_cod']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'>".
						"</TD>";
				print "</tr>";
			}



			if ($linhas2 !=0) { //ASSENTAMENTOS DO CHAMADO
				print "<tr><td colspan='6'><IMG ID='imgAssentamento' SRC='../../includes/icons/open.png' width='9' height='9' ".
						"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento')\">&nbsp;<b>".TRANS('THERE_IS_ARE')." <font color='red'>".$linhas2."</font>".
						" ".TRANS('FIELD_NESTING_FOR_OCCO')."</b></td></tr>";

				//style='{padding-left:5px;}'
				print "<tr><td colspan='6' ><div id='Assentamento' style='{display:none}'>"; //style='{display:none}'
				print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
				$i = 0;
				while ($rowAssentamento = mysql_fetch_array($resultado2)){
					$printCont = $i+1;
					print "<TR>";
					print "<TD width='20%' bgcolor='".TD_COLOR."' valign='top'>".
							"".TRANS('FIELD_NESTING')." ".$printCont." de ".$linhas2." por ".$rowAssentamento['nome']." em ".
							"".formatDate($rowAssentamento['data'])."".
						"</TD>";
					print "<TD colspan='5' align='left' valign='top'>".nl2br($rowAssentamento['assentamento'])."</TD>";
					print "</TR>";
					$i++;
				}
				print "</table></div></td></tr>";
			}


				//VERIFICA SE EXISTE UM CHAMADO ORIGEM
				$sqlPaiCall = "select * from ocodeps where dep_filho = ".$row['numero']." ";// or dep_filho=".$row['numero']."";
				$execPaiCall = mysql_query($sqlPaiCall) or die (TRANS('MSG_ERR_RESCUE_INFO_SUBCALL').'<br>'.$sqlPaiCall);
				$regPai = mysql_num_rows($execPaiCall);
				$rowPai = mysql_fetch_array($execPaiCall);
				if ($regPai > 0) {
					$headerLine = "<tr><td colspan='5'>".TRANS('TXT_BONDS_OTHER_CALL').":</td></tr>";
					$imgPai = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>";
				} else {
					$imgPai = "";
					$headerLine = "";
				}


				//VERIFICA SE EXISTEM SUB-CHAMADOS
				$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." ";// or dep_filho=".$row['numero']."";
				$execSubCall = mysql_query($sqlSubCall) or die (TRANS('MSG_ERR_RESCUE_INFO_SUBCALL').'<br>'.$sqlSubCall);
				$regSub = mysql_num_rows($execSubCall);
				if ($regSub > 0) {
					if ($headerLine=="" ) $headerLine = "<tr><td colspan='5'>".TRANS('TXT_BONDS_OTHER_CALL').":</td></tr>";
					$imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>";
				} else {
					$imgSub = "";
					//$headerLine = "";
				}


				print $headerLine;

				if ($regPai>0){
					print "<tr>";
					print "<td colspan='5' bgcolor='".BODY_COLOR."'><img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>".
						"<a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowPai['dep_pai']."')\">".$rowPai['dep_pai']."</a>";
					print "<input type='checkbox' name='delPai'  value='".$rowPai['dep_pai']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('TXT_DEL_BONDS')."'></TD>";
					print "</tr>";
				}

				$contSub = 0;
				while ($rowSub = mysql_fetch_array($execSubCall)) {
					$contSub++;
					print "<tr>";
					print "<td colspan='5' bgcolor='".BODY_COLOR."'><img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>".
						"<a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowSub['dep_filho']."')\">".$rowSub['dep_filho']."</a>";
					print "<input type='checkbox' name='delSub[".$contSub."]' value='".$rowSub['dep_filho']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('TXT_DEL_BONDS')."'></TD>";
					print "</tr>";

				}



			print "<TR>";
				print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>";
					print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";
					print "<input type='hidden' name='numero' value='".$_GET['numero']."'>";
					print "<input type='hidden' name='cont' value='".$cont."'>";
					print "<input type='hidden' name='contSub' value='".$contSub."'>";
					print "<input type='submit' class='button' value=' ".TRANS('BT_OK')." ' name='submit' id='idSubmit'>";
				print "</TD>";

				print "<TD colspan='3' align='center' width='25%' bgcolor='".BODY_COLOR."'>".
						"<INPUT type='button' class='button' value='".TRANS('BT_CANCEL')."' name='desloca' ONCLICK='javascript:history.back()'>".
					"</TD>";

				print "</TR>";

		} else
         	if (isset($_POST['submit'])) {
			$depois = $_POST['status'];
			$erro=false;

			if (!$erro) {
				//$data = datam($hoje2);
				$responsavel = $_SESSION['s_uid'];
				$queryA = "";
				$queryA = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values (".$_POST['numero'].", ";
				if ($_SESSION['s_formatBarOco']) {
					$queryA.= " '".$_POST['assentamento']."',";
				} else {
					$queryA.= " '".noHtml($_POST['assentamento'])."',";
				}
				$queryA.=" '".date("Y-m-d H:i:s")."', '".$responsavel."')";
				$resultado3 = mysql_query($queryA) or die ('ERRO: <br>'.$queryA);

				$sqlDoc1 = "select * from doc_time where doc_oco = ".$_POST['numero']." and doc_user=".$_SESSION['s_uid']."";
				$execDoc1 = mysql_query($sqlDoc1);
				$regDoc1 = mysql_num_rows($execDoc1);
				$rowDoc1 = mysql_fetch_array($execDoc1);
				if ($regDoc1 >0) {
					$sqlDoc  = "update doc_time set doc_edit=doc_edit+".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." where doc_id = ".$rowDoc1['doc_id']."";
					$execDoc =mysql_query($sqlDoc) or die (TRANS('MSG_ERR_UPDATE_TIME_DOC_CALL').'<br>').$sqlDoc;
				} else {
					$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$_POST['numero'].", 0, ".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." , 0, ".$_SESSION['s_uid'].")";
					$execDoc = mysql_query($sqlDoc) or die (TRANS('MSG_ERR_UPDATE_TIME_DOC_CALL').'<br>').$sqlDoc;
				}

				for ($j=1; $j<=$_POST['cont']; $j++) {
					if (isset($_POST['delImg'][$j])){
						$qryDel = "DELETE FROM imagens WHERE img_cod = ".$_POST['delImg'][$j]."";
						$execDel = mysql_query($qryDel) or die (TRANS('MSG_NOT_DEL_IMAGE'));
					}
				}

				if (isset($_POST['delPai'])){
					$qryDel = "DELETE FROM ocodeps WHERE dep_filho= ".$_POST['numero']." and dep_pai = ".$_POST['delPai']."";
					$execDel = mysql_query($qryDel) or die (TRANS('MSG_NOT_DEL_BOND').$qryDel);
				}

				for ($j=1; $j<=$_POST['contSub']; $j++) {
					if (isset($_POST['delSub'][$j])){
						$qryDel = "DELETE FROM ocodeps WHERE dep_pai= ".$_POST['numero']." and dep_filho = ".$_POST['delSub'][$j]."";
						$execDel = mysql_query($qryDel) or die (TRANS('MSG_NOT_DEL_BOND').$qryDel);
					}
				}

				if ($row['status'] != $_POST['status']) {
					$query = "";
					if (($data_atend==null) and ($_POST['status']!=4) and (isset($_POST['resposta']))) { //para verificar se j� foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
						$query = "UPDATE ocorrencias SET operador='".$_POST['operador']."', problema = '".$_POST['problema']."', ".
							"instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', ".
							"local='".$_POST['local']."', data_fechamento=NULL, status='".$_POST['status']."', data_atendimento='".date("Y-m-d H:i:s")."', descricao=";

						if ($_SESSION['s_formatBarOco']) {
							$query.= " '".$_POST['descricao']."',";
						} else {
							$query.= " '".noHtml($_POST['descricao'])."',";
						}

						$query.="contato='".noHtml($_POST['contato'])."', telefone='".$_POST['ramal']."' ".
							"WHERE numero=".$_POST['numero']."";
						$resultado4 = mysql_query($query)or die (TRANS('FIELD_ERROR').': 	<br>'.$query);
					} else {
						$query = "UPDATE ocorrencias SET operador='".$_POST['operador']."', problema = '".$_POST['problema']."' , ".
								"instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local='".$_POST['local']."', ".
								"data_fechamento=NULL, status='".$_POST['status']."', descricao=";

						if ($_SESSION['s_formatBarOco']) {
							$query.= " '".$_POST['descricao']."',";
						} else {
							$query.= " '".noHtml($_POST['descricao'])."',";
						}

						$query.= "contato='".noHtml($_POST['contato'])."', telefone='".$_POST['ramal']."' WHERE numero=".$_POST['numero']."";
						$resultado4 = mysql_query($query)or die ('ERRO: <br>'.$query);

					}
				} else {
					$query = "UPDATE ocorrencias SET operador='".$_POST['operador']."', problema ='".$_POST['problema']."', ".
						"instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', ".
						"local='".$_POST['local']."', status='".$_POST['status']."', descricao=";
					if ($_SESSION['s_formatBarOco']) {
						$query.= " '".$_POST['descricao']."',";
					} else {
						$query.= " '".noHtml($_POST['descricao'])."',";
					}
					$query.=	"contato='".noHtml($_POST['contato'])."', telefone='".$_POST['ramal']."' WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query) or die ('ERRO: <br>'.$query);
				}

				if (($resultado3==0) OR ($resultado4 == 0)) {
					$aviso = TRANS('MSG_ERR_ACCESS');
				} else {
					##ROTINAS PARA GRAVAR O TEMPO DO CHAMADO EM CADA STATUS
					if (isset($_POST['status']) != $row['status']) { //O status foi alterado
						##TRATANDO O STATUS ANTERIOR
						//Verifica se o status 'atual' j� foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, sen�o devo gravar ele pela primeira vez.
						$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$row['status']." ";
						$exec_sql = mysql_query($sql_ts_anterior);

						$error = "";
						if ($exec_sql == 0) $error= " erro 1";

						$achou = mysql_num_rows($exec_sql);
						if ($achou >0){ //esse status j� esteve setado em outro momento
							$row_ts = mysql_fetch_array($exec_sql);

							// if (array_key_exists($row['sistema'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
								// $areaT = $row['sistema']; //Recebe o valor da �rea de atendimento do chamado
							// } else $areaT = 1; //Carga hor�ria default definida no arquivo config.inc.php
							$areaT = "";
							$areaT=testaArea($areaT,$row['sistema'],$H_horarios);

							$dt = new dateOpers;
							$dt->setData1($row_ts['ts_data']);
							$dt->setData2(date("Y-m-d H:i:s"));
							$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
							$segundos = $dt->diff["sValido"]; //segundos v�lidos

							$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+".$segundos.") , ts_data ='".date("Y-m-d H:i:s")."' where ts_ocorrencia = ".$row['numero']." and ".
									"ts_status = ".$row['status']." ";
							$exec_upd = mysql_query($sql_upd);
							if ($exec_upd ==0) $error.= " erro 2";

						} else {
							$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$row['status'].", 0, '".date("Y-m-d H:i:s")."' )";
							$exec_ins = mysql_query ($sql_ins);
							if ($exec_ins == 0) $error.= " erro 3 ";
						}
						##TRATANDO O NOVO STATUS
						//verifica se o status 'novo' j� est� gravado na tabela 'tempo_status', se estiver eu devo atualizar a data de in�cio. Sen�o estiver gravado ent�o devo gravar pela primeira vez
						$sql_ts_novo = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$_POST['status']." ";
						$exec_sql = mysql_query($sql_ts_novo);
						if ($exec_sql == 0) $error.= " erro 4";

						$achou_novo = mysql_num_rows($exec_sql);
						if ($achou_novo > 0) { //status j� existe na tabela tempo_status
							$sql_upd = "update tempo_status set ts_data = '".date("Y-m-d H:i:s")."' where ts_ocorrencia = ".$row['numero']." and ts_status = ".$_POST['status']." ";
							$exec_upd = mysql_query($sql_upd);
							if ($exec_upd == 0) $error.= " erro 5";
						} else {//status novo na tabela tempo_status
							$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$_POST['status'].", 0, '".date("Y-m-d H:i:s")."' )";
							$exec_ins = mysql_query($sql_ins);
							if ($exec_ins == 0) $error.= " erro 6 ";
						}
					}
					$aviso = TRANS('MSG_OCCO_MODIFY_SUCESS');
				}
			}//fecha if erro=nao
			print "<script>mensagem('".$aviso."'); redirect('mostra_consulta.php?numero=".$_POST['numero']."');</script>";
		}//fecha if $_POST['submit']

print "</TABLE>";
print "</FORM>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idStatus','COMBO','Status',1);
		if (ok) var ok = validaForm('idProblema','COMBO','Problema',1);
		if (ok) var ok = validaForm('idArea','COMBO','�rea',1);
		if (ok) var ok = validaForm('idDescricao','','Descri��o',1);
		if (ok) var ok = validaForm('idEtiqueta','INTEIRO','Etiqueta',0);
		if (ok) var ok = validaForm('idContato','','Contato',1);
		if (ok) var ok = validaForm('idRamal','FONE','Ramal',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		if (ok) var ok = validaForm('idAssentamento','','Assentamento',1);

		return ok;
	}


	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = '../../includes/icons/';

		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}
-->
</script>
<?php 
print "</body>";
print "</html>";
?>
