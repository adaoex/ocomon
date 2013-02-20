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

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

if ($ok != 'Pesquisar')
{
	print "<html>";
	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
    print "	<BR><BR>";
	print "	<B><center>::: Relat�rio de SLA's :::</center></B><BR><BR>";
	print "		<FORM name='form1' action='".$_SERVER['PHP_SELF']."' method='post'>";
	print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">�rea Respons�vel:</td>";
	print "					<td class='line'><Select name='area' class='select'>";
	print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
									$query="select * from sistemas where sis_status not in (0) order by sistema";
									$resultado=mysql_query($query);
									while($row=mysql_fetch_array($resultado))
									{
										print "<option value=".$row['sis_id']."";
										if ($row['sis_id']==$s_area) print " selected";
										print ">".$row['sistema']."</option>";
									} // while
	print "		 				</Select>";
	print "					 </td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
	print "					<td class='line'><INPUT name='d_ini' class='data'><a href=\"javascript:cal1.popup();\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
	print "					<td class='line'><INPUT name='d_fim' class='data'><a href=\"javascript:cal2.popup();\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";

	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Tipo de relat�rio:</td>";
	print "					<td class='line'><select name='saida' class='data'>";
	print "							<option value=-1 selected>Normal</option>";
//	print "							<option value=1>Relat�rio 1 linha</option>";
	print "						</select>";
	print "					</td>";
	print "				</tr>";
	print "		</TABLE><br>";
	print "		<TABLE align='center'>";
	print "			<tr>";
	print "	            <td class='line'>";
	print "					<input type='submit' value='Pesquisar' name='ok' >";//onclick='ok=sim'
	print "	            </TD>";
	print "	            <td class='line'>";
	print "					<INPUT type='reset' value='Limpar campos' name='cancelar'>";
	print "				</TD>";
	print "			</tr>";
	print "	    </TABLE>";
	print " </form>";
	print "</BODY>";
    print "</html>";
}//if $ok!=Pesquisar

else //if $ok==Pesquisar
{
	//SLA 1 � menor do que o SLA 2 - VERDE
	$sla16 = 16; //16 horas - solicitado para chamados da �rea de sistemas
	$sla3 = 6; //INICIO DO VERMELHO - Tempo de SOLU��O
	$sla2 = 4; //IN�CIO DO AMARELO
	$slaR3 = 3600; //Tempo de RESPOSTA em segundos VERMELHO
	$slaR2 = 1800; //AMARELO

	$corSla1 = "green";
	$corSla2 = "orange";
	$corSla3 = "red";
	$corSla16 = "white";

	$chamadosGreen = array();
	$chamadosOrange = array();
	$chamadosRed = array();
	$chamados16 = array();


	$percLimit = 20; //Limite em porcento que um chamado pode estourar para ficar no SLA2 antes de ficar no vermelho

	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';

    $query = "select o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, o.sistema as cod_area, s.sistema,
            p.problema as problema, sl.slas_desc as sla, sl.slas_tempo as tempo , l.*, pr.*, res.slas_tempo as resposta
            from localizacao as l left join prioridades as pr on pr.prior_cod = l.loc_prior left join sla_solucao as res on res.slas_cod = pr.prior_sla, problemas as p left join sla_solucao as sl on p.prob_sla = sl.slas_cod,
            ocorrencias as o, sistemas as s
            where o.status=4 and s.sis_id=o.sistema and p.prob_id = o.problema  and o.local =l.loc_id";

	if (!empty($area) and ($area != -1) and (($area == $s_area)||($s_nivel==1))) // variavel do select name
	{
	    $query .= " and o.sistema = $area";
	} else
	if ($s_nivel!=1){
		print "<script>window.alert('Voc� s� pode consultar os dados da sua �rea!');</script>";
		print "<script>history.back();</script>";
		exit;
	}

	if ((empty($d_ini)) and (empty($d_fim)))
	{
		$aviso = "O per�odo deve ser informado.";
        $origem = 'javascript:history.back()';
        session_register("aviso");
        session_register("origem");
         print "<script>window.alert('O per�odo deve ser informado!'); history.back();</script>";
		//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
	}
	else
	{
       $d_ini = str_replace("-","/",$d_ini);
	   $d_fim = str_replace("-","/",$d_fim);
	   $d_ini_nova = converte_dma_para_amd($d_ini);
       $d_fim_nova = converte_dma_para_amd($d_fim);

	   $d_ini_completa = $d_ini_nova.$hora_inicio;
       $d_fim_completa = $d_fim_nova.$hora_fim;


		if($d_ini_completa <= $d_fim_completa)
	    {
			//$dias_va  //Alterado de data_abertura para data_fechamento -- ordena mudou de fechamento para abertura
		   $query .= " and o.data_fechamento >= '$d_ini_completa' and o.data_fechamento <= '$d_fim_completa' and
					    o.data_atendimento is not null order by o.data_abertura";
		   $resultado = mysql_query($query);       // print "<b>Query--></b> $query<br><br>";
		   $linhas = mysql_num_rows($resultado);  //print "Linhas: $linhas";
		  // $row = mysql_fetch_array($resultado);

		    if($linhas==0)
			   {
		       		$aviso = "N�o h� dados no per�odo informado. <br>Refa�a sua pesquisa.";
			        $origem = 'javascript:history.back()';
			        session_register("aviso");
			        session_register("origem");
		            //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
					 print "<script>window.alert('N�o h� dados no per�odo informado!'); history.back();</script>";
		       }
		    else //if($linhas==0)
		   	   	{
			   		$campos=array();
					switch($saida)
					{
						case -1:

                            echo "<br><br>";
							$background = '#CDE5FF';
							print "<p class='titulo'>RELAT�RIO DE SLAS: INDICADORES DE RESPOSTA e INDICADORES DE SOLU��O</p>";
                            print "<table class='centro' cellspacing='0' border='1' >";

                            print "<tr><td bgcolor=$background><B>NUMERO</td>
									   <td bgcolor=$background ><B>PROBLEMA</td>
                                       <td bgcolor=$background ><B>ABERTURA</td>
									   <td bgcolor=$background ><B>1� RESPOSTA</td>
									   <td bgcolor=$background ><B>FECHAMENTO</td>
									   <td bgcolor=$background ><b>T RESPOSTA VALIDO</td>
									   <td bgcolor=$background ><b>T SOLUCAO VALIDO</td></B>
									   <td bgcolor=$background ><b>EM ATENDIMENTO</td></B>
                                       <td bgcolor=$background ><b>SLA Resposta</td></B>
                                       <td bgcolor=$background ><b>SLA Solu��o</td></B>
                                       <td bgcolor=$background ><b>Resposta</td></B>
                                       <td bgcolor=$background ><b>Solu��o</td></B>
                                       <td bgcolor=$background ><b>SOL - RESP</td></B>
								  </tr>";


                           //INICIALIZANDO CONTADORES!!
                            $sla_green=0;
							$sla_red=0;
							$sla_yellow=0;
						  	$sla_16h=0;

							$slaR_green=0;
							$slaR_red=0;
							$slaR_yellow=0;

							$c_slaS_blue = 0;
                            $c_slaS_yellow = 0;
                            $c_slaS_red = 0;
                            $c_slaS_16h = 0;

							$c_slaR_blue = 0;
                            $c_slaR_yellow = 0;
                            $c_slaR_red = 0;
                            $c_slaM_blue = 0;
                            $c_slaM_yellow = 0;
                            $c_slaM_red = 0;
                            $c_slaR_checked = 0;
                            $c_slaS_checked = 0;
                            $c_slaM_checked = 0;
                            $imgSlaS = 'checked.png';
                            $imgSlaR = 'checked.png';
                            $imgSlaM = 'checked.png';




							$dtS = new dateOpers; //solu��o
							$dtR = new dateOpers; //resposta
							$dtM = new dateOpers; //tempo entre resposta e solu��o

                             $cont=0;
                             while ($row = mysql_fetch_array($resultado))
							 {
								// if (array_key_exists($row['cod_area'],$H_horarios)){  //verifica se o c�digo da �rea possui carga hor�ria definida no arquivo config.inc.php
									// $area = $row['cod_area']; //Recebe o valor da �rea de atendimento do chamado
								// } else $area = 1; //Carga hor�ria default definida no arquivo config.inc.php
								$area=testaArea($area,$row['cod_area'],$H_horarios);

								$dtR->setData1($row['data_abertura']);
								$dtR->setData2($row['data_atendimento']);
								$dtR->tempo_valido($dtR->data1,$dtR->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

                                $dtS->setData1($row['data_abertura']);
								$dtS->setData2($row['data_fechamento']);
								$dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");
								$t_horas = $dtS->diff["hValido"];

                                $dtM->setData1($row['data_atendimento']);
								$dtM->setData2($row['data_fechamento']);
								$dtM->tempo_valido($dtM->data1,$dtM->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");



                    //-----------------------------------------------------------------
                                //PARA CHECAR O SLA DO PROBLEMA -  TEMPO DE SOLU��O
                                $t_segundos_total = $dtS->diff["sValido"];

                            if ($row['tempo'] !=""){
                                if ($t_segundos_total <= ($row['tempo']*60))  { //transformando em segundos
                                        //$corSLA = $corSla1;
                                        $imgSlaS = 'sla1.png';
                                        $c_slaS_blue++;
                                }
                                else if ($t_segundos_total <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
                                        //$corSLA = $corSla2;
                                        $imgSlaS = 'sla2.png';
                                        $c_slaS_yellow++;
                               } else {
                                    //$corSLA = $corSla3;
                                    $imgSlaS = 'sla3.png';
                                    $c_slaS_red++;
                                }
                            } else {
                                $imgSlaS = 'checked.png';
                                $c_slaS_checked++;
                            }
                   //-------------------------------------------------------------------
                             //PARA CHECAR O SLA DO SETOR - TEMPO DE RESPOSTA

                            $t_segundos_resposta = $dtR->diff["sValido"];
                            if ($row['resposta'] != "") {
                                if ($t_segundos_resposta <= ($row['resposta']*60))  { //transformando em segundos
                                        //$corSLA = $corSla1;
                                        $imgSlaR = 'sla1.png';
                                        $c_slaR_blue++;
                                }
                                else if ($t_segundos_resposta <= ( ($row['resposta']*60) + (($row['resposta']*60) *$percLimit/100)) ){ //mais 20%
                                        //$corSLA = $corSla2;
                                        $imgSlaR = 'sla2.png';
                                        $c_slaR_yellow++;
                               } else {
                                    //$corSLA = $corSla3;
                                    $imgSlaR = 'sla3.png';
                                    $c_slaR_red++;
                                }
                            } else {
                                $c_slaR_checked++;
                                $imgSlaR = 'checked.png';
                            }
                    //-----------------------------------------------------------------------

                                $t_segundos_m = $dtM->diff["sValido"];

                            if ($row['tempo'] !=""){
                                if ($t_segundos_m <= ($row['tempo']*60))  { //transformando em segundos
                                        $imgSlaM = 'sla1.png';
                                        $c_slaM_blue++;
                                }
                                else if ($t_segundos_m <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
                                        $imgSlaM = 'sla2.png';
                                        $c_slaM_yellow++;
                               } else {
                                    $imgSlaM = 'sla3.png';
                                    $c_slaM_red++;
                                }
                            } else {
                                $imgSlaM = 'checked.png';
                                $c_slaM_checked++;
                            }





								//---N�o exclui as solu��es acima de 6 horas - � apenas um EXTRA solicitado pelo setor de Sistemas: chamado: 23761
								if ($t_horas>$sla16){
									$c_slaS_16h++;
									$chamados16[]=$row['numero'];
								}
								//-----
                                if ($t_horas>=$sla3) //>=6
								{
								    $cor = $corSla3;
									$sla_red++;
									$chamadosRed[] = $row['numero'];
								}
								else
							      if ($t_horas>=$sla2)
								  {
									$cor = $corSla2;
									$sla_yellow++;
									$chamadosOrange[] = $row['numero'];
								  }
								  else
								  {
									$cor = $corSla1;
									$sla_green++;
									$chamadosGreen[] = $row['numero'];
								  }
				#######################################################################
								$t_resp = $dtR->diff["sValido"];

                                if ($t_resp>=$slaR3) //>=6
								{
								    $corR = $corSla3;
									$slaR_red++;
								}
								else
							      if ($t_resp>=$slaR2)
								  {
									$corR = $corSla2;
									$slaR_yellow++;
								  }
								  else
								  {
									$corR = $corSla1;
									$slaR_green++;
								  }






								$chamados="";
								for ($i=0; $i<count($chamados16); $i++){
									$chamados.= "$chamados16[$i],";
								}
								if (strlen($chamados)>0) {
									$chamados = substr($chamados,0,-1);
								}





								$total_sol_segundos+= $dtS->diff["sFull"];
								$total_res_segundos+=$dtR->diff["sFull"];
								$total_res_valido+=$dtR->diff["sValido"];
								$total_sol_valido+=$dtS->diff["sValido"];

								print "<tr id='linha".$cont."' onMouseOver=\"destaca('linha".$cont."');\" onMouseOut=\"libera('linha".$cont."');\" onMouseDown=\"marca('linha".$cont."')\">";
									print "<td class='line'><a onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>$row[numero]</font></a></td>
                                            <td class='line'>$row[problema]</td>
                                            <td class='line'>$row[data_abertura]</td>
										   <td class='line'>$row[data_atendimento]</td>
										   <td class='line'>$row[data_fechamento]</td>
										   <td class='line'><font color=$corR>".$dtR->tValido."</font></td>
										   <td class='line'><font color=$cor>".$dtS->tValido."</font></td>
										   <td class='line'>".$dtM->tValido."</td>
                                           <td class='line'>".$row['resposta']." minutos</font></td>
                                           <td class='line'>".$row['sla']."</font></td>
                                           <td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaR."'></a></td>
                                           <td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaS."'></a></td>
                                           <td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaM."'></a></td>
									  </tr>";
								$cont++;
							 }//while

							$media_resposta_geral = $dtR->secToHour(floor($total_res_segundos/$linhas));
							$media_solucao_geral = $dtS->secToHour(floor($total_sol_segundos/$linhas));
							$media_resposta_valida = $dtR->secToHour(floor($total_res_valido/$linhas));
							$media_solucao_valida = $dtS->secToHour(floor($total_sol_valido/$linhas));

							print "<tr ><td colspan=5><b>M�DIAS -></td><td class='line'><b>$media_resposta_valida</td><td class='line'><B>$media_solucao_valida</td></tr>";

							//MEDIAS DE SOLU��O
							$perc_ate_sla2=round((($sla_green*100)/$linhas),2);
							$perc_ate_sla3=round((($sla_yellow*100)/$linhas),2);
							$perc_mais_sla3=round((($sla_red*100)/$linhas),2);
							$perc_sla16=round((($c_slaS_16h*100)/$linhas),2);

							//MEDIAS DE RESPOSTA
							$perc_ate_slaR2=round((($slaR_green*100)/$linhas),2);
							$perc_ate_slaR3=round((($slaR_yellow*100)/$linhas),2);
							$perc_mais_slaR3=round((($slaR_red*100)/$linhas),2);

							$slaR2M = $slaR2/60;
							$slaR3M = $slaR3/60;
	#####################################################################################
			//TOTAL DE HORAS V�LIDAS NO PER�ODO:
							$area = 1;//Padrao
							$dt = new dateOpers;
							$dt->setData1($d_ini_completa);
							$dt->setData2($d_fim_completa);
							$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");
							$hValido = $dt->diff["hValido"]+1; //Como o per�odo passado n�o � arredondado (xx/xx/xx 23:59:59) � necess�rio arrendondar o total de horas.
     ####################################################################################
							print "</table>";
							print "<table align='center'>";
							print "  <tr><td colspan =4></td><td class='line'></td></tr>";
							print "  <tr><td colspan=4 align=center><b>Per�odo: ".$d_ini." a ".$d_fim."</b></td></tr>";
							print "  <tr><td colspan=4 align=center><b>Total de horas v�lidas no per�odo: ".$hValido."</b></td></tr>";
							print "  <tr><td colspan='4' align='center'><b>Total de chamados fechados no per�odo: $linhas.</b></td></tr>";
                            print "  <tr><td colspan =4></td></tr>";
							print "<tr><td class='line'><b>Resposta em at� ".$slaR2M." minutos:</b></TD><td class='line'><font color=".$corSla1."> $slaR_green chamados = </font></TD><td class='line'><font color=".$corSla1.">$perc_ate_slaR2%</font></td><td class='line'></td></tr>";
							print "<tr><td class='line'><b>Resposta em at� ".$slaR3M." minutos:</b></TD><td class='line'><font color=".$corSla2."> $slaR_yellow chamados = </font></TD><td class='line'><font color=".$corSla2.">$perc_ate_slaR3%</font></td><td class='line'></td></tr>";
							print "<tr><td class='line'><b>Resposta em mais de ".$slaR3M." minutos:</b></TD><td class='line'><font color=".$corSla3."> $slaR_red chamados = </font></TD><td class='line'><font color=".$corSla3.">$perc_mais_slaR3%</font></td><td class='line'></td></tr>";
							print "  <tr><td colspan=4><hr></td></tr>";

							print "<tr><td class='line'><b>Solu��o em at� ".$sla2." horas:</b></TD><td class='line'><font color=".$corSla1."> $sla_green chamados = </font></TD><td class='line'><font color=".$corSla1.">$perc_ate_sla2%</font></td><td class='line'></td></tr>";
							print "<tr><td class='line'><b>Solu��o em at� ".$sla3." horas:</b></TD><td class='line'><font color=".$corSla2."> $sla_yellow chamados = </font></TD><td class='line'><font color=".$corSla2.">$perc_ate_sla3%</font></td><td class='line'></td></tr>";
							print "<tr><td class='line'><b>Solu��o em mais de ".$sla3." horas:</TD><td class='line'></b><font color=".$corSla3."> $sla_red chamados = </font></TD><td class='line'><font color=".$corSla3.">$perc_mais_sla3%</font></td><td class='line'></td></tr>";
							print "  <tr><td colspan=4><hr></td></tr>";

							print "<tr><td class='line'><b>Solu��o acima de ".$sla16." horas:</b></TD><td class='line'><font color=".$corSla3."> <a href=\"#\" onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$chamados."')\">$c_slaS_16h</a> chamados = </font></TD><td class='line'><font color=".$corSla3.">$perc_sla16%</font></td><td class='line'></td></tr>";
							print "  <tr><td colspan=4><hr></td></tr>";



                            $perc_blueS = (round($c_slaS_blue*100/$linhas,2));
                            $perc_yellowS = (round($c_slaS_yellow*100/$linhas,2));
                            $perc_redS = (round($c_slaS_red*100/$linhas,2));
                            $perc_checkedS = (round($c_slaS_checked*100/$linhas,2));
                            $perc_blueR = (round($c_slaR_blue*100/$linhas,2));
                            $perc_yellowR = (round($c_slaR_yellow*100/$linhas,2));
                            $perc_redR = (round($c_slaR_red*100/$linhas,2));
                            $perc_checkedR = (round($c_slaR_checked*100/$linhas,2));
                            $perc_blueM = (round($c_slaM_blue*100/$linhas,2));
                            $perc_yellowM = (round($c_slaM_yellow*100/$linhas,2));
                            $perc_redM = (round($c_slaM_red*100/$linhas,2));
                            $perc_checkedM = (round($c_slaM_checked*100/$linhas,2));


                            print "<tr><td colspan='4' align='center'><b>Tempo de Resposta X SLA definidos</b></td></tr>";
                            print "<tr><td class='line'><b>Resposta dentro do SLA:</td><td class='line'>".$c_slaR_blue." chamados</b></td><td class='line'>".$perc_blueR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td></tr>";
                            print "<tr><td class='line'><b>Resposta at� ".$percLimit."% acima do SLA:</td><td class='line'>".$c_slaR_yellow." chamados</b></td><td class='line'>".$perc_yellowR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td></tr>";
                            print "<tr><td class='line'><b>Resposta acima de ".$percLimit."% do SLA:</td><td class='line'>".$c_slaR_red." chamados</b></td><td class='line'>".$perc_redR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td></tr>";
                            print "<tr><td class='line'><b>Tempo de resposta n�o definido para o setor:</td><td class='line'>".$c_slaR_checked." chamados</b></td><td class='line'>".$perc_checkedR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/checked.png'></td></tr>";
                            print "  <tr><td colspan=4><hr></td></tr>";


                            print "<tr><td colspan='4' align='center'><b>Tempo de Solu��o X SLA definidos</b></td></tr>";
                            print "<tr><td class='line'><b>Solu��o dentro do SLA:</td><td class='line'>".$c_slaS_blue." chamados</b></td><td class='line'>".$perc_blueS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td></tr>";
                            print "<tr><td class='line'><b>Solu��o at� ".$percLimit."% acima do SLA:</b></td><td class='line'>".$c_slaS_yellow." chamados</td><td class='line'>".$perc_yellowS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td></tr>";
                            print "<tr><td class='line'><b>Solu��o acima de ".$percLimit."% do SLA:</b></td><td class='line'>".$c_slaS_red." chamados</td><td class='line'>".$perc_redS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td></tr>";
                            print "<tr><td class='line'><b>Tempo de solu��o n�o definido para o problema:</b></td><td class='line'>".$c_slaS_checked." chamados</td><td class='line'>".$perc_checkedS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/checked.png'></td></tr>";
                            print "  <tr><td colspan=4><hr></td></tr>";


                            print "<tr><td colspan='4' align='center'><b>Tempo de Solu��o a partir da 1.� resposta</b></td></tr>";
                            print "<tr><td class='line'><b>Solu��o dentro do SLA:</td><td class='line'>".$c_slaM_blue." chamados</b></td><td class='line'>".$perc_blueM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td></tr>";
                            print "<tr><td class='line'><b>Solu��o at� ".$percLimit."% acima do SLA:</b></td><td class='line'>".$c_slaM_yellow." chamados</td><td class='line'>".$perc_yellowM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td></tr>";
                            print "<tr><td class='line'><b>Solu��o acima de ".$percLimit."% do SLA:</b></td><td class='line'>".$c_slaM_red." chamados</td><td class='line'>".$perc_redM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td></tr>";
                            print "<tr><td class='line'><b>Tempo de solu��o n�o definido para o problema:</b></td><td class='line'>".$c_slaM_checked." chamados</td><td class='line'>".$perc_checkedM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/checked.png'></td></tr>";
                            print "</table>";
                            //print $query;




							break;

						case 1:
							$campos=array();
							$campos[]="numero";
							$campos[]="data_abertura";
							$campos[]="data_atendimento";
							$campos[]="data_fechamento";
							$campos[]="t_res_hora";
							$campos[]="t_sol_hora";
							$campos[]="t_res_valida_hor";
							$campos[]="t_sol_valida_hor";

							$cabs=array();
							$cabs[]="N�mero";
							$cabs[]="Abertura";
							$cabs[]="1� Resposta";
							$cabs[]="Fechamento";
							$cabs[]="T Resposta Total";
							$cabs[]="T Solu��o Total";
							$cabs[]="T Resposta V�lido";
							$cabs[]="T Solu��o V�lido";

							$logo="logo_unilasalle.gif";
							$msg1="Centro de Inform�tica";
							$msg2=date('d/m/Y H:m');
							$msg3= "Relat�rio de SLA's";

							gera_relatorio(1,$query,$campos,$cabs,$logo,$msg1, $msg2, $msg3);
							break;
					} // switch
				} //if($linhas==0)
			}//if  $d_ini_completa <= $d_fim_completa
			else
			{
				$aviso = "A data final n�o pode ser menor <br>do que a data inicial.<br>Refa�a sua pesquisa.";
		        $origem = 'javascript:history.back()';
		        session_register("aviso");
		        session_register("origem");
		        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
	?>
        <script type='text/javascript'>

			 function popup(pagina)	{ //Exibe uma janela popUP
				x = window.open(pagina,'popup','width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
				//x.moveTo(100,100);
				x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
				return false
			 }

			 function popup_alerta(pagina)	{ //Exibe uma janela popUP
                x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
                //x.moveTo(100,100);
                x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
                return false
             }



	  </script>
    <?php 



}//if $ok==Pesquisar
?>
