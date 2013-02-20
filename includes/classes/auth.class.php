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


class auth {
	var $saida;
	var $texto;


	function testa_user($s_usuario, $s_nivel, $s_nivel_desc, $permissao, $help=''){

		if (!isset($_SESSION['s_logado']) || $_SESSION['s_logado'] == 0)
		{
			print "<script>window.open('../../index.php','_parent','')</script>";
			exit;
		} else {
			//print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>";
			if($help!=''){
				$help = "&nbsp;<a><img align='absmiddle' src='".ICONS_PATH."help-16.png' width='16' height='16' border='0' onClick=\"return popupS('".HELP_PATH."".$help."')\"></a>";
			}

			if ($s_nivel>$permissao) //se o n�vel do usu�rio for maior do que a permiss�o necess�ria para o script..
			{
				$this->saida= "<script>window.open('../../index.php','_parent','')</script>";
				exit;
			} else {
				if (is_file( "./.invmon_dir" )) $this->texto = TRANS('MENU_TTL_MOD_INV'); else
				if (is_file( "./.admin_dir" )) $this->texto = TRANS('MENU_TTL_MOD_ADMIN');
				else $this->texto = TRANS('MENU_TTL_MOD_OCCO');

				$this->saida =  "<TABLE class='header_centro' cellspacing='1' border='0' cellpadding='1' align='center' width='100%'>".//#5E515B
								"<TR>".
									"<TD nowrap width='75%'><b>".$this->texto."</b></td>".
									"<td width='25%' nowrap><p class='parag' align='right'><b>".TRANS(date("l")).",&nbsp;".(formatDate(date("Y/m/d H:i"), " %H:%M"))."</b>".$help."</p></TD>";
				$this->saida.= "</TR>".
						"</TABLE>";
			}
			print $this->saida;
		}
	}

	function testa_user_hidden($s_usuario, $s_nivel, $s_nivel_desc, $permissao, $help=''){

		if (!isset($_SESSION['s_logado']) || $_SESSION['s_logado'] == 0)
		{
			print "<script>window.open('../../index.php','_parent','')</script>";
			exit;
		} else {
			//print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>";
			if($help!=''){//../../includes/icons/   align='top' //absmiddle  absbottom
				$help = "&nbsp;<a><img align='absmiddle' src='".ICONS_PATH."help-16.png' width='16' height='16' border='0' onClick=\"return popupS('".HELP_PATH."".$help."')\"></a>";
			}

			if ($s_nivel>$permissao) //se o n�vel do usu�rio for maior do que a permiss�o necess�ria para o script..
			{
				$this->saida= "<script>window.open('../../index.php','_parent','')</script>";
				exit;
			} else {
				if (is_file( "./.invmon_dir" )) $this->texto = TRANS('MENU_TTL_MOD_INV'); else
				if (is_file( "./.admin_dir" )) $this->texto = TRANS('MENU_TTL_MOD_ADMIN');
				else $this->texto = TRANS('MENU_TTL_MOD_OCCO');

				$this->saida =  "<TABLE class='header_centro' cellspacing='1' border='0' cellpadding='1' align='center' width='100%'>".//#5E515B
								"<TR>".
				$this->saida.= "</TR>".
						"</TABLE>";
				$this->saida = "";
			}
			print $this->saida;
		}
	}
}
?>