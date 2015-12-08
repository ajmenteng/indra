<?php
/*
A PART OF INDRA (IN DAILY REPORT ANALYZER) WEB APPLICATION.
THIS FILE IS USED AS A USER'S LOGIN INTERFACE.

AUTHOR: JOHN WESLY
*/
session_start();

/*
A CALL TO AUTHCONFIG.PHP IS NECESSARY AS IT CONTAINS PARAMETERS TO WHERE A USER SHOULD BE DIRECTED FROM HERE
*/
include("authconfig.php"); 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<TITLE>INDRA 3.0</TITLE>
<META http-equiv=Content-Type content="text/html; charset=iso-8859-1">
<META content=XQvPp6i+kUuy3E/ZPMvFFFGDEPr7mOvTxX9dQdYs9hE= name=verify-v1>
<LINK href="css/design.css" type=text/css rel=stylesheet>
<LINK href="css/layout.css" type=text/css rel=stylesheet>
<META content="MSHTML 6.00.6000.16587" name=GENERATOR>
<link rel="shortcut icon" href="images/icon_in.ico" >
</HEAD>

<BODY topmargin="10" topmargin="10" leftmargin="0" >
<center>
<table border="1" style="border-collapse:collapse; border-style:solid " cellspacing="0" cellpadding="0"><tr><td>
<table border="0" cellspacing="0" width="980">
<tr><td style="BORDER-BOTTOM: #5381FB 1px solid;padding:0px 17px 0px 17px;"  height="40" background="images/bg_header.jpg" width="100%"><img src="images/logo3_png.png" align="right"></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td style="padding:0px 17px 0px 17px">
<br>

<form name="frmLogin" method="post" action="<?php echo $resultpage ?>">
<CENTER>
  <table width="337" border="0">
    <tr>
      <td width="150" align="right">Username (NIK)</td>
      <td width="239"><div align="left"><input type="text" name="inpUsername"></div></td>
    </tr>
    <tr>
      <td align="right">Password</td>
      <td><div align="left"><input type="password" name="inpPassword"></div></td>
    </tr>
    <tr>
      <td align="center" colspan="2">&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="left"><input type="submit"  name="btnSubmit" value=" Login "></td>
	</tr>
    <tr>
	<td align="center" colspan="2">&nbsp;</td>
      </tr>
    <tr>
      <td align="center" colspan="2">
	  
	  <?php 
        /*
        THIS SECTION IS USED FOR DISPLAYING A MESSAGE REGARDING FAILED LOGIN ATTEMPT
        */
		if (isset($_GET["qryType"])){
			$qryType=$_GET["qryType"];
			if ($qryType == "WRONG") { 
					echo $_SESSION["adm_msg"];
			} elseif ($qryType == "ILLEGAL") {
				print "Illegal Access, please try again !!";
			}
		}
	 ?>        
	 </td>
    </tr>
  </table>
 </CENTER>
</form>
<br>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td style="BORDER-TOP: #5381FB 1px solid;padding:0px 17px 0px 17px" background="images/bg_header2.jpg" width="100%" height="25"><?php echo $footer; ?></td></tr>
</table>
</td></tr></table>
</center>
</BODY>
</HTML>
