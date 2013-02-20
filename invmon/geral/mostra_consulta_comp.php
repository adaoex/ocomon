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
	$cab = new headers;
	$cab->set_title(TRANS('TTL_OCOMON'));

	$hoje = date("d-m-Y H:i:s");
	$hojeDia = date("y-m-d");
	$hoje_termo = date("d/m/Y H:i:s");
	$logo = LOGO_PATH.'/logo_lasalle.gif';



	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	if ($_SESSION['s_nivel']==1)
	{
		$administrador = true;
	} else
		$administrador = false;

	if (!isset($_REQUEST['header'])) {
		$header= TRANS('TXT_REPORT_PERSON');
	} else
		$header = $_REQUEST['header'];


	print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";


	$qry = "SELECT conf_page_size AS page FROM config";
	$qry_exec = mysql_query($qry) or die (TRANS('MSG_NECESS_UPDATE_TABLE_CONF'));
	$rowConf = mysql_fetch_array($qry_exec);
	$PAGE_SIZE = $rowConf['page'];


	//Verifica se a coluna j� est� ordenada e seta para ser ordenada em ordem inversa!!
	$az = "";
	$mostra = "";
	$ordenado = "";

	$ICON_ORDER['etiqueta'] = "";
	$ICON_ORDER['instituicao'] = "";
	$ICON_ORDER['tipo'] = "";
	$ICON_ORDER['modelo'] = "";
	$ICON_ORDER['local'] = "";
	$ICON_ORDER['situacao'] = "";

	if (isset($_REQUEST['ordenado'])){
		$ordenado = $_REQUEST['ordenado'];
	} else
		$ICON_ORDER['etiqueta'] = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";

	if (isset($_REQUEST['coluna']) ) {
	 	if (isset($_REQUEST['ordenado']))
	 	if ($_REQUEST['coluna'] == $_REQUEST['ordenado']) {
			$az = " desc";
			$ordenado = "";
			$mostra = " ".TRANS('TXT_ORDER_BY_DESC');

			$ICON_ORDER['etiqueta'] = "";
			$ICON_ORDER['instituicao'] = "";
			$ICON_ORDER['tipo'] = "";
			$ICON_ORDER['modelo'] = "";
			$ICON_ORDER['local'] = "";
			$ICON_ORDER['situacao'] = "";

			$ICON_ORDER[$_REQUEST['coluna']] = "<img src='../../includes/css/OrderDesc.png' width='16' height='16' align='absmiddle'>";
	 	} else {
			$ordenado = $_REQUEST['coluna'];
			$az = " asc";
			$mostra = " ".TRANS('TXT_ORDER_BY_INCRESC');

			$ICON_ORDER['etiqueta'] = "";
			$ICON_ORDER['instituicao'] = "";
			$ICON_ORDER['tipo'] = "";
			$ICON_ORDER['modelo'] = "";
			$ICON_ORDER['local'] = "";
			$ICON_ORDER['situacao'] = "";

			$ICON_ORDER[$_REQUEST['coluna']] = "<img src='../../includes/css/OrderAsc.png' width='16' height='16' align='absmiddle'>";
	 	}
	}

	//Para n�o precisar escrever na tela todos os crit�rios de ordena��o eu defino aqui o que deve aparecer!!
	$traduz = array("etiqueta".$az.""=>TRANS('OCO_FIELD_TAG').$mostra,
		"fab_nome".$az.",modelo".$az."" => TRANS('COL_MODEL').$mostra,
		"fab_nome".$az.",modelo".$az.",etiqueta".$az.""=> TRANS('COL_MODEL').$mostra,
		"modelo".$az.",etiqueta".$az.""=> TRANS('COL_MODEL').$mostra,
		"instituicao".$az.",etiqueta".$az."" =>TRANS('OCO_FIELD_UNIT').$mostra,
		"equipamento".$az.",modelo".$az."" =>$TRANS["col_tipo"].$mostra,
		"local".$az."" =>TRANS('col_local').$mostra,
		"equipamento".$az.",fab_nome".$az.",modelo".$az.",etiqueta".$az."" => TRANS('COL_TYPE').$mostra,
		"equipamento".$az.",fab_nome".$az.",modelo".$az.",local".$az.",etiqueta".$az.""=> TRANS('COL_TYPE').$mostra,
		"equipamento".$az.",modelo".$az.",local".$az.",etiqueta".$az."" => TRANS('COL_TYPE').$mostra,
		"fab_nome".$az.",modelo".$az.",local".$az.",etiqueta".$az.""=> TRANS('COL_MANUFACTURE').$mostra,
		"local".$az.",etiqueta".$az.""=> TRANS('COL_LOCALIZATION').$mostra,
		"local".$az.",equipamento".$az.",fab_nome".$az.",modelo".$az.",etiqueta".$az.""=>TRANS('COL_LOCALIZATION').$mostra,
		"serial".$az.""=> TRANS('COL_SN').$mostra,
		"nota".$az.""=> TRANS('COL_NF').$mostra,
		"situac_nome".$az.",etiqueta".$az.""=> TRANS('COL_SITUAC').$mostra,
		"situac_nome".$az.""=> TRANS('COL_SITUAC').$mostra,
		"tipo,localiza��o".$az."" => TRANS('COL_TYPE').$mostra);

	if (isset($_REQUEST['visualiza'])) {
		if ($_REQUEST['visualiza']!='impressora' && $_REQUEST['visualiza']!='texto' && $_REQUEST['visualiza']!='relatorio' &&
			$_REQUEST['visualiza']!='mantenedora1' && $_REQUEST['visualiza']!='config' && $_REQUEST['visualiza']!='termo' &&
			$_REQUEST['visualiza']!='transito') {

		} else {
			print "<body class='relatorio' >";
		}
	} else {
		//print "<body class='relatorio' >";
	}
	print "<BODY  onLoad=\"checar();\">"; //bgcolor=".BODY_COLOR."


/*	if (!isset($_GET['negar'])) {
		$negar = "";
	} else
		$negar = $_GET['negar'];*/


	//C�digo para definir o array de unidades como sendo array de uma �nica posi��o
	$comp_inst ="";
	if (isset($_GET['comp_inst'])) {
		$comp_inst = $_GET['comp_inst'];
	} else
	if (isset($_POST['comp_inst'])){
		$comp_inst = $_POST['comp_inst'];
	}

	if (!isset($_POST['saida']) && !empty($comp_inst))
	{
		$saida="";
		if (is_array($comp_inst)) {
			for ($i=0; $i<count($comp_inst); $i++){
				$saida.= "$comp_inst[$i],";
			}
		} else
			$saida=$comp_inst;

		if (strlen($saida)>0) {
			$saida = substr($saida,0,-1);
		}
		$comp_inst = $saida;
	}
	################################################################
	$comp_inv ="";
	if (isset($_GET['comp_inv'])) {
		$comp_inv = $_GET['comp_inv'];
	} else
	if (isset($_POST['comp_inv'])){
		$comp_inv = $_POST['comp_inv'];
	}

	/**
	*@min = Vari�vel referente o primeiro parametro do "limit" na montagem da clausula SLQ
	*@maxAux = Vari�vel auxiliar para a montagem dos botoes de navegacao.
	*@minAux = Vari�vel auxiliar para a montagem dos bot�es de navegacao.
	*
	*/

	$min = 0;
	$maxAux = 0;
	$minAux = 0;
	//$page = 50;

	$msgInst = "";
	$checked = "";
	$comp_inv_flag = false;
	$comp_sn_flag = false;
	$comp_marca_flag = false;
	$comp_mb_flag = false;
	$comp_proc_flag = false;
	$comp_memo_flag = false;
	$comp_video_flag = false;
	$comp_som_flag = false;
	$comp_rede_flag = false;
	$comp_modem_flag = false;
	$comp_modelohd_flag = false;
	$comp_cdrom_flag = false;
	$comp_dvd_flag = false;
	$comp_grav_flag = false;
	$comp_local_flag = false;
	$comp_reitoria_flag = false;
	$comp_nome_flag = false;
	$comp_fornecedor_flag = false;
	$comp_nf_flag = false;
	$comp_inst_flag = false;
	$comp_tipo_equip_flag = false;
	$comp_fab_flag = false;
	$comp_tipo_imp_flag = false;
	$comp_polegada_flag = false;
	$comp_resolucao_flag = false;
	$comp_ccusto_flag = false;
	$comp_situac_flag = false;
	$comp_data_flag = false;
	$comp_data_compra_flag = false;
	$garantia_flag = false;
	$soft_flag = false;
	$comp_assist_flag = false;
	$comp_memo_notnull = false;
	$comp_memo_null = false;
	$tmpData = array();


	if (isset($_GET['encadeado'])) {
		$checked = "checked";
	}

 	$query = $QRY["full_detail_ini"];	// ../includes/queries/

        if (isset($_REQUEST['negado']))
	{
		$negado = $_REQUEST['negado'];
	} else
		$negado = false;


	if (empty($logico)) {
		$logico = " and ";
	}

	if (empty($sinal)) {
		$sinal = "=";
		$neg = "";
	}

	if (!empty($comp_inv)) {
		$comp_inv_flag = true;
		$query.= "$logico (c.comp_inv in (".$comp_inv.")) ";
	}

        if (isset($_REQUEST['comp_sn']))
	{
		if ($_REQUEST['comp_sn'] != '') {
			$comp_sn_flag = true;
			$comp_sn = strtoupper($_REQUEST['comp_sn']);
			$query.= "$logico (UPPER(c.comp_sn) = '".$comp_sn."') ";
		}
	}  else
		$comp_sn = "";

        if (isset($_REQUEST['comp_marca'])) {
		if (($_REQUEST['comp_marca'] != -1) && ($_REQUEST['comp_marca'] != '')) {
			$comp_marca_flag = true;
			$query.= " ".$logico." (c.comp_marca = ".$_REQUEST['comp_marca'].") ";
			$sinal_marca = "=";
		}
	}

	if (isset($_REQUEST['comp_mb'])) {
		if (($_REQUEST['comp_mb'] != -1) && ($_REQUEST['comp_mb'] != '')) {
			$comp_mb_flag = true;
			$query.= " ".$logico." (c.comp_mb = ".$_REQUEST['comp_mb'].") ";
		}
	}

	if (isset($_REQUEST['comp_proc'])) {
		if (($_REQUEST['comp_proc'] !=-1) && ($_REQUEST['comp_proc'] !='')) {
			$comp_proc_flag = true;
			$query.=" ".$logico." (c.comp_proc = ".$_REQUEST['comp_proc'].") ";
		}
	}


	if (isset($_REQUEST['comp_memo'])) {
		if (($_REQUEST['comp_memo'] != -1) && ($_REQUEST['comp_memo'] !='')) {
			if ($_REQUEST['comp_memo']==-2) {
				$comp_memo_notnull = true;
				$query.=" ".$logico." (c.comp_memo is not null)";
			} else
			if ($_REQUEST['comp_memo']==-3) {
				$comp_memo_null = true;
				$query.=" ".$logico." (c.comp_memo is null)";
			} else {
				$comp_memo_flag = true;
				$query.=" ".$logico." (c.comp_memo = ".$_REQUEST['comp_memo'].") ";
			}
		}
	}


	if (isset($_REQUEST['comp_video'])) {
		if (($_REQUEST['comp_video'] != -1) && ($_REQUEST['comp_video'] !='')) {
			$comp_video_flag = true;
			$query.= " ".$logico." (c.comp_video = ".$_REQUEST['comp_video'].") ";
		}
	}

	if (isset($_REQUEST['comp_som'])) {
		if (($_REQUEST['comp_som'] != -1) && ($_REQUEST['comp_som']!= '')) {
			$comp_som_flag = true;
			$query.= " ".$logico." (c.comp_som = ".$_REQUEST['comp_som'].") ";
		}
	}

	if (isset($_REQUEST['comp_rede'])) {
		if (($_REQUEST['comp_rede'] != -1) && ($_REQUEST['comp_rede'] !='')) {
			$comp_rede_flag = true;
			$query.= " ".$logico." (c.comp_rede = ".$_REQUEST['comp_rede'].") ";
		}
	}

	if (isset($_REQUEST['comp_modem'])) {
		if (($_REQUEST['comp_modem'] != -1) && ($_REQUEST['comp_modem'] !='')) {
			$comp_modem_flag = true;
			if ($_REQUEST['comp_modem'] ==-2) {$query.= "and (c.comp_modem is null or c.comp_modem = 0)";} else
			if ($_REQUEST['comp_modem'] ==-3) {$query.= "and (c.comp_modem is not null and c.comp_modem != 0)";} else
				$query.= " ".$logico." (c.comp_modem = ".$_REQUEST['comp_modem'].") ";
		}
        }

	if (isset($_REQUEST['comp_modelohd'])) {
		if (($_REQUEST['comp_modelohd'] != -1)&& ($_REQUEST['comp_modelohd']!='')) {
			$comp_modelohd_flag = true;
			$query.= " ".$logico." (c.comp_modelohd = ".$_REQUEST['comp_modelohd'].") ";
		}
        }

	if (isset($_REQUEST['comp_cdrom'])) {
		if (($_REQUEST['comp_cdrom'] != -1) && ($_REQUEST['comp_cdrom']!='')) {
			$comp_cdrom_flag = true;
			if ($_REQUEST['comp_cdrom'] ==-2) {$query.= "and (c.comp_cdrom is null or c.comp_cdrom = 0)";} else
			if ($_REQUEST['comp_cdrom'] ==-3) {$query.= "and (c.comp_cdrom is not null and c.comp_cdrom != 0)";} else
				$query.= " ".$logico." (c.comp_cdrom = ".$_REQUEST['comp_cdrom'].") ";
		}
	}

	if (isset($_REQUEST['comp_dvd'])) {
		if (($_REQUEST['comp_dvd'] != -1) && ($_REQUEST['comp_dvd']!='')) {
			$comp_dvd_flag = true;
			$query.= "$logico (c.comp_dvd = ".$_REQUEST['comp_dvd'].") ";
		}
        }

	if (isset($_REQUEST['comp_grav'])) {
		if (($_REQUEST['comp_grav'] != -1) && ($_REQUEST['comp_grav']!='')) {
			$comp_grav_flag = true;
			if ($_REQUEST['comp_grav'] ==-2) {$query.= "and (c.comp_grav is null or c.comp_grav = 0)";} else
			if ($_REQUEST['comp_grav'] ==-3) {$query.= "and (c.comp_grav is not null and c.comp_grav != 0)";} else
				$query.= " ".$logico." (c.comp_grav = ".$_REQUEST['comp_grav'].") ";
		}
	}


	if (isset($_REQUEST['comp_local'])) {
		if (($_REQUEST['comp_local'] != -1) && ($_REQUEST['comp_local']!='')) {
			$comp_local_flag = true;
			if ($negado== "comp_local") {
				$query.= "$logico (c.comp_local <> ".$_REQUEST['comp_local'].") ";
			} else
				$query.= "$logico (c.comp_local ".$sinal." ".$_REQUEST['comp_local'].") ";
		}
        }

	if (isset($_REQUEST['comp_reitoria'])) {// OBS: n�o existe o campo comp_reitoria, apenas usei esse nome para padronizar!
		if (($_REQUEST['comp_reitoria'] != -1) && ($_REQUEST['comp_reitoria']!='')) {
			$comp_reitoria_flag = true;
			$query.= "$logico (c.comp_reitoria = ".$_REQUEST['comp_reitoria'].") ";
		}
        }


	if (isset($_REQUEST['comp_nome'])) {
		if (!empty($_REQUEST['comp_nome'])) {
			$comp_nome_flag = true;
			$query.= "$logico (c.comp_nome = ".$_REQUEST['comp_nome'].") ";
		}
        }

	if (isset($_REQUEST['comp_fornecedor'])) {
		if (($_REQUEST['comp_fornecedor'] != -1) && ($_REQUEST['comp_fornecedor']!='')) {
			$comp_fornecedor_flag = true;
			$query.= "$logico (c.comp_fornecedor = ".$_REQUEST['comp_fornecedor'].") ";
		}
        }

	if (isset($_REQUEST['comp_nf'])) {
		if (!empty($_REQUEST['comp_nf'])) {
			$comp_nf_flag = true;
			$query.= "$logico (c.comp_nf = ".$_REQUEST['comp_nf'].") ";
		}
        }

        if (($comp_inst!= -1) and ($comp_inst!='')) {
		$comp_inst_flag = true;
		if ($negado== "comp_inst") {
			$query.= "$logico (c.comp_inst not in (".$comp_inst."))";
		} else
			$query.= "$logico (c.comp_inst in (".$comp_inst."))";
			if ($comp_inst ==1) {$logo = LOGO_PATH.'/logo_unilasalle.gif';} else
			if ($comp_inst ==2) {$logo = LOGO_PATH.'/logo_colegio.gif';}
	}


	if (isset($_REQUEST['comp_tipo_equip'])) {
		if (($_REQUEST['comp_tipo_equip'] != -1) && ($_REQUEST['comp_tipo_equip']!='')) {
			$comp_tipo_equip_flag = true;
			if ($negado== "comp_tipo_equip") {
				$query.= "$logico (c.comp_tipo_equip <> ".$_REQUEST['comp_tipo_equip'].") ";
			} else
				$query.= "$logico (c.comp_tipo_equip ".$sinal." ".$_REQUEST['comp_tipo_equip'].") ";
		}
        }

	if (isset($_REQUEST['comp_fab'])) {
		if (($_REQUEST['comp_fab'] != -1) && ($_REQUEST['comp_fab']!='')) {
			$comp_fab_flag = true;
			$query.= "$logico (c.comp_fab = ".$_REQUEST['comp_fab'].") ";
		}
        }

	if (isset($_REQUEST['comp_tipo_imp'])) {
		if (($_REQUEST['comp_tipo_imp'] != -1) && ($_REQUEST['comp_tipo_imp']!='')) {
			$comp_tipo_imp_flag = true;
			$query.= "$logico (c.comp_tipo_imp = ".$_REQUEST['comp_tipo_imp'].") ";
		}
        }

	if (isset($_REQUEST['comp_polegada'])) {
		if (($_REQUEST['comp_polegada'] != -1) && ($_REQUEST['comp_polegada']!='')) {
			$comp_polegada_flag = true;
			$query.= "$logico (c.comp_polegada = ".$_REQUEST['comp_polegada'].") ";
		}
        }

	if (isset($_REQUEST['comp_resolucao'])) {
		if (($_REQUEST['comp_resolucao'] != -1) && ($_REQUEST['comp_resolucao']!='')) {
			$comp_resolucao_flag = true;
			$query.= "$logico (c.comp_resolucao = ".$_REQUEST['comp_resolucao'].") ";
		}
        }
	if (isset($_REQUEST['comp_ccusto'])) {
		if (($_REQUEST['comp_ccusto'] != -1) && ($_REQUEST['comp_ccusto']!='')) {
			$comp_ccusto_flag = true;
			$query.= "$logico (c.comp_ccusto = ".$_REQUEST['comp_ccusto'].") ";
		}
        }

	if (isset($_REQUEST['comp_situac'])) {
		if (($_REQUEST['comp_situac'] != -1) && ($_REQUEST['comp_situac']!='')) {
			$comp_situac_flag = true;

/*			if ($negar == "NEG_SITUACAO") {
				$query.= $logico." (c.comp_situac <> ".$_REQUEST['comp_situac'].") ";

			} else
				$query.= $logico." (c.comp_situac ".$sinal." ".$_REQUEST['comp_situac'].") ";*/

			if ($negado== "comp_situac") {
				$query.= "$logico (c.comp_situac <> ".$_REQUEST['comp_situac'].") ";
			} else
				$query.= "$logico (c.comp_situac ".$sinal." ".$_REQUEST['comp_situac'].") ";
		}
        }

	if (isset($_REQUEST['comp_data'])) { //CADASTRO
		if ( ($_REQUEST['comp_data']!='')) {
			$comp_data_flag = true;
			$comp_data = $_REQUEST['comp_data'];

/*			if (strpos($_REQUEST['comp_data'],"-")) {
				$comp_data = substr(datam2($_REQUEST['comp_data']),0,10);
			}*/
			if (strpos($_REQUEST['comp_data']," ")) {
				$tmpData = explode(" ", $_REQUEST['comp_data']);
				$comp_data = $tmpData[0];
			}

			//$comp_data = substr(datam($comp_data),0,10);

			if (isset($_REQUEST['fromDateRegister'])) {
				$query.= "$logico (c.comp_data >='".$comp_data."')";
			} else {
				$query.= "$logico (c.comp_data like ('".$comp_data."%'))";
			}
		}
        } //else
        	//$comp_data = "";

	if (isset($_REQUEST['comp_data_compra'])) { //CADASTRO
		if ( ($_REQUEST['comp_data_compra']!='')) {
			$comp_data_compra_flag = true;
			$comp_data_compra = $_REQUEST['comp_data_compra'];

			//$comp_data_compra = substr(datam($comp_data_compra),0,10);
			if (strpos($_REQUEST['comp_data_compra']," ")) {
				$tmpData = explode(" ", $_REQUEST['comp_data_compra']);
				$comp_data_compra = $tmpData[0];
			}


			$query.= "$logico (c.comp_data_compra like ('".$comp_data_compra."%'))";
		}
        }

	if (isset($_REQUEST['garantia'])) {
		if (($_REQUEST['garantia'] == 1) && ($_REQUEST['garantia']==2)) {
			$garantia_flag = true;
			if ($_REQUEST['garantia'] == 1){
				$consulta= TRANS('TXT_IN_GUARANT');
				$query.="and (date_add(c.comp_data_compra, interval tmp.tempo_meses month) >=now())";
			} else {
				$consulta= TRANS('TXT_GUARANT_OUTSIDE');
				$query.="and (date_add(c.comp_data_compra, interval tmp.tempo_meses month) <now() or comp_garant_meses is null)";
			}
		}
        }

	if (isset($_REQUEST['software'])) {
		if (($_REQUEST['software'] != -1) && ($_REQUEST['software']!='')) {
			$soft_flag = true;
			$query.= "$logico (soft.soft_cod = ".$_REQUEST['software'].") ";
		}
        }

	if (isset($_REQUEST['comp_assist'])) {
		if (($_REQUEST['comp_assist'] != -1) && ($_REQUEST['comp_assist']!='')) {
			$comp_assist_flag = true;
			if ($_REQUEST['comp_assist'] == -2) {
				$query.= "and (c.comp_assist is null)";
			} else
				$query.= "and (c.comp_assist ".$sinal." ".$_REQUEST['comp_assist'].")";
		}
        }

        //$query.=")";

		if (!isset($_REQUEST['ordena'])) {
			$ordena = "etiqueta";
		} else {
			$aux = explode(",",$_REQUEST['ordena']);
			$ordena= "";
			for ($i=0;$i<count($aux);$i++){
				$ordena.=$aux[$i].$az.",";
			}
			$ordena = substr($ordena,0,-1);
		}

		if (isset($_REQUEST['VENCIMENTO'])){
			$query.=  "AND comp_tipo_equip NOT IN (5) ".
				"AND date_add(date_format(comp_data_compra, '%Y-%m-%d') , INTERVAL tempo_meses MONTH) = '".$_REQUEST['VENCIMENTO']."'";
		}

		$query.= $QRY["full_detail_fim"];
		$query.= "  order by ".$ordena."";

		$traduzOrdena = strtr("$ordena", $traduz);

		//dump($query);
##################################################################################
	$qtdTotal = $query;
	$resultadoTotal = mysql_query($qtdTotal) or die (TRANS('MSG_ERR_IN_THE_QUERY').':<br>'.$qtdTotal);
	$linhasTotal = mysql_num_rows($resultadoTotal); //Aqui armazedo a quantidade total de registros
##################################################################################

		if ( (!isset($_REQUEST['visualiza'])) || ($_REQUEST['visualiza']=='tela')) { //condi��o para montar na tela os bot�es de navega��o

			/*------------------------------------------------------------------------------
			@$min = PRIMEIRO REGISTRO A SER EXIBIDO
			@$max = QUANTIDADE DE REGISTROS POR P�GINA
			@$top = N�MERO DO �LTIMO REGISTRO EXIBIDO DA P�GINA
			@$base = N�MERO DO PRIMEIRO REGISTRO EXIBIDO DA P�GINA
			--------------------------------------------------------------------------------*/

// 			$min = 0;
// 			$maxAux = 0;
// 			$minAux = 0;
// 			$page = 50;

			if (!isset($_POST['min']))  {
				$min =0;
			} else $min = $_POST['min'];

			if (!isset($_POST['max']))  {
				$max =$PAGE_SIZE;
				if ($max > $linhasTotal) {
					$maxAux = $max;
					$max = $linhasTotal;
				}
			} else {
				$max = $_POST['max'] ;//$linhasTotal;
				$maxAux = $_POST['max'];
				if ($max > $linhasTotal) {
					$maxAux = $max;
					$max = $linhasTotal;
				}
			}

			if (!isset($_POST['top'])) {
				if ($max < $linhasTotal) {
					$top = $max;
				} else
					$top = $linhasTotal;
			} else
				$top = $_POST['top'];

			if (!isset($_POST['base'])) {
				$base = $min+1;
			} else
				$base = $_POST['base'];

			if (isset($_POST['avancaUm'])) {
				$minAux = $min;
				$min += $max;
				if ($min >=($linhasTotal)) {
					$min = $minAux;
				}
				$top += $max;
				if ($top >$linhasTotal) {
					$base = $min+1;
					$top = $linhasTotal;
				} else {
					if ($base < (($top - $max))) {
						$base += $max;
					} else {
						$base-=$max;
					}
				}
			} else
			if (isset($_POST['avancaFim'])) {
				$minAux = $min;
				$min=$linhasTotal - $PAGE_SIZE;
				if ($min <=0) {
					$min = $minAux;
				}
				$top = $linhasTotal;
				$base = ($linhasTotal - $PAGE_SIZE)+1;
			} else
			if (isset($_POST['avancaTodos'])) {
				$max=$linhasTotal;
				$min=0;
				$top = $linhasTotal;
				$base = $linhasTotal - $max;
			} else
			if (isset($_POST['voltaUm']) ) {
				if (($_POST['max']==$linhasTotal) && ($_POST['min']==0)) {$max=$_POST['maxAux']; $min=$linhasTotal;}
					//Est� exibindo todos os registros na tela!

				$min-=$_POST['max'];
				if ($min<0) {$min=0;};

				if (($top - $base) < $max) {
					$top = $base -1;
				} else $top-=$max;
				$base-=$max;
			} else
			if (isset($_POST['voltaInicio']) ) {
				$min=0;
				//$max=$_POST['maxAux'];
				$max = $PAGE_SIZE;
				$top = $max;
				$base = 1;
			}

			$query.=" LIMIT ".$min.", ".$max."";

			if ($top > $linhasTotal) {
				$top = $linhasTotal;
			} else
			if ($top < $max) {
				$top = $max;
			}
			if ($base < 1) {
				$base = 1;
			}
		}


	$resultado = mysql_query($query) or die (TRANS('MSG_ERR_IN_THE_QUERY').': <BR>'.$query);
	$resultadoAux = mysql_query($query);
        $linhas = mysql_num_rows($resultado);

        $row = mysql_fetch_array($resultadoAux);

	######################################################

		//Titulo da consulta que retorna o crit�rio de pesquisa.
		//$texto ="com: ";
		$texto ="";
		$tam = (strlen($texto));
		$param ="&";
		$tamParam = (strlen($param));

		if ($comp_tipo_equip_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_TYPE_EQUIP')."</b> = ".$row['equipamento']."]"; //Escreve o crit�rio de pesquisa
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_tipo_equip=".$_REQUEST['comp_tipo_equip'].""; 	//Monta a lista de par�metros para a consulta
		};
		if ($comp_tipo_imp_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_TYPE_PRINTER')."</b> = ".$row['impressora']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_tipo_imp=".$_REQUEST['comp_tipo_imp']."";
		};
		if ($comp_polegada_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_MONITOR')."</b> = ".$row['polegada_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_polegada=".$_REQUEST['comp_polegada']."";
		};

		if ($comp_resolucao_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_SCANNER')."</b> = ".$row['resol_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_resolucao=".$_REQUEST['comp_resolucao']."";
		};

		if ($comp_inv_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('OCO_FIELD_TAG')."</b> = ".$comp_inv."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_inv=".$comp_inv."";
		};

		if ($comp_sn_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_SN')."</b> = ".$row['serial']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_sn=".$_REQUEST['comp_sn']."";
		};

		if ($comp_fab_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MANUFACTURE')."</b> = ".$row['fab_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_fab=".$_REQUEST['comp_fab']."";
		};


		if ($comp_marca_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MODEL')."</b> = ".$row['modelo']."]"; //$sinal
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_marca=".$_REQUEST['comp_marca']."";
		};

		if ($comp_mb_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_MB')."</b> = ".$row['fabricante_mb']." ".$row['mb']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_mb=".$_REQUEST['comp_mb']."";
		};
		if ($comp_proc_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_PROC')."</b> = ".$row['processador']." ".$row['clock']." ".$row['proc_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_proc=".$_REQUEST['comp_proc']."";
		};
	  	if ($comp_memo_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_MEMO')."</b> = ".$row['memoria']."".$row['memo_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};
	  	if ($comp_memo_notnull) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_MEMO')."</b> = ".TRANS('FIELD_NOT_NULL')."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};
	  	if ($comp_memo_null) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_MEMO')."</b> = ".TRANS('FEILD_NULL')."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_memo=".$_REQUEST['comp_memo']."";
		};

		if ($comp_video_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_VIDEO')."</b> = ".$row['fabricante_video']." ".$row['video']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_video=".$_REQUEST['comp_video']."";
		};
		if ($comp_som_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_SOM')."</b> = ".$row['fabricante_som']." ".$row['som']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_som=".$_REQUEST['comp_som']."";
		};
		if ($comp_cdrom_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_cdrom']==-2) {$texto.="[<b>".TRANS('MNL_CDROM')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_cdrom']==-3) {$texto.="[<b>".TRANS('MNL_CDROM')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('MNL_CDROM')."</b> = ".$row['fabricante_cdrom']." ".$row['cdrom']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_cdrom=".$_REQUEST['comp_cdrom']."";
		};

		if ($comp_grav_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_grav']==-2) {$texto.="[<b>".TRANS('FIELD_RECORD_CD')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_grav']==-3) {$texto.="[<b>".TRANS('FIELD_RECORD_CD')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('FIELD_RECORD_CD')."</b> = ".$row['fabricante_gravador']." ".$row['gravador']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_grav=".$_REQUEST['comp_grav']."";
		};

		if ($comp_dvd_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_dvd']==-2) {$texto.="[<b>".TRANS('MNL_DVD')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_dvd']==-3) {$texto.="[<b>".TRANS('MNL_DVD')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('MNL_DVD')."</b> = ".$row['fabricante_dvd']." ".$row['dvd']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_dvd=".$_REQUEST['comp_dvd']."";
		};


		if ($comp_modem_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if ($_REQUEST['comp_modem']==-2) {$texto.="[<b>".TRANS('FIELD_MODEM')."</b> = ".TRANS('MSG_NOT_POSS_NONE')."]";} else
			if ($_REQUEST['comp_modem']==-3) {$texto.="[<b>".TRANS('FIELD_MODEM')."</b> = ".TRANS('MSG_POSS_ANY_MODEL')."]";} else
			$texto.="[<b>".TRANS('FIELD_MODEM')."</b> = ".$row['fabricante_modem']." ".$row['modem']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_modem=".$_REQUEST['comp_modem']."";
		};

		if ($comp_modelohd_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_HD')."</b> = ".$row['fabricante_hd']." ".$row['hd_capacidade']."".$row['hd_sufixo']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_modelohd=".$_REQUEST['comp_modelohd']."";
		};
		if ($comp_rede_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('MNL_REDE')."</b> = ".$row['rede_fabricante']." ".$row['rede']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_rede=".$_REQUEST['comp_rede']."";
		};
		if ($comp_local_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_LOCALIZATION')."</b> = ".$row['local']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_local=".$_REQUEST['comp_local']."";
		};
		if ($comp_reitoria_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_MAJOR')."</b> = ".$row['reitoria']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_reitoria=".$_REQUEST['comp_reitoria']."";
		};

		if ($comp_fornecedor_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_VENDOR')."</b> = ".$row['fornecedor_nome']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_fornecedor=".$_REQUEST['comp_fornecedor']."";
		};
		if ($comp_nf_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_FISCAL_NOTES')."</b> = ".$row['nota']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_nf=".$_REQUEST['comp_nf']."";
		}


		if (($comp_ccusto_flag)|| ((isset($_REQUEST['visualiza']) && $_REQUEST['visualiza']=='termo'))) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$CC =  $row['ccusto'];
			if ($CC =="") $CC = -1;
			$query2 = "select * from ".DB_CCUSTO.".".TB_CCUSTO." where ".CCUSTO_ID."= $CC "; //
			$resultado2 = mysql_query($query2);
			$rowCC= mysql_fetch_array($resultado2);
			$centroCusto = $rowCC[CCUSTO_DESC];
			$custoNum = $rowCC[CCUSTO_COD];
			$texto.="[<b>".TRANS('FIELD_CENTER_COST')."</b> = ".$centroCusto."]";

			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_ccusto = ".$_REQUEST['comp_ccusto']."";
		}

		if ($comp_inst_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$sqlA ="select inst_nome as inst from instituicao where inst_cod in (".$comp_inst.")";
			$resultadoA = mysql_query($sqlA);
			//$rowA = mysql_fetch_array($resultadoA);
  			//if (($resultadoA = mysql_query($sqlA)) && (mysql_num_rows($resultadoA) > 0) ) {
				while ($rowA = mysql_fetch_array($resultadoA)) {
					$msgInst.= $rowA['inst'].', ';
				}
				$msgInst = substr($msgInst,0,-2);
			//}

			$texto.="[<b>".TRANS('FIELD_INSTITUTION')."</b> = ".$msgInst."]";
			if (strlen($param) > $tamParam) $param.= "&";

			$p_temp = explode(",",$comp_inst);

			for ($i=0;$i<count($p_temp);$i++){
				$param.="comp_inst%5B%5D=".$p_temp[$i]."&";  //%5B%5D  Caracteres especiais do HTML para entender arrays!!
			}
			$param = substr($param,0,-1);
			//$param.= "comp_inst in ($comp_inst)";
		}

		if ($comp_situac_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if (strlen($param) > $tamParam) $param.= "&";

/*			if ($negar=="NEG_SITUACAO") {
				$texto.="[<b>".$TRANS["cx_situacao"]."</b> <> ".$row['situac_nome']."]";
				$param.= "comp_situac <> ".$_REQUEST['comp_situac']."";
			} else {
				$texto.="[<b>".$TRANS["cx_situacao"]."</b> = ".$row['situac_nome']."]";
				$param.= "comp_situac=".$_REQUEST['comp_situac']."";
			}*/

			$texto.="[<b>".TRANS('COL_SITUAC')."</b> = ".$row['situac_nome']."]";
			$param.= "comp_situac=".$_REQUEST['comp_situac']."";
		};
		if ($comp_data_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			if (isset($_REQUEST['fromDateRegister'])){
				$texto.="[<b>".TRANS("COL_SUBSCRIBE_DATE")."&nbsp;".TRANS('INV_FROM_DATE_REGISTER')."</b> = ".$comp_data."]";
			} else {
				$texto.="[<b>".TRANS("COL_SUBSCRIBE_DATE")."</b> = ".$comp_data."]";
			}
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_data=".$_REQUEST['comp_data']."";
		};
		if ($comp_data_compra_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('FIELD_DATE_PURCHASE')."</b> = ".$comp_data_compra."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_data_compra=".$_REQUEST['comp_data_compra']."";
		};

		if ($garantia_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('TXT_IN_GUARANT')."</b> = ".$consulta."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "garantia=".$_REQUEST['garantia']."";
		};

		if ($soft_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";
			$texto.="[<b>".TRANS('COL_SOFT')."</b> = ".$row['software']." ".$row['versao']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "software=".$_REQUEST['software']."";
		};

		if ($comp_assist_flag) {
			if (strlen($texto) > $tam) $texto.= ", ";

			if ($comp_assist==-2) {$texto.="[<b>".TRANS('FIELD_ASSISTENCE')."</b> = ".TRANS('MSG_NOT_DEFINE')."]";} else
				$texto.="[<b>".TRANS('FIELD_ASSISTENCE')."</b> = ".$row['assistencia']."]";
			if (strlen($param) > $tamParam) $param.= "&";
			$param.= "comp_assist=".$_REQUEST['comp_assist']."";
		};

		if (isset($_REQUEST['VENCIMENTO'])) {
			if (strlen($texto) > $tam) $texto.= ", ";

			$texto.="[<b>".TRANS('WARRANTY_EXPIRE')."</b> = ".$_REQUEST['VENCIMENTO']."]";
			$param.= "VENCIMENTO=".$_REQUEST['VENCIMENTO']."";
		};

		if (strlen($texto)==$tam) {$texto.="[<b>".TRANS('COL_TYPE')."</b> = ".TRANS('FIELD_ALL')."]";}; //Se nenhum campo foi selecionado para a consulta ent�o todos os equipamentos s�o listados!!

 		$lim = (strlen($texto)-7);
		$texto2 = (substr($texto,6,$lim));

		#########################################################
		geraLog(LOG_PATH.'invmon.txt',date("d-m-Y H:i:s"),$_SESSION['s_usuario'],$_SERVER['PHP_SELF'],$texto);
		#########################################################

	if ($linhas == 0)
	{
		//print $query."<br><br><a class='likebutton' onClick=\"javascript:history.back();\">Voltar</a>"; exit;

		print "<script>mensagem('".TRANS('MSG_THIS_CONS_NOT_RESULT')."')</script>";
		//dump($query);
		print "<script>history.back()</script>";
		exit;
	} else
	if ($linhas>1){
		if (isset($_REQUEST['visualiza']) && $_REQUEST['visualiza'] =='impressora') {
			print cabecalho($logo,'<a href=abertura.php>Ocomon</a>',$hoje,$header);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND')." <font color='red'>".$linhas."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
		} else

		if (isset($_REQUEST['visualiza']) && $_REQUEST['visualiza'] =='termo') {
			//print "<BODY bgcolor= 'white'>";
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>".TRANS('TTL_CINFO')."</B></p>";
			print "<p class='centro'><B>".TRANS('TTL_TERM_COMP_HW')."</B></p>";

			print "<p class='parag'>".TRANS('TXT_TERM_COMP_HW')."</p>";
			print "<br>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' >";//bgcolor= 'black'
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_TAG')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_UNIT')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_TYPE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MANUFACTURE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MODEL')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_SN')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_NF')."</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>".TRANS('TTL_CINFO')."</B></p>";
			print "<p class='centro'><B>".TRANS('TTL_FORM_TRANSIT_EQUIP_INFO')."</B></p>";
			print "<p class='parag'>".TRANS('TXT_FORM_TRANSIT_EQUIP_INFO')."</p>";
			print "<br>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' >";//bgcolor= 'black'
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_TAG')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_UNIT')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_TYPE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MANUFACTURE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MODEL')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_SN')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_NF')."</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='config') {
			print cabecalho($logo,'<a href=abertura.php>OcoMon</a>',$hoje,$header);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND')." <font color='red'>".$linhas."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href=consulta_comp.php>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href=consulta_comp.php>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";

		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='relatorio') {
			print cabecalho($logo,'<a href=abertura.php>OcoMon</a>',$hoje,$header);
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND')." <font color='red'>".$linhas."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='100%'  bgcolor='white'>";//
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=etiqueta&visualiza=relatorio".$param."&header=".$header."'>".TRANS('OCO_FIELD_TAG')."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=instituicao,equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".TRANS('FIELD_INSTITUTION')."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".TRANS('COL_TYPE')."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".TRANS('COL_MODEL')."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=serial&visualiza=relatorio".$param."&header=".$header."'>".TRANS('COL_SN')."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=nota&visualiza=relatorio".$param."&header=".$header."'>".TRANS('FIELD_FISCAL_NOTES')."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=situac_nome,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".TRANS('COL_SITUAC')."</a></TD>
				<TD bgcolor='".$color."'><b><a href='mostra_consulta_comp.php?ordena=local,equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio".$param."&header=".$header."'>".TRANS('COL_LOCALIZATION')."</a></TD>
				</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='mantenedora1') {
			print cabecalho($logo,'<a href=abertura.php>OcoMon</a>',$hoje,TRANS('TTL_REPORT_INV_EQUIP_INFO')."<br>".$texto."");
			print "<br><br><TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='100%' bgcolor= white>";
			$color = "#A3A352";
			print "<TR><TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_TAG')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_TYPE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MANUFACTURE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MODEL')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_SN')."</TD>".
				"<TD bgcolor='".$color."'><b".TRANS('COL_NF')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_SITUAC')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_LOCALIZATION')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('FIELD_CENTER_COST')."</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] == 'texto') {
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='abertura.php'>OcoMon</a> - ".TRANS('FIELD_FORMAT_EXPORT').".\t</B></TD></TR><br>";
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND')." <font color=red>".$linhas."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>: </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
		} else {  //Visualiza��o normal na tela do sistema!!
			print "<table border='0' cellspacing='1' width='100%'>";
			print "<tr><TD with='70%' align='left'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td>
					<td width='30%' align='left'>
					<form name='checagem' method='post' action=''>
						<input  type='checkbox' class='radio' name='encadeia' id='idEncadeia' value='ok' ".$checked." onChange=\"checar();\"><a title='".TRANS('HNT_PIPE')."!'>".TRANS('FIELD_CHAIN_NAV')."</a>";
				print "<input  type='checkbox' class='radio' name='ckpopup' value='ok'><a title='".TRANS('MSG_CONS_DETAIL_EQUIP_POPUP')."'>popup</a>";
				print "<input  type='checkbox' class='radio' name='negada' value='ok'><a title='".TRANS('HNT_NAV_EXCLIVE')."!'>".TRANS('NOT')."</a>";
			print "	</form></td></tr><br>";

			print "</table>";


			print "<table border='0' cellspacing='1' summary=''>";


			print "<FORM method='post' action='".$_SERVER['PHP_SELF']."'>";
			print "<TR>";
			$min++;
			$stilo = "style='{height:17px; width:30px; background-color:#DDDCC5; color:#5E515B; font-size:11px;}'"; //Estilo dos bot�es de navega��o
			$stilo2 = "style='{height:17px; width:50px; background-color:#DDDCC5; color:#5E515B;font-size:11px;}'";
			//if ($avanca==$TRANS["bt_todos"]) {$top=$linhasTotal;} else$top=$min+($max-1);
			print "<TD width='750' align='left' ><B>".TRANS('FOUND')." <font color='red'>".$linhasTotal."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>. ".TRANS('TXT_SHOW_OF')." <font color='red'>".$min."</font> ".TRANS('TXT_THE')." <font color='red'>".$top."</font>.</B></TD>";
			//print "<TD width='50' align='left' ></td>";


				print "<TD width='30%' align='right'><input  type='submit' class='button' name='voltaInicio' value='<<' ".
					"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('FIRST_RECORDS')."'> <input  type='submit' class='button'  name='voltaUm' value='<' ".
					"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('PREVIOUSLY_RECORDS')."'> <input  type='submit' class='button'  name='avancaUm' value='>' ".
					"title='".TRANS('VIEW_THE_NEXT')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button'  name='avancaFim' value='>>' ".
					"title='".TRANS('VIEW_THE_LAST')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button'  name='avancaTodos' value='Todas' ".
					"title='".TRANS('VIEW_ALL')." ".$linhasTotal." ".TRANS('RECORDS')."'></td>";

			print "</tr>";
			$min--;



			print "<input type='hidden' value='".$min."' name='min'>";
			print "<input type='hidden' value='".$max."' name='max'>";
			print "<input type='hidden' value='".$maxAux."' name='maxAux'>";
			print "<input type='hidden' value='".$base."' name='top'>";
			print "<input type='hidden' value='".$top."' name='top'>";
			print "<input type='hidden' value='".$ordena."' name='ordena'>";
			print "<input type='hidden' value='".$comp_inv."' name='comp_inv'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_sn'])."' name='comp_sn'>";
			if (isset($comp_sn))
				print "<input type='hidden' value='".$comp_sn."' name='comp_sn'>";
			if (isset($_REQUEST['comp_marca']))
				print "<input type='hidden' value='".$_REQUEST['comp_marca']."' name='comp_marca'>";
			if (isset($_REQUEST['comp_mb']))
				print "<input type='hidden' value='".$_REQUEST['comp_mb']."' name='comp_mb'>";
			if (isset($_REQUEST['comp_proc']))
				print "<input type='hidden' value='".$_REQUEST['comp_proc']."' name='comp_proc'>";
			if (isset($_REQUEST['comp_memo']))
				print "<input type='hidden' value='".$_REQUEST['comp_memo']."' name='comp_memo'>";
			if (isset($_REQUEST['comp_video']))
				print "<input type='hidden' value='".$_REQUEST['comp_video']."' name='comp_video'>";
			if (isset($_REQUEST['comp_som']))
				print "<input type='hidden' value='".$_REQUEST['comp_som']."' name='comp_som'>";
			if (isset($_REQUEST['comp_rede']))
				print "<input type='hidden' value='".$_REQUEST['comp_rede']."' name='comp_rede'>";
			if (isset($_REQUEST['comp_modem']))
				print "<input type='hidden' value='".$_REQUEST['comp_modem']."' name='comp_modem'>";
			if (isset($_REQUEST['comp_modelohd']))
				print "<input type='hidden' value='".$_REQUEST['comp_modelohd']."' name='comp_modelohd'>";

			if (isset($_REQUEST['comp_cdrom']))
				print "<input type='hidden' value='".$_REQUEST['comp_cdrom']."' name='comp_cdrom'>";
			if (isset($_REQUEST['comp_dvd']))
				print "<input type='hidden' value='".$_REQUEST['comp_dvd']."' name='comp_dvd'>";
			if (isset($_REQUEST['comp_grav']))
				print "<input type='hidden' value='".$_REQUEST['comp_grav']."' name='comp_grav'>";
			if (isset($_REQUEST['comp_local']))
				print "<input type='hidden' value='".$_REQUEST['comp_local']."' name='comp_local'>";
			if (isset($_REQUEST['comp_nome']))
				print "<input type='hidden' value='".$_REQUEST['comp_nome']."' name='comp_nome'>";
			if (isset($_REQUEST['comp_fornecedor']))
				print "<input type='hidden' value='".$_REQUEST['comp_fornecedor']."' name='comp_fornecedor'>";
			if (isset($_REQUEST['comp_nf']))
				print "<input type='hidden' value='".$_REQUEST['comp_nf']."' name='comp_nf'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_inst'])."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_inst']))
				print "<input type='hidden' value='".$comp_inst."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_tipo_equip']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_equip']."' name='comp_tipo_equip'>";
			if (isset($_REQUEST['comp_fab']))
				print "<input type='hidden' value='".$_REQUEST['comp_fab']."' name='comp_fab'>";
			if (isset($_REQUEST['comp_tipo_imp']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_imp']."' name='comp_tipo_imp'>";
			if (isset($_REQUEST['comp_polegada']))
				print "<input type='hidden' value='".$_REQUEST['comp_polegada']."' name='comp_polegada'>";
			if (isset($_REQUEST['comp_resolucao']))
				print "<input type='hidden' value='".$_REQUEST['comp_resolucao']."' name='comp_resolucao'>";
			if (isset($_REQUEST['comp_ccusto']))
				print "<input type='hidden' value='".$_REQUEST['comp_ccusto']."' name='comp_ccusto'>";
			if (isset($_REQUEST['comp_situac']))
				print "<input type='hidden' value='".$_REQUEST['comp_situac']."' name='comp_situac'>";

			if (isset($comp_data))
				print "<input type='hidden' value='".$comp_data."' name='comp_data'>";
			if (isset($comp_data_compra))
				print "<input type='hidden' value='".$comp_data_compra."' name='comp_data_compra'>";

			//print "<input type='hidden' value='".isset($_REQUEST['comp_data'])."' name='comp_data'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_data_compra'])."' name='comp_data_compra'>";
			if (isset($_REQUEST['garantia']))
				print "<input type='hidden' value='".$_REQUEST['garantia']."' name='garantia'>";
			if (isset($_REQUEST['negado']))
				print "<input type='hidden' value='".$_REQUEST['negado']."' name='negado'>";


			print "</form>";
			print "</table>";

		}
	}
	 else //APENAS 1 REGISTRO
	{
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='impressora') {
			print cabecalho('<a href=abertura.php>OcoMon</a>','',TRANS('TXT_REPORT_PERSON'));
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND_ONE')."<font color='red'>1</font>".TRANS('TXT_CAD_REG_SYSTEM').":</B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
		} else
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='termo') {
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>".TRANS('TTL_CINFO')."</B></p>";
			print "<p class='centro'><B>".TRANS('TTL_TERM_COMP_HW')."</B></p>";

			print "<p class='parag'>".TRANS('TXT_TERM_COMP_HW')."</p>";
			print "<br>";
			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor= 'black'>";
			$color = "A3A352";
			print "<TR><TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_TAG')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_UNIT')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_TYPE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MANUFACTURE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MODEL')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_SN')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('FIELD_FISCAL_NOTES')."</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {
			print "<p align='center'><img src='".LOGO_PATH."/unilasalle-peb.gif'></p>";
			print "<br>";
			print "<p class='centro'><B>".TRANS('TTL_CINFO')."</B></p>";
			print "<p class='centro'><B>".TRANS('TTL_FORM_TRANSIT_EQUIP_INFO')."</B></p>";
			print "<p class='parag'>".TRANS('TXT_FORM_TRANSIT_EQUIP_INFO')."</p>";

			print "<br>";

			print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor= 'black'>";
			$color = "A3A352";
			print "<TR><TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_TAG')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('OCO_FIELD_UNIT')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_TYPE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MANUFACTURE')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_MODEL')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('COL_SN')."</TD>".
				"<TD bgcolor='".$color."'><b>".TRANS('FIELD_FISCAL_NOTES')."</TD>".
				"</tr>";
		} else

		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='texto') {
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='abertura.php'>OcoMon</a> - <u>".TRANS('FIELD_FORMAT_EXPORT').".</u>\t</B></TD></TR><br>";
			print "<tr><TD bgcolor='".TD_COLOR."'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto.".</i></td></tr><br><br>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B>".TRANS('FOUND_ONE')." <font color='red'>1</font> ".TRANS('TXT_CAD_REG_SYSTEM').": </B></TD></TR>";
			print "<TR><TD bgcolor='".TD_COLOR."'><B><a href='consulta_comp.php'>[ ".TRANS('LINK_NEW_REPORT')." ]</a>.</B></TD></TR>";
		} else { //Visualiza��o normal na tela do sistema!!
			print "<table border='0' cellspacing='1' width='100%'>";
			print "<tr><TD with='70%' align='left'><i>".TRANS('FIELD_CRITE_EXIBIT').": ".$texto."</i></td><td width='30%' align='left'><form name='checagem' method='post' action=''><input type='checkbox' name='encadeia' value='ok' disabled><a title='".TRANS('HNT_PIPE')."'>".TRANS('FIELD_CHAIN_NAV')."</a>";
			print "<input  type='checkbox' class='radio' name='ckpopup' value='ok' disabled><a title='".TRANS('MSG_CONS_DETAIL_EQUIP_POPUP')."'>popup</a>";
			print "</form></td></tr><br>";
			print "<TR><td class='line'><B>".TRANS('FOUND_ONE')." <font color='red'>1</font> ".TRANS('TXT_CAD_REG_SYSTEM').":</B></TD><td class='line'></td></TR>";
			print "</table>";
		}
	}
		print "</TD>";

		// Se a consulta foi solicitada para a impressora ele monta outra sa�da tipo relat�rio
		if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='impressora') {
			print "<hr width='80%' align='center'>";
			$i=0;
        		$j=2;
			while ($row = mysql_fetch_array($resultado)) {
				if ($j % 2)
				{
					$color =  'white';//BODY_COLOR;
				}
				else
				{
					$color = 'white';
				}
				$j++;

				//print "<title>InvMon - Relat�rio</title>";
				print "<TABLE WIDTH='80%' BORDER='0' CELLPADDING='4' CELLSPACING='0' align='center'>";
				print "<link rel=stylesheet type=text/css href='../includes/css/estilos.css.php'>";
				print "	<COL WIDTH='10%'>";
				print "<COL WIDTH='20%'>";
				print "	<COL WIDTH='10%'>";
				print "	<COL WIDTH='20%'>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".strtoupper(TRANS('COL_TYPE')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".$row['equipamento']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".strtoupper(TRANS('COL_MANUFACTURE')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT'>".$row['fab_nome']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('OCO_FIELD_TAG')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><a href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."'>".$row['etiqueta']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_SN')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['serial']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_MODEL')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['modelo']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_NF')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['nota']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_SITUAC')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['situac_nome']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_LOCALIZATION')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='10%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['local']."</P>";
				print "			</TH>";
				print "		</TR>";
				print "		<TR VALIGN='TOP'>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('OCO_FIELD_UNIT')).":</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['instituicao']."</P>";
				print "			</TH>";
				print "			<TD WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
				print "				</P>";
				print "			</TD>";
				print "			<TH WIDTH='20%'>";
				print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
				print "				</P>";
				print "			</TH>";
				print "		</TR>";
				print "</TABLE>		";
				print "		<hr width='80%' align='center'>";
                print" <hr width='80%' align='center'>";
                $i++;
		}

		print "<b><a href='abertura.php'>OcoMon</a> - ".TRANS('MENU_TTL_MOD_INV').". ".TRANS('OCO_DATE').": ".$hoje.".</b>";
        	print "</TABLE>";


	} else if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='termo') {


		print "<title>".TRANS('TXT_OCOMON_TERM_COMP_HW')."</title>";
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css.php'>";

		while ($row = mysql_fetch_array($resultado)) {
			$color =  'white';//BODY_COLOR;
			print "<TR>";
			print "<TD bgcolor='".$color."'>".$row['etiqueta']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['instituicao']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['equipamento']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['fab_nome']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['modelo']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['serial']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['nota']."</TD>";
			print "</tr>";
			$setor = $row['local'];
		}

		//Linha que mostra o total de registros mostrados
		$cor2='#A8A8A8';

	print "</TABLE><br><br>";
		//print "</fieldset>";
		print "<div id='container'>";
		print "<p class='parag_header'><b>".TRANS('TXT_INFO_COMPLEM').":</b></P>";
		print "<p class='parag'>";
		print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor='black'";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_CENTER_COST').":</td><td bgcolor='white'>".$custoNum." - ".$centroCusto."</td></tr>";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_SECTOR').":</td><td bgcolor='white'>".strtoupper($setor)."</td></tr>";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_USER_RESP').":</td><td bgcolor='white'><input type='text' class='text3' name='responsavel'></td></tr>";
		print "</table>";
		print "</P>";

		print "<p class='parag_header'><b>".TRANS('TXT_IMPORTANT').":</b></P>";
		print "<p class='parag'>".TRANS('TXT_TERM_COMP_1')."</p>";
		print "<p class='parag'>".TRANS('TXT_TERM_COMP_2')."</p>";
		print "<p class='parag'>".TRANS('TXT_TERM_COMP_3')."</p>";

		print "<br>";
		print "<p class='parag'>".TRANS('TXT_SIGNATURE').":__________________________________</P>";
		print "<p class='parag'>".TRANS('TXT_CITY').", ".$hoje_termo.".</p>";
		print "<br><br><br><br><br>";
		print "<div id='footer'><B><a href='abertura.php'>OcoMon</a> -".TRANS('TXT_DIFINE_OCOMON')."</B></div>";
		print "</div>";

	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='transito') {

		print "<title>".TRANS('TXT_OCOMON_TERM_COMP_HW')."</title>";
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css.php'>";

		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			$color = 'white';//BODY_COLOR;

			print "<TR>";
			print "<TD bgcolor='".$color."'>".$row['etiqueta']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['instituicao']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['equipamento']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['fab_nome']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['modelo']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['serial']."</TD>";
			print "</tr>";
		}
			$cor2="#A8A8A8";

        print "</TABLE>";
		//print "</fieldset>";
		print "<div id='container'>";
		print "<p class='parag_header'><b>".TRANS('TXT_INFO_COMPLEM').":</b></P>";
		print "<p class='parag'>";
		print "<TABLE border='0' cellpadding='4' cellspacing='1' align='center' width='80%' bgcolor='black'";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_CARRIER').":</td><td bgcolor='white'><input type='text' class='text3' name='portador'></td></tr>";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_DESTINY').":</td><td bgcolor='white'><input type='text' class='text3' name='destino'></td></tr>";
		print "<tr><td bgcolor='white'>".TRANS('OCO_FIELD_DATE_EXIT').":</td><td bgcolor='white'>".$hoje_termo."</td></tr>";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_REASON').":</td><td bgcolor='white'><input type='text' class='text3' name='motivo'></td></tr>";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_AUTH_FOR').":</td><td bgcolor='white'><input type='text' class='text3' name='responsavel'></td></tr>";
		print "<tr><td bgcolor='white'>".TRANS('FIELD_SECTOR_RESP').":</td><td bgcolor='white'><input type='text' class='text3' name='setor_reponsavel'></td></tr>";

		print "</table>";
		print "</P>";

		print "<p class='parag_header'><b>".TRANS('TXT_IMPORTANT').":</b></P>";
		print "<p class='parag'>".TRANS('TXT_FORM_TRANSIT_1')."</p>";

		print "<br>";
		print "<p class='parag'>".TRANS('TXT_SIGNATURE').":__________________________________</P>";
		print "<p class='parag'>".TRANS('TXT_CITY').", ".$hoje_termo.".</p>";
		print "<br><br><br><br><br>";
		print "<div id='footer'><B><a href=abertura.php>OcoMon</a> - ".TRANS('TXT_DIFINE_OCOMON')."</B></div>";
		print "</div>";
	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='config') {
		print "<hr width=80% align=center>";
		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				$color =  'white';//BODY_COLOR;
			}
			else
			{
				$color = 'white';
			}
			$j++;

			print "<TABLE WIDTH='80%' BORDER='0' CELLPADDING='4' CELLSPACING='0' align='center'>";
			print "<link rel='stylesheet' type='text/css' href='./css/estilos.css.php'>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".strtoupper(TRANS('COL_TYPE')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".$row['equipamento']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".strtoupper(TRANS('COL_MANUFACTURE')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT'>".$row['fab_nome']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(".TRANS('OCO_FIELD_TAG').")."</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><a href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."'>".$row['etiqueta']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_SN')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($row['serial'])."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_MODEL')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['modelo']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_NF')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['nota']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_SITUAC')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['situac_nome']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_LOCALIZATION')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['local']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('OCO_FIELD_UNIT')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['instituicao']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
			print "				</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><BR>";
			print "				</P>";
			print "			</TH>";
			print "		</TR>";

			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_PROC')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_proc']." ".$row['processador']." ".$row['clock']." ".$row['proc_sufixo']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_MB')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_mb']." ".$row['mb']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_VIDEO')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_video']." ".$row['video']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_MEMO')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['memoria']."".$row['memo_sufixo']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_REDE')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['rede_fabricante']." ".$row['rede']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_SOM')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_som']." ".$row['som']."</P>";
			print "			</TH>";
			print "		</TR>";

			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_HD')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_hd']." ".$row['hd_capacidade']."".$row['hd_sufixo']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('MNL_CDROM')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_cdrom']." ".$row['cdrom']."</P>";
			print "			</TH>";
			print "		</TR>";
			print "		<TR VALIGN='TOP'>";
			print "			<TD WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('FIELD_MODEM')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='20%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$row['fabricante_modem']." ".$row['modem']."</P>";
			print "			</TH>";
			print "			<TD WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('FIELD_RECORD_CD')).":</P>";
			print "			</TD>";
			print "			<TH WIDTH='10%'>";
			print "				<P ALIGN='LEFT' STYLE='{font-weight: medium}'><FONT FACE='Arial, sans-serif'>".$row['fabricante_gravador']." ".$row['gravador']."</P>";
			print "			</TH>";
			print "		</TR>";

			$qryPieces = "";
			$qryPieces = $QRY["componenteXequip_ini"];// ../includes/queries/
			$qryPieces.=" and eqp.eqp_equip_inv=".$row['etiqueta']." and eqp.eqp_equip_inst=".$row['cod_inst']."";
			$qryPieces.= $QRY["componenteXequip_fim"];

			$execQryPieces = mysql_query($qryPieces) or die (TRANS('ERR_QUERY')."<br>".$qryPieces);

			print "<TR><TD colspan='4'></TD></TR>";
			print "<tr><TD colspan='4'><b>".TRANS('SUBTTL_DATA_COMPLE_PIECES').":</b></TD></tr>";
			print "<TR><TD colspan=4></TD></TR>";


			while ($rowPiece = mysql_fetch_array($execQryPieces)){


				print "<TR>";
				print "<td><P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper($rowPiece['item_nome']).":</P></td>";
				print "<td><P ALIGN='LEFT' STYLE='{font-weight: medium}'>".$rowPiece['fabricante']." ".$rowPiece['modelo']." ".$rowPiece['capacidade']." ".$rowPiece['sufixo'].":</P></td>";

				//print "<TD align='left' bgcolor='".TD_COLOR."'><b>".TRANS('COL_SN').":</b></TD>";
				print "<td><P ALIGN='LEFT' STYLE='{font-weight: medium}'>".strtoupper(TRANS('COL_SN')).":</P></td>";
				print "<TD align='left' ><a onClick=\"popup('estoque.php?action=details&cod=".$rowPiece['estoq_cod']."&cellStyle=true')\">".$rowPiece['estoq_sn']."</a></TD>";

				print "</tr>";

					//"<a href='mostra_consulta_comp.php?comp_dvd=".$row['cod_dvd']."' title='".TRANS('HNT_LIST_EQUIP_DVD')." ".$row['fabricante_dvd']." ".$row['dvd']." ".TRANS('HNT_CAD_IN_SYSTEM')."'>";

			}



			print "</TABLE>";
			print "<hr width='80%' align='center'>";
			print "<hr width='80%' align='center'>";
			$i++;
		}

		print "<b><a href='abertura.php'>".TRANS('MENU_TTL_MOD_INV')."</a>. ".TRANS('OCO_DATE').": ".$hoje.".</b>";
		print "</TABLE>";

	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='relatorio') {

		$color = "#A3A352";
                print "<link rel='stylesheet' type='text/css' href='./css/estilos.css.php'>";
		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				if (($row['situac_destaque']=='1')) { //Situa��o de destaque
					$color='#FF0000';
					$alerta = "style='{color:white;}'";
				} else {
					$color =  "#C8C8C8";
					$alerta = "";
				}
			}
			else
			{
			// $color = EAEAEA;
				if (($row['situac_destaque']=='1'))
				{
					$color='#FF0000';
					$alerta = "style='{color:white;}'";
				}
				else
				{
					$color =  "#EAEAEA";
					$alerta = "";
				}
			}
			$j++;

			print "<TR>";
			print "<TD bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."' title='".TRANS('HNT_DATEIL_CAD_EQUIP')."'>".$row['etiqueta']."</a></TD>";

			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_inst=".$row['instituicao']."&ordena=instituicao,fab_nome,modelo,local,etiqueta&visualiza=relatorio'>".$row['instituicao']."</a></td>";

			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=relatorio' title='".TRANS('HNT_LIST_EQUIP_TYPE')." ".$row['equipamento']."'>".$row['equipamento']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_marca=".$row['modelo_cod']."&ordena=local,etiqueta&visualiza=relatorio' title='".TRANS('HNT_LIST_EQUIP_MODEL')." ".$row['fab_nome']." ".$row['modelo'].".'>".$row['fab_nome']." ".$row['modelo']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_sn=".$row['serial']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio' title='".TRANS('HNT_LIST_EQUIP_SN')." ".$row['serial'].".'>".strtoupper($row['serial'])."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_nf=".$row['nota']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=relatorio' title='".TRANS('HNT_LIST_EQUIP_NF')." ".$row['nota'].".'>".$row['nota']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_situac=".$row['situac_cod']."&ordena=local,etiqueta&visualiza=relatorio' title='".TRANS('HNT_LIST_EQUIP_SITUAC')." ".$row['situac_nome'].".'>".$row['situac_nome']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_local=".$row['tipo_local']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=relatorio' title='".TRANS('HNT_LIST_EQUIP_LOCAL')." ".$row['local'].".'>".$row['local']."</a></td>";
			print "</tr>";
			$i++;
		}


		//Linha que mostra o total de registros mostrados
		$cor2='#A8A8A8';
		print "<TR><TD colspan='6' bgcolor='".$cor2."'><b></TD>".
				"<TD bgcolor='".$cor2."'><b>".TRANS('TOTAL')."</TD>".
				"<TD bgcolor='".$cor2."'><b><font color='red'>".$linhas."</font></TD>".
			"</tr>";

		print "</TABLE><br>";

		print "<table width='90%'>".
				"<tr><td class='line'><b><a href='abertura.php'>".TRANS('MENU_TTL_MOD_INV')."</a>. ".TRANS('OCO_DATE').": ".$hoje."</b></td>".
				"</tr>".
			"</table>";


	} else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='mantenedora1') {
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css.php'>";
		$i=0;
		$j=2;
		$cor2="#A8A8A8";
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				$color = '#C8C8C8';//BODY_COLOR;
				$alerta = "style='{color:white;}'";
			}
			else
			{
				$color = '#EAEAEA';
				$alerta = "";
			}
			$j++;

			if (!(empty($row['ccusto'])))
			{
				$CC =  $row['ccusto'];
				$query2 = "select * from ".DB_CCUSTO.".".TB_CCUSTO." where ".CCUSTO_ID."= ".$CC."";
				$resultado2 = mysql_query($query2);

				$row2 = mysql_fetch_array($resultado2);
				$centroCusto = $row2[CCUSTO_COD];
				$custoDesc = $row2[CCUSTO_DESC];
			} else
				$centroCusto = '';

			print "<TR>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."' title='".TRANS('HNT_DATEIL_CAD_EQUIP')."'>".$row['etiqueta']."</a></TD>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=mantenedora1' title='".TRANS('HNT_LIST_EQUIP_TYPE')." ".$row['equipamento']."'>".$row['equipamento']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_fab=".$row['fab_cod']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=mantenedora1' title='".TRANS('HNT_LIST_EQUIP_MANUF')." ".$row['fab_nome'].".'>".$row['fab_nome']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_marca=".$row['modelo_cod']."&ordena=local,etiqueta&visualiza=mantenedora1' title='".TRANS('HNT_LIST_EQUIP_MODEL')." ".$row['fab_nome']." ".$row['modelo'].".'>".$row['fab_nome']." ".$row['modelo']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_sn=".$row['serial']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=mantenedora1' title='HNT_LIST_EQUIP_SN ".$row['serial'].".'>".strtoupper($row['serial'])."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_nf=".$row['nota']."&ordena=fab_nome,modelo,local,etiqueta&visualiza=mantenedora1' title='".TRANS('HNT_LIST_EQUIP_NF')."".$row['nota'].".'>".$row['nota']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_situac=".$row['situac_cod']."&ordena=local,etiqueta&visualiza=mantenedora1' title='".TRANS('HNT_LIST_EQUIP_SITUAC')." ".$row['situac_nome'].".'>".$row['situac_nome']."</a></td>";
			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_local=".$row['tipo_local']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=mantenedora1' title='".TRANS('HNT_LIST_EQUIP_LOCAL')." ".$row['local'].".'>".$row['local']."</a></td>";

			print "<td bgcolor='".$color."'><a ".$alerta." href='mostra_consulta_comp.php?comp_ccusto=".$row['ccusto']."&ordena=equipamento,fab_nome,modelo,etiqueta&visualiza=mantenedora1' title='".TRANS('HNT_LIST_EQUIP_CENTRA_COST')." ".$custoDesc.".'>".$centroCusto."</a></td>";
			print "</tr>";
			$i++;
		}
		//Linha que mostra o total de registros mostrados
		print "<TR><TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b></TD>".
			"<TD bgcolor='".$cor2."'><b>TOTAL</TD>".
			"<TD bgcolor='".$cor2."'><b><font color='red'>".$linhas."</font></TD>".
			"</tr>";

		print "</TABLE><br>";

		print "<table width='90%'><tr><td class='line'><b><a href='abertura.php'>OcoMon</a> - ".TRANS('TXT_DIFINE_OCOMON')." ".TRANS('OCO_DATE').": ".$hoje.".</b></td></tr></table>";

	}  else

	if (isset($_REQUEST['visualiza'])  && $_REQUEST['visualiza'] =='texto') {  //Texto separado por tabula��o//
		print "<link rel='stylesheet' type='text/css' href='./css/estilos.css.php'>";
		print "<br><i>(".TRANS('TXT_SEL_TEXT_CVS').").</i><br><br><br>";
		echo" <hr width='100%' align='center'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='white'>";
		print "<b>'".TRANS('OCO_FIELD_TAG')."','".TRANS('OCO_FIELD_UNIT')."','".TRANS('COL_TYPE')."', '".TRANS('COL_SITUAC')."' , '".TRANS('COL_MANUFACTURE')."','".TRANS('COL_MODEL')."','".TRANS('COL_SN')."','".TRANS('COL_VENDOR')."','".TRANS('COL_NF')."','".TRANS('FIELD_CENTER_COST')."','".TRANS('COL_LOCALIZATION')."','MNL_PROC','".TRANS('MNL_MEMO')."','".TRANS('MNL_HD')."','".TRANS('FIELD_TYPE_PRINTER')."','".TRANS('FIELD_MONITOR')."','".TRANS('FIELD_SCANNER')."'</b><br>";

		$i=0;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			if ($j % 2)
			{
				$color =  'white';
			}
			else
			{
				$color = 'white';
			}
			$j++;
			if (!(empty($row['ccusto'])))
			{
				$CC =  $row['ccusto'];
				$query2 = "select * from ".DB_CCUSTO.".".TB_CCUSTO." where ".CCUSTO_ID."= ".$CC."";
				$resultado2 = mysql_query($query2);
				$row3 = mysql_fetch_array($resultado2);
				$resultado3 = $row3[CCUSTO_DESC];
				$centroCusto = $row3[CCUSTO_COD];
			}
			print "".$row['etiqueta'].",".$row['instituicao'].",".$row['equipamento'].", ".$row['situac_nome']." , ".$row['fab_nome'].",".$row['modelo'].",".$row['serial'].",".$row['fornecedor_nome'].",".$row['nota'].",".$centroCusto.",".$row['local'].",".$row['processador']." ".$row['clock']." ".$row['proc_sufixo'].",".$row['memoria']."".$row['memo_sufixo'].",".$row['fabricante_hd']." ".$row['hd_capacidade']."".$row['hd_sufixo'].",".$row['impressora'].",".$row['polegada_nome'].",".$row['resol_nome']."<br>";
			$centroCusto ="";
			print "</TR>";
			$i++;
		}
		print "<hr width='100%' align='center'>";
		print "</TABLE>";
	}
	else ####### Mostra Consulta normal na tela principal do sistema!!
	{
		print "<fieldset><legend>".TRANS('MNL_VIS_EQUIP')."</legend>";
		print "<TABLE border='0' cellpadding='3' cellspacing='0' align='center' width='100%'>";
		print "<TR class='header'>".
				"<TD class='line' valign='middle'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=etiqueta&coluna=etiqueta&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_TAG').".'>".TRANS('OCO_FIELD_TAG')."</a>".$ICON_ORDER['etiqueta']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=instituicao,etiqueta&coluna=instituicao&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_UNIT')."'.>".TRANS('OCO_FIELD_UNIT')."</a>".$ICON_ORDER['instituicao']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=equipamento,modelo&coluna=tipo&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_TYPE_EQUIP')."'>".TRANS('COL_TYPE')."</a>".$ICON_ORDER['tipo']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=fab_nome,modelo&coluna=modelo&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_MODEL_EQUIP')."'>".TRANS('COL_MODEL')."</a>".$ICON_ORDER['modelo']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=local&coluna=local&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_LOCAL')."'>".TRANS('COL_LOCALIZATION')."</a>".$ICON_ORDER['local']."</TD>".
				"<td class='line'><b><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?ordena=situac_nome&coluna=situacao&ordenado=".$ordenado."&".$param."')\" title='".TRANS('HNT_ORDER_BY_SITUAC')."'>".TRANS('COL_SITUAC')."</a>".$ICON_ORDER['situacao']."</TD>";
		if ($_SESSION['s_invmon']==1)
			print "<td class='line'><b>".TRANS('COL_EDIT')."</TD>";
		if ($administrador){
			print "<td class='line'><b>".TRANS('COL_DEL')."</TD>";
		}
		$i=0;
		$j=2;
		$cont=0;
  		while ($row = mysql_fetch_array($resultado)) {
			$cont++;
			if ($j % 2)
			{
				if (($row['situac_destaque']=='1')) {//Situa��o com destaque
					$color="#FF0000";
					$alerta = "style='{color:white;}'";
					$trClass = "lin_alerta_par";
					$corDestaque = '#FF0000';
				} else {
					$color =  BODY_COLOR;
					$alerta = "";
					$trClass = "lin_par";
					$corDestaque = $_SESSION['s_colorLinPar'];
				}
			}
			else
			{
				if (($row['situac_destaque']=='1')) {
					$color='#FF0000';
					$alerta = "style='{color:white;}'";
					$trClass = "lin_alerta_impar";
					$corDestaque = '#FF0000';
				} else {
					$color = 'white';
					$alerta = "";
					$trClass = "lin_impar";
					$corDestaque = $_SESSION['s_colorLinImpar'];
				}
			}
                	$j++;
			//print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
			print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";


			//print "<td class='line'><a ".$alerta." onClick=\"montaPopup('mostra_consulta_inv.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\" title='".TRANS('HNT_SHOW_DATEIL_EQUIP_CAD')."'>".$row['etiqueta']."</a></TD>";
			print "<td class='line'><a ".$alerta." onClick=\"exibeEscondeImg('idTr".$j."'); exibeEscondeImg('idDivLinha".$j."'); ajaxFunction('idDivLinha".$j."', 'mostra_consulta_inv.php', 'idLoad', 'comp_inv=idEtiqueta".$j."', 'comp_inst=idUnidade".$j."' , 'INDIV=idINDIV');\" title='".TRANS('HNT_SHOW_DATEIL_EQUIP_CAD')."'>".$row['etiqueta']."</a></TD>";

			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_UNIT')." ".$row['instituicao'].".' href=\"javascript:monta_link('?comp_inst%5B%5D=".$row['cod_inst']."&ordena=fab_nome,modelo,local,etiqueta&coluna=instituicao&ordenado=".$ordenado."','".$param."','comp_inst')\">".$row['instituicao']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_TYPE')." ".$row['equipamento'].".' href=\"javascript:monta_link('?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta&coluna=tipo&ordenado=".$ordenado."','".$param."','comp_tipo_equip')\">".$row['equipamento']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_MODEL')." ".$row['fab_nome']." ".$row['modelo'].".' href=\"javascript:monta_link('?comp_marca=".$row['modelo_cod']."&ordena=local,etiqueta&coluna=modelo&ordenado=".$ordenado."','".$param."','comp_marca')\">".$row['fab_nome']." ".$row['modelo']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_LOCAL_SECTOR')." ".$row['local'].".' href=\"javascript:monta_link('?comp_local=".$row['tipo_local']."&ordena=equipamento,fab_nome,modelo,etiqueta&coluna=local&ordenado=".$ordenado."','".$param."','comp_local')\">".$row['local']."</a></td>";
			print "<td class='line'><a ".$alerta." title='".TRANS('HNT_FILTER_EQUIP_SITUAC')." ".$row['situac_nome'].".' href=\"javascript:monta_link('?comp_situac=".$row['situac_cod']."&ordena=fab_nome,modelo,local,etiqueta&coluna=modelo&ordenado=etiqueta','".$param."','NEG_SITUACAO')\">".$row['situac_nome']."</a></td>";
			if ($_SESSION['s_invmon']==1)
				print "<td class='line'><a ".$alerta." onClick =\"return redirect('altera_dados_computador.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row["cod_inst"]."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
			if ($administrador){
				print "<td class='line'><a ".$alerta." onClick =\"return confirma('".TRANS('MSG_DEL_REG')."','exclui_equipamento.php?comp_inv=".$row['etiqueta']."&comp_inst=".$row['cod_inst']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
			}
			print "</TR>";

			print "<tr id='idTr".$j."' style='{display:none;}'><td colspan='8'><div id='idDivLinha".$j."' style='{display:none;}'></div></td></tr>";
			print "<input type='hidden' name='etiquetaAjax".$j."' id='idEtiqueta".$j."' value='".$row['etiqueta']."'>";
			print "<input type='hidden' name='unidadeAjax".$j."' id='idUnidade".$j."' value='".$row['cod_inst']."'>";
			print "<input type='hidden' name='INDIV' id='idINDIV' value='INDIV'>";

			$i++;
		}
		print "</TABLE>";

		if ($linhas>5) { //Colocar rodap� se a quantidade de registros for maior do que 20 registros.

			print "</fieldset>";
			print "<table border='0' cellpadding='3' cellspacing='0' summary=''>";
			print "<FORM method='post' action='".$_SERVER['PHP_SELF']."'>";

			print "<TR>";
			$min++;
			if (isset($avancaTodos)) {$top=$linhasTotal;} else $top=$min+($max-1);
			print "<TD width='750' align='left' ><B>".TRANS('FOUND')." <font color='red'>".$linhasTotal."</font> ".TRANS('TXT_REG_ORDER_BY')." <u>".$traduzOrdena."</u>. ".TRANS('TXT_SHOW_OF')." <font color='red'>".$min."</font> ".TRANS('TXT_THE')." <font color='red'>".$top."</font>.</B></TD>";
			print "<TD width='50' align='left' ></td>";

			print "<TD width='30%' align='right'><input  type='submit' class='button' name='voltaInicio' value='<<' ".
				"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('FIRST_RECORDS')."'> <input  type='submit' class='button'  name='voltaUm' value='<' ".
				"title='".TRANS('VIEW_THE')." ".$max." ".TRANS('PREVIOUSLY_RECORDS')."'> <input  type='submit' class='button'  name='avancaUm' value='>' ".
				"title='".TRANS('VIEW_THE_NEXT')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button'  name='avancaFim' value='>>' ".
				"title='".TRANS('VIEW_THE_LAST')." ".$max." ".TRANS('RECORDS')."'> <input  type='submit' class='button'  name='avancaTodos' value='Todas' ".
				"title='".TRANS('VIEW_ALL')." ".$linhasTotal." ".TRANS('RECORDS')."'></td>";


			print "</tr>";
			$min--;

			print "<input type='hidden' value='".$min."' name='min'>";
			print "<input type='hidden' value='".$max."' name='max'>";
			print "<input type='hidden' value='".$maxAux."' name='maxAux'>";
			print "<input type='hidden' value='".$base."' name='top'>";
			print "<input type='hidden' value='".$top."' name='top'>";
			print "<input type='hidden' value='".$ordena."' name='ordena'>";
			print "<input type='hidden' value='".$comp_inv."' name='comp_inv'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_sn'])."' name='comp_sn'>";
			if (isset($comp_sn))
				print "<input type='hidden' value='".$comp_sn."' name='comp_sn'>";
			if (isset($_REQUEST['comp_marca']))
				print "<input type='hidden' value='".$_REQUEST['comp_marca']."' name='comp_marca'>";
			if (isset($_REQUEST['comp_mb']))
				print "<input type='hidden' value='".$_REQUEST['comp_mb']."' name='comp_mb'>";
			if (isset($_REQUEST['comp_proc']))
				print "<input type='hidden' value='".$_REQUEST['comp_proc']."' name='comp_proc'>";
			if (isset($_REQUEST['comp_memo']))
				print "<input type='hidden' value='".$_REQUEST['comp_memo']."' name='comp_memo'>";
			if (isset($_REQUEST['comp_video']))
				print "<input type='hidden' value='".$_REQUEST['comp_video']."' name='comp_video'>";
			if (isset($_REQUEST['comp_som']))
				print "<input type='hidden' value='".$_REQUEST['comp_som']."' name='comp_som'>";
			if (isset($_REQUEST['comp_rede']))
				print "<input type='hidden' value='".$_REQUEST['comp_rede']."' name='comp_rede'>";
			if (isset($_REQUEST['comp_modem']))
				print "<input type='hidden' value='".$_REQUEST['comp_modem']."' name='comp_modem'>";
			if (isset($_REQUEST['comp_modelohd']))
				print "<input type='hidden' value='".$_REQUEST['comp_modelohd']."' name='comp_modelohd'>";

			if (isset($_REQUEST['comp_cdrom']))
				print "<input type='hidden' value='".$_REQUEST['comp_cdrom']."' name='comp_cdrom'>";
			if (isset($_REQUEST['comp_dvd']))
				print "<input type='hidden' value='".$_REQUEST['comp_dvd']."' name='comp_dvd'>";
			if (isset($_REQUEST['comp_grav']))
				print "<input type='hidden' value='".$_REQUEST['comp_grav']."' name='comp_grav'>";
			if (isset($_REQUEST['comp_local']))
				print "<input type='hidden' value='".$_REQUEST['comp_local']."' name='comp_local'>";
			if (isset($_REQUEST['comp_nome']))
				print "<input type='hidden' value='".$_REQUEST['comp_nome']."' name='comp_nome'>";
			if (isset($_REQUEST['comp_fornecedor']))
				print "<input type='hidden' value='".$_REQUEST['comp_fornecedor']."' name='comp_fornecedor'>";
			if (isset($_REQUEST['comp_nf']))
				print "<input type='hidden' value='".$_REQUEST['comp_nf']."' name='comp_nf'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_inst'])."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_inst']))
				print "<input type='hidden' value='".$comp_inst."' name='comp_inst[]'>";
			if (isset($_REQUEST['comp_tipo_equip']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_equip']."' name='comp_tipo_equip'>";
			if (isset($_REQUEST['comp_fab']))
				print "<input type='hidden' value='".$_REQUEST['comp_fab']."' name='comp_fab'>";
			if (isset($_REQUEST['comp_tipo_imp']))
				print "<input type='hidden' value='".$_REQUEST['comp_tipo_imp']."' name='comp_tipo_imp'>";
			if (isset($_REQUEST['comp_polegada']))
				print "<input type='hidden' value='".$_REQUEST['comp_polegada']."' name='comp_polegada'>";
			if (isset($_REQUEST['comp_resolucao']))
				print "<input type='hidden' value='".$_REQUEST['comp_resolucao']."' name='comp_resolucao'>";
			if (isset($_REQUEST['comp_ccusto']))
				print "<input type='hidden' value='".$_REQUEST['comp_ccusto']."' name='comp_ccusto'>";
			if (isset($_REQUEST['comp_situac']))
				print "<input type='hidden' value='".$_REQUEST['comp_situac']."' name='comp_situac'>";

			//if (isset($_REQUEST['comp_data']))
			if (isset($comp_data))
				print "<input type='hidden' value='".$comp_data."' name='comp_data'>";
			//if (isset($_REQUEST['comp_data_compra']))
			if (isset($comp_data_compra))
				print "<input type='hidden' value='".$comp_data_compra."' name='comp_data_compra'>";

			//print "<input type='hidden' value='".isset($_REQUEST['comp_data'])."' name='comp_data'>";
			//print "<input type='hidden' value='".isset($_REQUEST['comp_data_compra'])."' name='comp_data_compra'>";
			if (isset($_REQUEST['garantia']))
				print "<input type='hidden' value='".$_REQUEST['garantia']."' name='garantia'>";
			if (isset($_REQUEST['negado']))
				print "<input type='hidden' value='".$_REQUEST['negado']."' name='negado'>";


			print "</form>";
			print "</table>";

		} else {
			print "<TABLE border='0' cellpadding='1' cellspacing='0' align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			print "<TR><TD bgcolor='".TD_COLOR."'><font color='".TD_COLOR."'>&nbsp</font></TD></TR>";
			print "</table>";
			print "</fieldset>";
		}
	}

	?>
	<SCRIPT LANGUAGE="JAVASCRIPT">
	<!--

		desabilitaLinks(<?php print $_SESSION['s_invmon'];?>);


		function desabilita(v){
			if (document.checagem.negada !=null)
				document.checagem.negada.disabled = v;
		}

		function checar() {
			var checado = false;
			if (document.checagem.encadeia.checked){
				checado = true;
				desabilita(false);
			} else {
				checado = false;
				desabilita(true);
			}
			return checado;
		}

		function ckPopup() {
			var popup = false;
			if (document.checagem.ckpopup.checked){
				popup = true;
			} else {
				popup = false;
			}
			return popup;
		}


		function montaPopup(pagina)	{ //Exibe uma janela popUP

			if (ckPopup()==false){
				window.location.href=pagina;
			} else {
				x = window.open(pagina,'_blank','dependent=yes,width=650,height=470,scrollbars=yes,statusbar=no,resizable=yes');
				x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			}
			return false
		}

		function negar() {
			var negado = false;
			if (document.checagem.negada.checked){
				negado = true;
			} else {
				negado = false;
			}
			return negado;
		}

		function monta_link(clicado,parametro,negaCampo){

			var encadeado = "encadeado=1";
			if (checar()==false){
				parametro = "";
				encadeado = "";
				negaCampo ="";
			}

			//FIM DO BLOCO ALTERADO
			window.location.href=clicado+"&"+parametro+"&"+encadeado;
		}

		//-->
		</SCRIPT>
		<?php 
			//else
			//	if (negar()==false){
			//		negaCampo = "";
			//	} else {
			//		negaCampo = "negar="+negaCampo;
			//	}
				//window.location.href=clicado+"&"+negaCampo+"&"+parametro;

print "</body>";
print "</html>";
?>


