<?session_start();/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 *
 * For further information visit:
 * 		http://www.fckeditor.net/
 *
 * File Name: fck_editor.css
 * 	Styles used by the editor IFRAME and Toolbar.
 *
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

/*
	### Basic Editor IFRAME Styles.
*/

	require_once ('../../../../../includes/config.inc.php');

	if (is_file("../../../../../includes/classes/conecta.class.php"))
		require_once ("../../../../../includes/classes/conecta.class.php"); else
		require_once ("../classes/conecta.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	//$qry = "SELECT * FROM styles";
	//$exec = mysql_query($qry);
	//$row = mysql_fetch_array($exec);

	if (isset($_SESSION['s_uid'])) {
	//if (isset($_COOKIE['cook_oco_uid'])) {

		$qry = "SELECT * FROM temas t, uthemes u  WHERE u.uth_uid = ".$_SESSION['s_uid']." and t.tm_id = u.uth_thid";
		$exec = mysql_query($qry) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMA��ES DO TEMA!<BR>'.$qry);
		$row = mysql_fetch_array($exec);
		$regs = mysql_num_rows($exec);
		if ($regs==0){ //SE N�O ENCONTROU TEMA ESPEC�FICO PARA O USU�RIO
			$qry = "SELECT * FROM styles";
			$exec = mysql_query($qry);
			$row = mysql_fetch_array($exec);
		}
	} else {
		$qry = "SELECT * FROM styles";
		$exec = mysql_query($qry);
		$row = mysql_fetch_array($exec);
	}

print "
body
{
	padding: 1px 1px 1px 1px;
	margin: 0px 0px 0px 0px;
}

#eWysiwygCell, .Source
{
	border: #696969 1px solid;
}

#eSourceField
{
	border: none;
	padding: 5px;
	font-family: Monospace;
}

/*
	### Toolbar Styles
*/

.TB_ToolbarSet, .TB_Expand, .TB_Collapse
{
	background-color: ".$row['tm_color_td'].";
}

.TB_End
{
	display: none;
}

.TB_ExpandImg
{
	background-image: url(images/toolbar.expand.gif);
	background-repeat: no-repeat;
}

.TB_CollapseImg
{
	background-image: url(images/toolbar.collapse.gif);
	background-repeat: no-repeat;
}

.TB_ToolbarSet
{
	border-top: ".$row['tm_color_td']." 1px outset;
	border-bottom: ".$row['tm_color_td']." 1px outset;
}

.TB_ToolbarSet, .TB_ToolbarSet *
{
	font-size: 11px;
	cursor: default;
	font-family: 'Microsoft Sans Serif' , Tahoma, Arial, Verdana, Sans-Serif;
}

.TB_Expand, .TB_Collapse
{
	padding: 2px 2px 2px 2px;
	border: ".$row['tm_color_td']." 1px outset;
}

.TB_Collapse
{
	border: ".$row['tm_color_td']." 1px outset;
	width: 5px;
}

.TB_Button_On, .TB_Button_Off, .TB_Button_Disabled, .TB_Combo_Off, .TB_Combo_Disabled
{
	border: ".$row['tm_color_td']." 1px solid;
	height: 21px;
}

.TB_Button_On
{
	border-color: #316ac5;
	background-color: #c1d2ee;
}

.TB_Button_Off, .TB_Combo_Off
{
	filter: alpha(opacity=70);
	-moz-opacity: 0.70;
	background-color: ".$row['tm_color_td'].";
}

.TB_Button_Disabled, .TB_Combo_Disabled
{
	filter: gray() alpha(opacity=30);
	-moz-opacity: 0.30;
}

.TB_Button_On_Over, .TB_Button_Off_Over
{
	background-color: #dff1ff;
}

.TB_Icon DIV
{
	width: 21px;
	height: 21px;
	background-position: 50% 50%;
	background-repeat: no-repeat;
}

.TB_Text
{
	height: 21px;
	padding-right: 5px;
}

.TB_ButtonArrow
{
	padding-right: 3px;
}

.TB_Break
{
	height: 23px;
}";
?>