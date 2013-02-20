<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></meta>
<link rel="stylesheet" href="layersmenu-demo.css" type="text/css"></link>
<link rel="stylesheet" href="layerstreemenu.css" type="text/css"></link>
<style type="text/css">
<!--
@import url("layerstreemenu-hidden.css");
//-->
</style>
<link rel="shortcut icon" href="LOGOS/shortcut_icon_phplm.png"></link>
<title>The PHP Layers Menu System</title>
</head>
<body>

<div class="normalbox">
<div class="normal" align="center">
<b><?php  print basename(__FILE__); ?> - a file-based example with a PHP Tree Menu</b>
</div>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="20%" valign="top">
<div class="normalbox">
<div class="normal">
PHP Tree Menu
</div>
<?php 
require_once 'lib/PHPLIB.php';
require_once 'lib/layersmenu-common.inc.php';
require_once 'lib/phptreemenu.inc.php';
$mid = new PHPTreeMenu();
//$mid->setDirroot('./');
////$mid->setImgdir('./menuimages/');
//$mid->setImgwww('menuimages/');
////$mid->setIcondir('./menuicons/');
//$mid->setIconwww('menuicons/');
$mid->setMenuStructureFile('layersmenu-vertical-1.txt');
$mid->setPHPTreeMenuDefaultExpansion('2|3|6');
$mid->setIconsize(16, 16);
$mid->parseStructureForMenu('treemenu1');
//$mid->setSelectedItemByCount('treemenu1', 7);
$mid->setSelectedItemByUrl('treemenu1', basename(__FILE__));
print $mid->newPHPTreeMenu('treemenu1');
 /* alternatively:
$mid->newPHPTreeMenu('treemenu1');
$mid->printPHPTreeMenu('treemenu1');
*/
/* alternatively:
$mid->newPHPTreeMenu('treemenu1');
$php_tree_menu1 = $mid->getPHPTreeMenu('treemenu1');
print $php_tree_menu1;
*/
?>
</div>
<br />
<center>
<a href="http://phplayersmenu.sourceforge.net/"><img border="0"
src="LOGOS/powered_by_phplm.png" alt="Powered by PHP Layers Menu" height="31" width="88" /></a>
</center>
<br />
<center>
<a href="http://validator.w3.org/check/referer"><img border="0"
src="images/valid-xhtml10.png" alt="Valid XHTML 1.0!" height="31" width="88" /></a>
</center>
<br />
<center>
<a href="http://jigsaw.w3.org/css-validator/"><img border="0"
src="images/vcss.png" alt="Valid CSS!" height="31" width="88" /></a>
</center>
</td>
<td valign="top">
<div class="normalbox">
<div class="normal">
<?php  require_once 'README.ihtml'; ?>
</div>
</div>
</td>
</tr>
</table>

</body>
</html>
