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

include ("var_sessao.php");
include ("funcoes.inc");
include ("config.inc.php");
include ("logado.php");

$hoje = date("Y-m-d H:i:s");




?>

<HTML>
<BODY bgcolor=<?php print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - Controle de Invent�rio  -  Usu�rio: <font color=red>$s_usuario</font></b></TD>";
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
<B>Inclus�o de monitores</B> (campos marcados com <B>*</B> devem ser preenchidos).
<BR>

<FORM method="POST" action=<?php _SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?php print BODY_COLOR?>>
        
		
       <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>Unidade*:</b></TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='mon_inst' size=1>";
                print "<option value=-1 selected>Unidade: </option>";
                $query = "SELECT * from instituicao  order by inst_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
        </tr>
        </table>		
		
		
		
		
		
		
		<TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>C�digo de Invent�rio *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="mon_inv" maxlength="10" size="39"></TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>N�mero de S�rie *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="mon_sn" maxlength="10" size="30"></TD>

        </TABLE>
       </TR>


        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>Fabricante *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='mon_fabricante' size=1>";
                print "<option value=-1 selected>Selecione o fabricante ---------------</option>";
                $query = "SELECT * from fabricantes order by fab_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>Modelo *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='mon_modelo' size=1>";
                print "<option value=-1 selected>Selecione o modelo -----------------</option>";
                $query = "SELECT * from modelos order by modelo_desc";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>




        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>Fornecedor *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='mon_fornecedor' size=1>";
                print "<option value=-1 selected>Selecione o fornecedor ---------------</option>";
                $query = "SELECT * from fornecedores  order by forn_nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>

                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>Nota Fiscal:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="mon_nf" maxlength="30" size="30"></TD>

        </tr>
        </table>


        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>Invent�rio associado *:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='mon_comp_inv' size=1>";
                print "<option value=-1 selected>C�digo de invent�rio associado</option>";
                $query = "SELECT comp_inv from computadores  order by comp_inv";
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
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><b>Localiza��o*:</b></TD>
                <TD width="30%" align="left" bgcolor=<?php print BODY_COLOR?>>
                <?php print "<SELECT name='mon_local' size=1>";
                print "<option value=-1 selected>Selecione o local</option>";
                $query = "SELECT * from localizacao  order by local";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?php print mysql_result($resultado,$i,0);?>">
                                         <?php print mysql_result($resultado,$i,1);?>
                       </option>
                       <?php 
                       $i++;
                }
                ?>
                </SELECT>
                </TD>
        </table>
        </tr>






        <TR>
        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>

        <?php 

                if ($rodou == "sim")
                {
                        $erro="n�o";

#############################################

                        $query2 = "SELECT m.*, c.* FROM monitores as m, computadores as c 
									WHERE 
										(m.mon_inv='$mon_inv') or ((c.comp_inv = '$mon_inv') and 
											(c.comp_inst = '$mon_inst'))";
                        $resultado2 = mysql_query($query2);
                        $linhas = mysql_numrows($resultado2);
                        if ($linhas > 0)
                        {
                                $aviso = "Este c�digo de invent�rio j� est� cadastrado sistema!";
                                $erro = "sim";
                        }
############################################






                        if ( empty($mon_inv) or  ($mon_modelo==-1) or ($mon_fornecedor ==-1) or ($mon_local==-1) or
                             ($mon_fabricante==-1) or $mon_inst ==-1)

                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }


                        if ($erro=="n�o")
                        {


                                $data = $hoje;

                                        $query = "INSERT INTO monitores (mon_inv, mon_fabricante, mon_modelo, mon_fornecedor,
                                                  mon_sn, mon_nf, mon_comp_inv, mon_local, mon_inst) values ('$mon_inv','$mon_fabricante',
                                                  '$mon_modelo','$mon_fornecedor','$mon_sn','$mon_nf','$mon_comp_inv','$mon_local', '$mon_inst')";
                                        $resultado = mysql_query($query);


                                if ($resultado == 0)
                                {
                                        print $query;

                                        $aviso = "ERRO na inclus�o dos dados.";
                                }
                                else
                                {
                                        $numero = mysql_insert_id();                                                 //$numero
                                        $aviso = "OK. Monitor inventariado com sucesso.<BR>C�digo: <font color=red>$comp_inv</font>";

                                }
                        }
                        $origem = "incluir_monitor.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }

        ?>

</TABLE>
</FORM>

</body>
</html>
