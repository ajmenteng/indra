<?php
/*
A PART OF INDRA (IN DAILY REPORT ANALYZER) WEB APPLICATION. THIS FILE CONTAINS CLASS DECLARATION FOR USER'S AUTHENTICATION.
AUTHOR: JOHN CHANDRA
*/

session_start();

/*
A CLASS FOR USER AUTHENTICATION
*/
class authUser{
	/*
    THE PARAMETERS FOR DATABASE CONNECTION. PLEASE FILL THEM ACCORDINGLY.
    */
	var $HOST = "[HOSTNAME]";
	var $USERNAME = "[USERNAME]";
	var $PASSWORD = "[PASSWORD]";
	var $DBNAME = "[DATABASE NAME]";
	
	/*
    A FUNCTION FOR AUTHENTICATION
    */
	function authenticate($username, $password, $flag) {
        
        /*
        PREPARE A QUERY FOR UPDATING THE USER'S STATUS ON THE SYSTEM. EACH USER HAS THE MAXIMUM OF THREE LOGIN ATTEMPTS. 
        THE COUNTER WILL BE RESET ON EVERY SUCCESSFUL LOGIN.
        */
		$unlock_str="UPDATE TUser SET state='active',errCounter=0,lastLogin=now() WHERE state='inactive' and errCounter=3
					AND id='$username' AND ((DATEDIFF(NOW(),lastLogin)>0) OR (HOUR(TIMEDIFF(NOW(),lastLogin))>0) 
					OR (MINUTE(TIMEDIFF(NOW(),lastLogin))>15))";

        /*
        PREPARE A QUERY TO SEARCH FIND A USER IN THE DATABASE WHERE THE STATUS IS STILL ACTIVE (OR NOT LOCKED)
        */
		$query = "SELECT * FROM TUser WHERE id='$username' AND passwd='$password' AND state <> 'inactive'";
	
        /*
        RECORD THE IP ADDRESS OF A CLIENT ACCESSING THE SYSTEM
        */
		$ip = getenv('REMOTE_ADDR');
		$UpdateRecords = "UPDATE TUser SET lastlogin = NOW(), logincount = logincount + 1, errCounter=0, lastIp='$ip' WHERE id='$username'";

        
		$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);
		$SelectedDB = mysql_select_db($this->DBNAME);
		
        /*
        EXECUTE THE QUERY FOR UPDATE A USER'S STATUS
        */
		mysql_query($unlock_str);
		
        /*
        EXECUTE THE QUERY FOR A USER
        */
		$result = mysql_query($query); 		
		$numrows = mysql_num_rows($result);
		$row = mysql_fetch_array($result);
        
        /*
		IF THERE ARE NO RESULT, THEN FURTHER CHECK WILL BE APPLIED.
		*/
		if ($numrows == 0) {
			$query2="SELECT * from TUser where id='$username'";
			$result2=mysql_query($query2);
			if(mysql_num_rows($result2)>0){
				$row2=mysql_fetch_array($result2);
				$userState=$row2["state"];
				if(strtolower($userState)=='active'){	
					$errCounter=$row2["errCounter"];				
					if($errCounter<3){
						$errCounter=$errCounter+1;
						$UpdateErrCounter= "UPDATE TUser SET errCounter=$errCounter, lastLogin=now() where id='$username'";
						mysql_query($UpdateErrCounter);
						$remainCounter=3-$errCounter;
						if($remainCounter>0){
							$_SESSION["adm_msg"]="Invalid password. Login attempts remain $remainCounter time(s)";
						}
						else {
							$UpdateState="UPDATE TUser SET state='inactive', lastLogin=now() where id='$username'";
							mysql_query($UpdateState);
							$_SESSION["adm_msg"]="Your account has been locked! <br>Please wait for 15 minutes or contact administrator!";
						}
					}
					else{
						$UpdateState="UPDATE TUser SET state='inactive', lastLogin=now() where id='$username'";
						mysql_query($UpdateState);
						$_SESSION["adm_msg"]="Your account has been locked! <br>Please wait for 15 minutes or contact administrator!";
					}
				}
				elseif(strtolower($userState)=='inactive'){
					
				}
			}			
			return 0;
		}
        
        /*
        OTHERWISE MEANS THAT THE LOGIN IS SUCCESSFUL THUS IT NEEDS TO UPDATE THE ACCESS LOG.        
        */
		else {
			if ($flag == 1) {
				$Update = mysql_query($UpdateRecords);
			}
			return $row ;
		}
	}

    /*
    THIS FUNCTION IS CALLED ON PAGES THAT REQUIRE USERS TO LOGIN FIRST.
    */
	function page_check($username, $password) {
		$query = "SELECT * FROM TUser WHERE id='$username' AND passwd='$password' AND state <> 'inactive'";

    	$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);
		
		$SelectedDB = mysql_select_db($this->DBNAME);
		$result = mysql_query($query); 
		
		$numrows = mysql_num_rows($result);
		$row = mysql_fetch_array($result);

		if ($numrows == 0) {
			return false;
		}
		else {
			return $row;
		}
	}
	
    /*
    THIS FUNCTION IS CALLED FOR MODIFYING A USER'S CREDENTIALS
    */
	function modify_user($username, $password) {
        $qUpdate = "UPDATE TUser SET passwd=MD5('$password'), exprdLogin = ADDDATE(CURDATE(),INTERVAL 1 MONTH) 
			            WHERE id='$username'";

		if (($username=="sa" AND $state=="inactive")) {
			return "sa cannot be inactivated";
		}
		elseif (($username=="admin" AND $state=="inactive")) {
			return "admin cannot be inactivated";
		}
		else {
			$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);
			$SelectedDB = mysql_select_db($this->DBNAME);
			$result = mysql_query($qUpdate); 
			return 1;
		}
		
	}
    
    /*
    THIS FUNCTION IS CALLED FOR MODIFYING A USER'S PROFILE
    */
	function modify_user_profile($username, $password, $fullName, $dept, $approver, $shift, $region, $office) {
        $qUpdate = "UPDATE TUser SET fullName='$fullName', dept='$dept', 
					approver = $approver, shift='$shift', region='$region', office='$office' WHERE id='$username'";
    	
		$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);
		$SelectedDB = mysql_select_db($this->DBNAME);
		$result = mysql_query($qUpdate); 
		return 1;
	}

    /*
    THIS FUNCTION IS CALLED FOR GETTING A USER'S AREA SO THE SYSTEM CAN DISPLAY A LIST OF CHECKLISTS ONLY FROM RELATED AREA
    */
	function get_user_area($columnId, $areaValue) {		
		$my_qry = "";
 		$my_area = explode (',',$areaValue);
		
		if ( count ($my_area) > 1 ) {
			$areaValue = $my_area[0];
			for ($i = 0; $i < count($my_area) - 1; $i++) {
				$my_qry .= " OR " . $columnId . " LIKE '" . $my_area[$i + 1] . "'"; 
			}
		} 
		
		return "(" . $columnId . " LIKE '" . trim($areaValue) . "'" . $my_qry . ")";
		     	
	} 

    /*
    THIS FUNCTION IS CALLED FOR GETTING USER'S FULLNAME
    */
	function get_user_fullname($username) {
		$query = "SELECT id, fullName FROM TUser WHERE id='$username'";

    	$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);
		
		$SelectedDB = mysql_select_db($this->DBNAME);
		$result = mysql_query($query); 
		
		$numrows = mysql_num_rows($result);
		$row = mysql_fetch_array($result);

		if ($numrows == 0) {
			return false;
		}
		else {
			return $row[1];
		}		     	
	}	
    
    /*
    THIS FUNCTION IS CALLED FOR GETTING A USER'S APPROVER INFORMATION
    */
	function get_user_approver($username) {
		$query = "SELECT id,approver FROM TUser WHERE id='$username'";

    	$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);
		
		$SelectedDB = mysql_select_db($this->DBNAME);
		$result = mysql_query($query); 
		
		$numrows = mysql_num_rows($result);
		$row = mysql_fetch_array($result);

		if ($numrows == 0) {
			return false;
		}
		else {
			return $row[1];
		}		     	
	}
}
?>
