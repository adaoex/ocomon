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
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;


		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);
?>

<BR>
<B>Inclus�o de categoria de softwares:</B>
<BR><br>

<FORM method="POST" action=<?php _SELF?>>
<TABLE border="0"  align="left" width="40%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Categoria:</TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" class="text" name="categoria"></TD>
        </TR>
		
		<tr><td colspan="2"></td></tr>
        <TR>
                <TD align="center" width="20%" bgcolor=<?php print BODY_COLOR?>><input type="submit" value="Cadastrar" name="submit">
                </TD>
				<TD align="center" width="30%" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar" onClick="javascript:window.close()"></TD>
        </TR>

        <?php 
                if ($submit == "Cadastrar")
                {
                        $erro="n�o";

                        if (empty($categoria))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM categorias WHERE cat_desc='$categoria'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Essa categoria j� est� cadastrada!";
                                $erro = "sim";
                        }

                        if ($erro == "n�o")
                        {
                                $query = "INSERT INTO categorias (cat_desc) values ('$categoria')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir categoria";
                                }
                                else
                                {
                                        $aviso = "OK. Categoria inclu�da com sucesso!";
                                }
                        }
				   		print "<script>mensagem('$aviso'); window.opener.location.reload();</script>";               
						$origem = "incluir_categoria.php";
                        session_register("aviso");
                        session_register("origem");
                }
       

	   
	    ?>


</TABLE>
</FORM>

</body>
</html>
