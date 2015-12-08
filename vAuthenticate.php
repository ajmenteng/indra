<?php
/*
A PART OF INDRA (IN DAILY REPORT ANALYZER) WEB APPLICATION.
THIS FILE IS USED FOR PROCESSING THE LOGIN CREDENTIALS THAT HAVE BEEN PASSED ON FROM THE LOGIN PAGE

AUTHOR: JOHN CHANDRA
*/

/*
AUTH.PHP CONTAINS A CLASS FOR USER'S AUTHENTICATION
AUTHCONFIG.PHP CONTAINS THE CONFIGURATION OF THE DATABASE AND PAGES DIRECTIONAL INFORMATION
*/
include ("auth.php");
include ("authconfig.php");

$username =  $_POST['inpUsername'];
$password =  md5($_POST['inpPassword']);

$Auth = new authUser();
$detail = $Auth->authenticate($username, $password,1);

/*
IF THE AUTHENTICATION IS FAILED, ALERT THE USER.
*/
if ($detail == 0)
{
    echo "<HEAD>\n";
    echo "<SCRIPT language=\"JavaScript1.1\">\n";
    echo "<!--\n";
    echo "	location.replace(\"$failure\");\n";
    echo $detail;
    echo "//-->\n";
    echo "</SCRIPT>\n";
    echo "</HEAD>\n";

}

/*
UPON SUCCESSFUL, SAVE TO SESSION VARIABLE AND REDIRECT THE PAGE.
*/
else 
{
    $_SESSION["sesUsername"] = $username;
    $_SESSION["sesPassword"] = $password;

    echo "<HEAD>\n";
    echo "<SCRIPT language=\"JavaScript1.1\">\n";
    echo "<!--\n";

    if (($detail["loginCount"] == 0) || ($detail["lastLogin"] > $detail["exprdLogin"])) { 
        echo "	location.replace(\"$changepassword\");\n";		
    } else {
        echo "	location.replace(\"$success\");\n";
    }

    echo "//-->\n";
    echo "</SCRIPT>\n";
    echo "</HEAD>\n";
}	
?>
