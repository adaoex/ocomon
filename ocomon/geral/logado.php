<?php 
# Inlcuir coment�rios e informa��es sobre o sistema
#
################################################################################
#                                  CHANGELOG                                   #
################################################################################
#  incluir um changelog
################################################################################

if (!isset($_SESSION['s_logado']) || $_SESSION['s_logado'] == 0)
{
	print "<script>window.location.href='../../index.php';</script>";
	exit;
}
?>