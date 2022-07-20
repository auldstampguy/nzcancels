<?php
/*
 *       index.php
 *      
 *      Copyright 2022 Tim Auld <tim@auld.us>
 *
 * 		This program will: 
 * 		1. Provide the main process for the NZCANCELS.ORG website
 */

		//Setup Global Variables
		$sys_defaults = array();
		$glb_db = "";	//Global database connection
		
		
		//Initialization
		Initialization();
		
		include($_SERVER["DOCUMENT_ROOT"]."/nz_functions.php");
		
		//Security
		Security();
		
		//Display Screens
		if (isset($_GET["a"])) {
			$wrk_msg_screen = Process_Get_Requests() ;
		}
		elseif (isset($_POST["step"])) {
			$wrk_msg_screen = Process_Post_Requests();
		}
		else {
			$wrk_msg_screen = Display_Home_Page();
		}

		//Build Heading and Footer
		$wrk_msg_heading = Build_Heading();
		$wrk_msg_footer = Build_Footer();
				
		//Cookie Settings
		Cookie_Settings();
		
		//Build Final Message
		$wrk_final_message = $wrk_msg_heading.$wrk_msg_screen.$wrk_msg_footer;
		echo $wrk_final_message;
		

	//****** SCREEN PROCESS SECTION ******
	
	
	function Process_Get_Requests() {
		//This function controls the process of the Get requests
		$wrk_act = $_GET["a"];
		
		//Login
		if ($wrk_act == "1") {
			$prm_error = "";
			$wrk_msg = Login_Display($prm_error);
		}
		//Logoff
		elseif ($wrk_act == "9") {
			$wrk_msg = "";
			Logoff_Process ();
		}
		//Admin Mode On
		elseif ($wrk_act == "98") {
			$wrk_msg = Admin_Mode_On ();
		}
		//Admin Mode Off
		elseif ($wrk_act == "99") {
			$wrk_msg = Admin_Mode_Off ();
		}
		
		//New Zealand Post Office Lookup
		elseif ($wrk_act == "10") {
			$prm_error = "";
			$wrk_msg = MN_PO_Lookup($prm_error);
		}
		//List of Covers for Post Office
		elseif ($wrk_act == "15") {
			$wrk_po_id = $_GET["po"];
			if (!is_numeric($wrk_po_id)) {
				$wrk_error = "ERROR: Error occurred on PO param input";
				return $wrk_error;
			}
			else {
				$wrk_msg = List_Of_Covers_For_Post_Office($wrk_po_id);
			}
		}
		//List of Covers for Post Office
		elseif ($wrk_act == "16") {
			$wrk_cov_id = $_GET["id"];
			if (!is_numeric($wrk_cov_id)) {
				$wrk_error = "ERROR: Error occurred on Cover param input";
				return $wrk_error;
			}
			else {
				$wrk_msg = Display_Cover_Information($wrk_cov_id);
			}
		}
		//Display Covers for Post Office
		elseif ($wrk_act == "17") {
			$wrk_po_id = $_GET["id"];
			if (!is_numeric($wrk_po_id)) {
				$wrk_error = "ERROR: Error occurred on PO param input";
				return $wrk_error;
			}
			else {
				$wrk_msg = Display_Covers_for_a_Post_Office($wrk_po_id);
			}
		}
		//Display Covers for Post Office
		elseif ($wrk_act == "18") {
			$wrk_county = $_GET["id"];
			if (strlen($wrk_county) > 30) {
				$wrk_error = "ERROR: Error occurred on County param input";
				return $wrk_error;
			}
			else {
				$wrk_msg = Display_Covers_for_a_County($wrk_county);
			}
		}
		
		//Display Rarity Factor
		elseif ($wrk_act == "20") {
			$wrk_msg = Display_Rarity_Factor();
		}
		//Display List of Rare Post Offices
		elseif ($wrk_act == "21") {
			$wrk_msg = List_Rare_Post_Offices();
		}
		//Display List of 70 Earliest Covers
		elseif ($wrk_act == "22") {
			$wrk_msg = List_Earliest_Covers();
		}
		//Display List of 50 Last Added Covers
		elseif ($wrk_act == "23") {
			$wrk_msg = List_Recently_Added_Covers();
		}
		//Display List of My Favorite Covers
		elseif ($wrk_act == "24") {
			$wrk_msg = List_My_Favorites();
		}
		//Display List of Covers that need further research
		elseif ($wrk_act == "25") {
			$wrk_msg = List_Of_Covers_Needing_Research();
		}
		
		//Edit Cover Display
		elseif ($wrk_act == "30") {
			$wrk_cover_id = $_GET["id"];
			if (!is_numeric($wrk_cover_id)) {
				$wrk_error = "ERROR: Error occurred on Cover param input";
				return $wrk_error;
			}
			else {
				$wrk_error = "";
				$wrk_msg = Edit_Cover_Display($wrk_cover_id, $wrk_error);
			}
		}

		//Add Cover Display
		elseif ($wrk_act == "35") {
			$wrk_po_id = $_GET["id"];
			if (!is_numeric($wrk_po_id)) {
				$wrk_error = "ERROR: Error occurred on PO param input";
				return $wrk_error;
			}
			else {
				$wrk_error = "";
				$wrk_msg = Add_Cover_Display($wrk_po_id, $wrk_error);
			}
		}

		//Add Cover Display
		elseif ($wrk_act == "40") {
			$wrk_cvr_id = $_GET["id"];
			if (!is_numeric($wrk_cvr_id)) {
				$wrk_error = "ERROR: Error occurred on Cover param input";
				return $wrk_error;
			}
			else {
				$wrk_error = "";
				$wrk_msg = Delete_Cover_Display($wrk_cvr_id);
			}
		}
		
		
		//Display List of Covers that need further research
		elseif ($wrk_act == "45") {
			$prm_error = "";
			$wrk_msg = Add_Post_Office_Display($prm_error);
		}
		
		//Edit Post Office Display
		elseif ($wrk_act == "47") {
			$wrk_po_id = $_GET["id"];
			if (!is_numeric($wrk_po_id)) {
				$wrk_error = "ERROR: Error occurred on Post Office param input";
				return $wrk_error;
			}
			else {
				$wrk_error = "";
				$wrk_msg = Edit_Post_Office_Display($wrk_po_id, $wrk_error);
			}
		}
		
		//Delete Post Office Display
		elseif ($wrk_act == "49") {
			$wrk_po_id = $_GET["id"];
			if (!is_numeric($wrk_po_id)) {
				$wrk_error = "ERROR: Error occurred on Post Office param input";
				return $wrk_error;
			}
			else {
				$wrk_error = "";
				$wrk_msg = Delete_Post_Office_Display($wrk_po_id);
			}
		}
			

		//Image Upload Display
		elseif ($wrk_act == "50") {
			$wrk_cover_id = $_GET["id"];
			$wrk_pic = $_GET["pic"];
			if (!is_numeric($wrk_cover_id)) {
				$wrk_error = "ERROR: Error occurred on Cover param input";
				return $wrk_error;
			}
			if (($wrk_pic != "1") && ($wrk_pic != "2")) { 
				$wrk_error = "ERROR: Error occurred on Pic Number param input";
				return $wrk_error;
			}
			else {
				$cvr = new MN_Cover;
				$cvrs = new MN_Covers;
				if (!$cvr = $cvrs->Fetch($wrk_cover_id)) {
					$wrk_error = "ERROR: Cover not found on Image Upload";
					return $wrk_error;
				}
				$wrk_msg = Upload_Image_Display ($cvr, $wrk_pic);
			}
		}

		//Test Log File Read
		elseif ($wrk_act == "61") {
			$prm_error = "";
			$wrk_msg = Log_File_Read();
		}
		//Test Log File Read
		elseif ($wrk_act == "62") {
			$wrk_msg = Display_Cover_Statistics();
		}

		//PO Data File Upload
		elseif ($wrk_act == "70") {
			$prm_error = "";
			$wrk_msg = Upload_PO_File_Display($prm_error);
		}
		
		
		
		
		
		return $wrk_msg;
		
	}
	
	function Process_Post_Requests() {
		//This function controls the process of the Post requests
		$wrk_step = $_POST["step"];
		
		//Login process
		if ($wrk_step == "L1") {
			$wrk_msg = Login_Process ();
		}
		//Image Upload Process
		elseif ($wrk_step == "U1") {
			$wrk_cvr_id = $_POST["pst_cvr_id"];
			$wrk_pic_num = $_POST["pst_picture_num"];
			$wrk_msg = Upload_Image_Process ($wrk_cvr_id, $wrk_pic_num);
		}
		//Edit Cover Process
		elseif ($wrk_step == "C1") {
			$wrk_msg = Edit_Cover_Process();
		}
		//Add Cover Process
		elseif ($wrk_step == "A1") {
			$wrk_msg = Add_Cover_Process();
		}
		//Delete Cover Process
		elseif ($wrk_step == "D1") {
			$wrk_cvr_id = $_POST["pst_id"];
			$wrk_msg = Delete_Cover_Process($wrk_cvr_id);
		}
		//Add Post Office Process
		elseif ($wrk_step == "P1") {
			$wrk_msg = Add_Post_Office_Process();
		}
		//Edit Post Office Process
		elseif ($wrk_step == "E1") {
			$wrk_msg = Edit_Post_Office_Process();
		}
		//Delete Post Office Process
		elseif ($wrk_step == "D2") {
			$wrk_msg = Delete_Post_Office_Process();
		}

		//Post Office Data File Upload Process
		elseif ($wrk_step == "U2") {
			$wrk_msg = Upload_PO_File_Process();
		}
		

		
		return $wrk_msg;
	}
	
	function Display_Home_Page() {
		//This function will display the Home Page

		Write_Log_Record("Display_Home_Page");
		
		$wrk_msg = "";
		
		
		if (isset($_SESSION["ssn_un"] )) {
			$wrk_logged_in = "Y";
		}
		else {
			$wrk_logged_in = "N";
		}
		
		//Check the Admin Flag to see if the current user is an Administrator
		if ($wrk_logged_in == "Y") {
			if ($_SESSION["ssn_admin_flag"] == "Y") {
				if (isset($_SESSION["ssn_admin_mode"])) {
					$wrk_admin_mode_lit = "&nbsp;<input type=\"button\" class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" name=\"pst_admin_exit\" value=\"Exit Admin\" onclick=\"javascript:document.location.href='/?a=99'\">";
				}
				else {
					$wrk_admin_mode_lit = "&nbsp;<input type=\"button\" class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" name=\"pst_admin_mode\" value=\"Admin Mode\" onclick=\"javascript:document.location.href='/?a=98'\">";
				}
			}
			else {
				$wrk_admin_mode_lit = "";
			}
		}
		else {
			$wrk_admin_mode_lit = "";
		}
		
		$wrk_msg .= "<div class=\"w3-container w3-line-height-large\" style=\"max-width: 900px; margin:auto; \">";
		if ($wrk_logged_in == "Y") {
			$wrk_msg .= "<p>Welcome ".$_SESSION["ssn_given_name"]."!<br>";
		}
		$wrk_msg .= "<p>This site is dedicated to providing information on all the post offices that have ever cancelled and processed postal items in New Zealand. The site provides lookup tools so that you can get information about a particular post office such as the Postal District it was in, the dates that it operated in and the rarity of the covers that can be found carrying that cancel. You can also post your covers on the site so that we can all enjoy them. You must be a registered member of the site to do so. <br>";
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "<div class=\"w3-container w3-right-align\" style=\"max-width: 900px; margin:auto;\">";
		if ($wrk_logged_in == "Y") {
			$wrk_msg .= "<input type=\"button\" class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" name=\"Login\" value=\"Logoff\" onclick=\"javascript:document.location.href='/?a=9'\">$wrk_admin_mode_lit";
		}
		else {
			$wrk_msg .= "<input type=\"button\" class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" name=\"Login\" value=\"Login\" onclick=\"javascript:document.location.href='/?a=1'\">$wrk_admin_mode_lit";
		}
		$wrk_msg .= "</div>";
		
		$wrk_cover = new MN_Cover;
		$wrk_cover = Get_Random_Cover();
		if ($wrk_cover != FALSE) {
			$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 900px; margin:auto; \">";
			$wrk_msg .= "<br><br><img class=\"w3-image\" src=\"".$wrk_cover->picture1."\"/>";
			$wrk_msg .= "<br><em>".$wrk_cover->destination."</em>";
			$wrk_msg .= "</div>";
		}
		
		return $wrk_msg;
	}
	
	function Get_Random_Cover() {
		//This function will retrieve an image for display on the home page

		$cov = new stdClass();
		$covs = new MN_Covers;
		$cov1 = new MN_Cover;

		$sql = "select max(id) as max_id, min(id) as min_id from MN_Covers";
		$wrk_cov_array = array();
		$wrk_cover_count = 0;
		
		if ($wrk_cov_array = $covs->Fetch_All($sql)) {
			foreach ($wrk_cov_array as $key=>$cov) {
				$wrk_min = $cov->min_id;
				$wrk_max = $cov->max_id;
			}
		}
		
		$wrk_img = "";
		$wrk_cov_id = rand($wrk_min, $wrk_max);
		if ($cov1 = $covs->Fetch($wrk_cov_id)) {
			return $cov1;
			//$wrk_img = $cov1->picture1;
		}
		else {
			return FALSE;
		}
	}
		
	function Login_Display ($prm_error) {
		//This function displays the Login Screen
		
		Write_Log_Record("Login_Display");
		
		$wrk_username = "";
		$wrk_password = "";

		if (isset($_POST["pst_username"])) {
			$wrk_username = $_POST["pst_username"];
			$wrk_password = $_POST["pst_userpassword"] ;
		}
		
		$wrk_msg = "";
		$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<p>";

		$wrk_msg .= "<form method=post action=\"/\" name=\"MemberLogin\" >";
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<h2>Please Log In:</h2>";
		$wrk_msg .= "</p>";
		  
		if ($prm_error != ""){
			$wrk_msg .= "<p>";
			$wrk_msg .= "<font color='yellow'>$prm_error</font></p>";
		}

		$wrk_msg .= "<p>";
		$wrk_msg .= "<label class=\"w3-small\">Username</label>";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" name=\"pst_username\" value=\"$wrk_username\"></p>";

		$wrk_msg .= "<p>";     
		$wrk_msg .= "<label class=\"w3-small\">Password</label>";
		$wrk_msg .= "<input class=\"w3-input\" type=\"password\" name=\"pst_userpassword\" value=\"$wrk_password\"></p>";

		$wrk_msg .= "<p>";
		$wrk_msg .= "<center><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" value=\"Login\" name=\"submit\"></center>";
		$wrk_msg .= "<input type=\"hidden\" name=\"step\" value=\"L1\">";
		$wrk_msg .= "</p>";
		
		$wrk_msg .= "<p>";
		//$wrk_msg .= "<center><A HREF=\"../mdata/pass_main.php?action=10\">Forgotten Password?</A></center>";
		$wrk_msg .= "</p>";

		$wrk_msg .= "</form>";
		$wrk_msg .= "</div>";
		
		return $wrk_msg;
	}
	
	function Login_Process () {
		//This function processes the login request
		
		$usr = new MN_User;
		$usrs = new MN_Users;
		
		//Validate Login
		$wrk_username = $_POST["pst_username"];
		$wrk_password = $_POST["pst_userpassword"];
		
		$wrk_error = "";
		if (strlen($wrk_username) == 0) {
			$wrk_error .= "<li>Username can't be blank</li>";
		}
		elseif (strlen($wrk_username) > 20) {
			$wrk_error .= "<li>Username is invalid</li>";
		}
		if (strlen($wrk_password) == 0) {
			$wrk_error .= "<li>Password can't be blank</li>";
		}
		elseif (strlen($wrk_password) > 24) {
			$wrk_error .= "<li>Password is greater than 24</li>";
		}
		
		//Get the MN_User record
		if ($wrk_error == "") {
			if (!$usr = $usrs->Fetch_by_username($wrk_username)) {
				$wrk_error .= "<li>Username was not found</li>";
				//$wrk_error .= "<li>Passwor: ".Get_Crypt($wrk_password)."</li>";
				//$wrk_error .= "<li>Guid: ".createGUID()."</li>";
			}
			else {
				//$wrk_pass_crypt = Get_Crypt($wrk_password);
				//echo "Password crypt: $wrk_pass_crypt<br>";
				//return;
				if (Get_Crypt($wrk_password) != $usr->userpassword) {
					$wrk_error .= "<li>Password is invalid</li>";
				}
			}
		}
		
		if ($wrk_error != "") {
			$wrk_msg = Login_Display($wrk_error);
			return $wrk_msg;
		}
		else {
			//All is Good.  Finish Login Process
			$_SESSION["ssn_un"] = $wrk_username;
			$_SESSION["ssn_id"] = $usr->user_id;
			$_SESSION["set_un"] = $wrk_username;
			$_SESSION["ssn_given_name"] = $usr->given_name;
			$_SESSION["set_user_guid"] = $usr->user_guid;
			$_SESSION["ssn_admin_flag"] = $usr->admin_flag ;
			
			$redir = "https://nzcancels.org";
			$wrk_msg = "";
			$wrk_msg .= "<br><center>You have successfully logged into New Zealand Cancels<br><br>";
			$wrk_msg .= "<form method=post name=\"Cont\">";
			$wrk_msg .= "<input type=\"button\" name=\"Continue\" value=\"Continue\" onclick=\"javascript:document.location.href='$redir'\">";
			$wrk_msg .= "</form>";
			
			//echo"<script type=\"text/javascript\">document.Cont.Continue.focus()</script>";
			$wrk_msg .= "<script type=\"text/javascript\">document.Cont.Continue.click()</script>";
			$wrk_msg .= "</center>";
			
			return $wrk_msg;
		}
	}

	function Logoff_Process () {
		//This function processes the logoff request
		session_destroy();
		if(isset($_COOKIE['cookie_user_guid'])) {
			unset($_COOKIE['cookie_user_guid']);
			setcookie('cookie_user_guid', '', time() - 3600, '/'); // empty value and old timestamp
		}
	
	
		$servername = $_SERVER["SERVER_NAME"];
		if ($servername == "dev.nzcancels.org")  {
			header('Location: https://dev.nzcancels.org') ;
		}
		else {
			header('Location: https://nzcancels.org') ;
		}
		
	}
	
	
	function MN_PO_Lookup($prm_error) {
		//This function handles the New Zealand Post Office Lookup

		Write_Log_Record("MN_PO_Lookup");
		
		$pos = new MN_Post_Offices;
		$cnts = new MN_Counties ;
		
		//Setup Counties arrays
		$wrk_counties_array = $cnts->Get_Array_Counties();
			
		if (isset($_SESSION["ssn_un"] )) {
			$wrk_logged_in = "Y";
		}
		else {
			$wrk_logged_in = "N";
		}
		
		//Get the Post variables
		$wrk_po_name = "";
		$wrk_county = "";
		
		if ((isset($_POST["pst_submit"])) || (isset($_POST["pst_po_name"]))) {
			if ($_POST["pst_submit"] == "Clear") {
				$_SESSION["ssn_po_name"] = null;
				$_SESSION["ssn_county"] = null;
			}
			else {	
				if (strlen(trim($_POST["pst_po_name"])) > 0) {
					$wrk_po_name = substr(trim($_POST["pst_po_name"]), 0, 30);
					$_SESSION["ssn_po_name"] = $wrk_po_name;
				}
				else {
					$_SESSION["ssn_po_name"] = null;
				}
				if (strlen(trim($_POST["pst_county"])) > 0) {
					$wrk_county = substr(trim($_POST["pst_county"]), 0, 30);
					$_SESSION["ssn_county"] = $wrk_county;
				}
				else {
					$_SESSION["ssn_county"] = null;
				}

			}
		}
		else {
			if (isset($_SESSION["ssn_po_name"]) || (isset($_SESSION["ssn_county"]))) {
				if (isset($_SESSION["ssn_po_name"])) {
					$wrk_po_name =  $_SESSION["ssn_po_name"] ;
				}
				if (isset($_SESSION["ssn_county"])) {
					$wrk_county =  $_SESSION["ssn_county"] ;
				}
			}
		}
		

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">New Zealand Post Office Lookup</div><br>";
		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<div class=\"w3-container w3-line-height-large\" style=\"max-width: 900px; margin:auto; \">";
		$wrk_msg .= "<p>This page allows you to do a look up on Post Offices within New Zealand.  If you want all the PO’s that start with 'H', enter 'H' in the PO Name field.  If you want all the PO’s in the Christchurch District, enter 'Christchurch' or 'Chr' in the District field.  If you see a black envelope next to a Post Office, it means that there is a example of the cancel from the Post Office in the system. Click on the black envelope to see the examples.</p><br><br>";		
		$wrk_msg .= "</div>";
		
		//Filter Table
		$wrk_msg .= "<a name=\"top\"></a><form autocomplete=\"off\" action=\"/?a=10#top\" method=\"post\">";
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 900px; margin:auto; \">";
		
		$wrk_msg .= "<div class=\"w3-cell-row\" style=\"max-width: 550px; margin:auto; \">";
		//PO Cell
		$wrk_msg .= "<div class=\"w3-cell w3-margin-left w3-mobile autocomplete\">";
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label class=\"w3-text-white\">Post Office</label><br>";
		//$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_po_name\" name=\"pst_po_name\" value=\"$wrk_po_name\"  style=\"max-width: 250px\" onchange=\"javascript:this.form.submit()\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_po_name\" name=\"pst_po_name\" value=\"$wrk_po_name\"  style=\"max-width: 250px\" >";
		
		$wrk_msg .= "</div>";

		//County Cell
		$wrk_msg .= "<div class=\"w3-cell w3-margin-left w3-mobile autocomplete\">";
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label class=\"w3-text-white\">District</label><br>";
		//$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_county\" name=\"pst_county\" value=\"$wrk_county\"  style=\"max-width: 250px\" onchange=\"javascript:this.form.submit()\">";		
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_county\" name=\"pst_county\" value=\"$wrk_county\"  style=\"max-width: 250px\" >";		
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "<div class=\"w3-container w3-center\">";	
		$wrk_msg .= "<input type=\"hidden\" id=\"step\" name=\"step\" value=\"A1\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" id=\"pst_submit\" name=\"pst_submit\" value=\"Submit\" style=\"cursor:pointer; margin:0;\">&nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" name=\"pst_submit\" value=\"Clear\" style=\"cursor:pointer; margin:0;\">";
		//if (isset($_SESSION["ssn_admin_mode"])) {
			//$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large w3-right\" type=\"button\" name=\"pst_add_PO\" value=\"Add Post Office\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='".$wrk_perm_link."?action=11&po=$prm_po_id&id=$prm_user_id'\">";
		//}
		$wrk_msg .= "<p>";
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";
		$wrk_msg .= "</form>";
		
		//AutoComplete Script
		$wrk_msg .= "<script>";
		/*An array containing all the country names in the world:*/
		/*<?php echo "var countries = [$wrk_mem_string]" ; ?> */
		/*$wrk_msg .= "var countries = [\"Afghanistan\",\"Albania\",\"Algeria\",\"Andorra\",\"Angola\",\"Anguilla\",\"Antigua & Barbuda\",\"Argentina\",\"Armenia\",\"Aruba\",\"Australia\",\"Austria\",\"Azerbaijan\",\"Bahamas\",\"Bahrain\",\"Bangladesh\",\"Barbados\",\"Belarus\",\"Belgium\",\"Belize\",\"Benin\",\"Bermuda\",\"Bhutan\",\"Bolivia\",\"Bosnia & Herzegovina\",\"Botswana\",\"Brazil\",\"British Virgin Islands\",\"Brunei\",\"Bulgaria\",\"Burkina Faso\",\"Burundi\",\"Cambodia\",\"Cameroon\",\"Canada\",\"Cape Verde\",\"Cayman Islands\",\"Central Arfrican Republic\",\"Chad\",\"Chile\",\"China\",\"Colombia\",\"Congo\",\"Cook Islands\",\"Costa Rica\",\"Cote D Ivoire\",\"Croatia\",\"Cuba\",\"Curacao\",\"Cyprus\",\"Czech Republic\",\"Denmark\",\"Djibouti\",\"Dominica\",\"Dominican Republic\",\"Ecuador\",\"Egypt\",\"El Salvador\",\"Equatorial Guinea\",\"Eritrea\",\"Estonia\",\"Ethiopia\",\"Falkland Islands\",\"Faroe Islands\",\"Fiji\",\"Finland\",\"France\",\"French Polynesia\",\"French West Indies\",\"Gabon\",\"Gambia\",\"Georgia\",\"Germany\",\"Ghana\",\"Gibraltar\",\"Greece\",\"Greenland\",\"Grenada\",\"Guam\",\"Guatemala\",\"Guernsey\",\"Guinea\",\"Guinea Bissau\",\"Guyana\",\"Haiti\",\"Honduras\",\"Hong Kong\",\"Hungary\",\"Iceland\",\"India\",\"Indonesia\",\"Iran\",\"Iraq\",\"Ireland\",\"Isle of Man\",\"Israel\",\"Italy\",\"Jamaica\",\"Japan\",\"Jersey\",\"Jordan\",\"Kazakhstan\",\"Kenya\",\"Kiribati\",\"Kosovo\",\"Kuwait\",\"Kyrgyzstan\",\"Laos\",\"Latvia\",\"Lebanon\",\"Lesotho\",\"Liberia\",\"Libya\",\"Liechtenstein\",\"Lithuania\",\"Luxembourg\",\"Macau\",\"Macedonia\",\"Madagascar\",\"Malawi\",\"Malaysia\",\"Maldives\",\"Mali\",\"Malta\",\"Marshall Islands\",\"Mauritania\",\"Mauritius\",\"Mexico\",\"Micronesia\",\"Moldova\",\"Monaco\",\"Mongolia\",\"Montenegro\",\"Montserrat\",\"Morocco\",\"Mozambique\",\"Myanmar\",\"Namibia\",\"Nauro\",\"Nepal\",\"Netherlands\",\"Netherlands Antilles\",\"New Caledonia\",\"New Zealand\",\"Nicaragua\",\"Niger\",\"Nigeria\",\"North Korea\",\"Norway\",\"Oman\",\"Pakistan\",\"Palau\",\"Palestine\",\"Panama\",\"Papua New Guinea\",\"Paraguay\",\"Peru\",\"Philippines\",\"Poland\",\"Portugal\",\"Puerto Rico\",\"Qatar\",\"Reunion\",\"Romania\",\"Russia\",\"Rwanda\",\"Saint Pierre & Miquelon\",\"Samoa\",\"San Marino\",\"Sao Tome and Principe\",\"Saudi Arabia\",\"Senegal\",\"Serbia\",\"Seychelles\",\"Sierra Leone\",\"Singapore\",\"Slovakia\",\"Slovenia\",\"Solomon Islands\",\"Somalia\",\"South Africa\",\"South Korea\",\"South Sudan\",\"Spain\",\"Sri Lanka\",\"St Kitts & Nevis\",\"St Lucia\",\"St Vincent\",\"Sudan\",\"Suriname\",\"Swaziland\",\"Sweden\",\"Switzerland\",\"Syria\",\"Taiwan\",\"Tajikistan\",\"Tanzania\",\"Thailand\",\"Timor L'Este\",\"Togo\",\"Tonga\",\"Trinidad & Tobago\",\"Tunisia\",\"Turkey\",\"Turkmenistan\",\"Turks & Caicos\",\"Tuvalu\",\"Uganda\",\"Ukraine\",\"United Arab Emirates\",\"United Kingdom\",\"United States of America\",\"Uruguay\",\"Uzbekistan\",\"Vanuatu\",\"Vatican City\",\"Venezuela\",\"Vietnam\",\"Virgin Islands (US)\",\"Yemen\",\"Zambia\",\"Zimbabwe\"];";  */

		
		//Get string of Post Offices
		$wrk_po_string = $pos->Get_String_Post_Offices();
		$wrk_msg .= "var post_offices = [$wrk_po_string];";

		//Get string of Counties
		$wrk_county_string = $cnts->Get_String_Counties();
		$wrk_msg .= "var counties = [$wrk_county_string];";


		/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
		$wrk_msg .= "autocomplete(document.getElementById(\"pst_po_name\"), post_offices);";
		$wrk_msg .= "autocomplete(document.getElementById(\"pst_county\"), counties);";
		
		//Check for the Enter key in the pst_po_name textbox
		$wrk_msg .= "var pst_po_name = document.getElementById(\"pst_po_name\");";
		$wrk_msg .= "pst_po_name.addEventListener(\"keydown\", function(event) { ";
		//$wrk_msg .= "    if (event.keyCode == 13) { ";
		$wrk_msg .= "    if (event.key === \"Enter\") { ";
		$wrk_msg .= "        event.preventDefault();";
		$wrk_msg .= "        document.getElementById(\"pst_submit\").click(); ";
		$wrk_msg .= "    } ";
		$wrk_msg .= "});";

		//Check for the Enter key in the pst_county textbox
		$wrk_msg .= "var pst_county = document.getElementById(\"pst_county\");";
		$wrk_msg .= "pst_county.addEventListener(\"keydown\", function(event) { ";
		$wrk_msg .= "    if (event.keyCode == 13) { ";
		$wrk_msg .= "        event.preventDefault();";
		$wrk_msg .= "        document.getElementById(\"pst_submit\").click(); ";
		$wrk_msg .= "    } ";
		$wrk_msg .= "});";

		
		$wrk_msg .= "</script>";
		
		
		
		//Build the Response Table
		if (($wrk_po_name != "") || ($wrk_county != "")) {
			$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 900px; margin:auto; \">";
			$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
			$wrk_msg .= "<tr class=\"alternate1\">";
			$wrk_msg .= "<th align=\"left\" width=\"150px\">Post Office Name</th>";
			$wrk_msg .= "<th align=\"left\" width=\"100px\">District</th>";
			$wrk_msg .= "<th align=\"left\" width=\"100px\">Period</th>";
			$wrk_msg .= "<th align=\"left\" width=\"80px\">Rarity</th>";
			$wrk_msg .= "</tr>";

			//Build SQL
			$sql = "select a.*, (select count(*) from MN_Covers b where b.po_id=a.id) as cover_count  from MN_Post_Offices a where ";
			if ($wrk_po_name != "") {
				$sql .= " po_name like \"$wrk_po_name%\"";
				if ($wrk_county != "") {
					$sql .= " and county like \"$wrk_county%\"";
				}
			}
			else {
				$sql = "select a.*, (select count(*) from MN_Covers b where b.po_id=a.id) as cover_count  from MN_Post_Offices a, MN_Counties c where a.county=c.county ";
				$sql .= " and c.county_name like \"$wrk_county%\"";
			}
			$sql .= " order by po_name asc";
			//Build list of PO's
			$po = new stdClass();
			$pos = new MN_Post_Offices;
			$wrk_po_array = array();
			
			if ($wrk_po_array = $pos->Fetch_All($sql)) {
				$wrk_alt_flag = "Y";
				foreach ($wrk_po_array as $key=>$po) {
					if ($wrk_alt_flag == "Y") {
						$wrk_alt_lit = " class=\"alternate\"";
						$wrk_alt_flag = "N";
					}
					else {
						$wrk_alt_lit = " class=\"alternate2\"";
						$wrk_alt_flag = "Y";
					}
						
					$wrk_msg .= "<tr$wrk_alt_lit>";
					//setup the Cover Icon
					if ($po->cover_count > 0) {
						$wrk_envelope_image =  "/images/envelope_very_small.png";
						$wrk_cover_icon_lit = "<a name=\"".$po->id."\" href=\"/?a=17&id=".$po->id."\"><img src=\"$wrk_envelope_image\" alt=\"Click for Cover List\" /></a>";
					}
					else {
						$wrk_cover_icon_lit = "";
					}
					//if (($wrk_curr_user_id > 1) && ($po->cover_count > 0)) {
					if (isset($_SESSION["ssn_id"])) {
						$wrk_curr_user_id = $_SESSION["ssn_id"];
						$wrk_msg .= "<td><a href=\"/?a=15&po=".$po->id."\">".$po->po_name."</a>&nbsp;$wrk_cover_icon_lit</td>";
					}
					else {
						$wrk_msg .= "<td>".$po->po_name."&nbsp;$wrk_cover_icon_lit</td>";
					}
					$wrk_msg .= "<td>".$wrk_counties_array[$po->county]."</td>";
					$wrk_msg .= "<td>".$po->period."</td>";
					
					//Add Edit image if user is logged on
					if (isset($_SESSION["ssn_admin_mode"])) {
						$wrk_edit_lit = "<span class=\"mws-align-right\"><a href=\"/?a=47&id=".$po->id."\"><img src=\"/images/edit-15x15.jpg\" alt=\"Click to Edit Post Office\" /></a></span>";
					}
					else {
						$wrk_edit_lit = "";
					}
					
					//Rarity Tim
					if (strlen($po->rarity_tim) > 0) {
						$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
					}
					else {
						$wrk_rarity_tim_lit = "";
					}
					$wrk_msg .= "<td>$wrk_edit_lit".$po->rarity."$wrk_rarity_tim_lit</td>";
					
					//if (isset($_SESSION["ssn_un"])) {
						//$wrk_edit_image =  "/images/edit-15x15.jpg";
						//$wrk_edit_lit = "<div class=\"w3-image w3-right-align\"><a href=\"$wrk_Edit_PO_link?id=".$po->id."\"><img src=\"$wrk_edit_image\" alt=\"Click to Edit Post Office\" /></a></div>";
						//$wrk_msg .= "<td>".$wrk_edit_lit."</td>";
					//}

					$wrk_msg .= "</tr>";
				}
			}
			
			$wrk_msg .= "</table></div>";
		}
		
		return $wrk_msg;
	}
	
	function List_Of_Covers_For_Post_Office($prm_po_id) {
		//This function displays the list of Covers for a Post Office

		$cov = new stdClass();
		$covs = new MN_Covers;
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
		
		Set_Request_URI();
		
		//Get the Post Office record
		if (!$po = $pos->Fetch($prm_po_id)) {
			$wrk_error = "ERROR: Post Office record was not found<br>";
			return $wrk_error;
		}
		
		$wrk_curr_user_id = $_SESSION["ssn_id"];

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">List of Covers for a Post Office</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		
		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}
		
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";
		$wrk_msg .= "</table>";
			
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\" width=\"250px\">Destination</th>";
		$wrk_msg .= "<th align=\"left\" width=\"100px\">Cancel Date</th>";
		$wrk_msg .= "<th align=\"left\" width=\"50px\">Label #</th>";
		$wrk_msg .= "</tr>";

		//Build SQL
		$sql = "select * from MN_Covers where po_id = $prm_po_id and user_id = $wrk_curr_user_id order by cancel_date";
		
		//Build list of PO's
		$wrk_cover_array = array();
		
		if ($wrk_cover_array = $covs->Fetch_All($sql)) {
			$wrk_alt_flag = "Y";
			foreach ($wrk_cover_array as $key=>$cov) {
				if ($wrk_alt_flag == "Y") {
					$wrk_alt_lit = " class=\"alternate\"";
					$wrk_alt_flag = "N";
				}
				else {
					$wrk_alt_lit = " class=\"alternate2\"";
					$wrk_alt_flag = "Y";
				}
					
				$wrk_msg .= "<tr$wrk_alt_lit>";
				//setup the Cover Icon
				//if (($wrk_curr_user_id > 1) && ($po->cover_count > 0)) {
				$wrk_curr_user_id = $_SESSION["ssn_id"];
				$wrk_msg .= "<td><a href=\"/?a=16&id=".$cov->id."\">".$cov->destination."</a></td>";
				$wrk_msg .= "<td>".mws_format_date($cov->cancel_date)."</td>";
				$wrk_msg .= "<td>".$cov->label_num."</td>";
				
				
				$wrk_msg .= "</tr>";
			}
		}
		else {
			$wrk_msg .= "<tr class=\"alternate1\">";
			$wrk_msg .= "<td class=\"w3-center\" colspan=3>There are no covers set up in the system for this Post Office<br></td>";
			$wrk_msg .= "</tr>";
		}
		
		//Buttons
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<td  class=\"w3-center\" colspan=3 align=\"center\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=10#top'\">&nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"mws_add\" value=\"Add Cover\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=35&id=$prm_po_id'\">&nbsp;";
		
		$wrk_msg .= "</td>";
		$wrk_msg .= "</tr>";
				
		$wrk_msg .= "</table></div>";
		
		return $wrk_msg;
	}
			
	function Display_Cover_Information($prm_cover_id) {
		//This function displays the information for a cover

		$cov = new MN_Cover;
		$covs = new MN_Covers;
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;

		//Get the Cover record
		if (!$cov = $covs->Fetch($prm_cover_id)) {
			$wrk_error = "ERROR: Cover record was not found<br>";
			return $wrk_error;
		}
		
		//Get the Post Office record
		if (!$po = $pos->Fetch($cov->po_id)) {
			$wrk_error = "ERROR: Post Office record was not found<br>";
			return $wrk_error;
		}
		
		$wrk_curr_user_id = $_SESSION["ssn_id"];

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Display Cover Information</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";
		$wrk_msg .= "</table>";
		$wrk_msg .= "<p><br>";
		
		//Build Cover Display
		$wrk_msg .= Build_Cover_Display($prm_cover_id);
		
		//Buttons
		$wrk_button_msg .= "<div class=\"w3-container w3-center\">";
		$wrk_return_URI = $_SESSION["URI"] ;
		$wrk_button_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='$wrk_return_URI#".$cov->id."'\">&nbsp;";
		if (isset($_SESSION["ssn_un"])) {
			$wrk_button_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_edit_cover\" value=\"Edit Cover\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=30&id=".$cov->id."'\">&nbsp;";
			$wrk_button_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_delete\" value=\"Delete Cover\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=40&id=".$cov->id."'\"><br>&nbsp;";
		}
		$wrk_button_msg .= "</div>";
		
		$wrk_msg = str_replace("[buttons]", $wrk_button_msg, $wrk_msg);
		$wrk_msg .= "<br>";
		$wrk_msg .= "</div>";
		
		return $wrk_msg;
	}
	
	function Display_Covers_for_a_Post_Office($prm_po_id) {
		//This function will display cover information for all the covers in the system for a particular Post Office
		
		Write_Log_Record("Display_Covers_for_a_Post_Office");
		
		$cnts = new MN_Counties;
		$po = new MN_Post_Office ;
		$po_next = new MN_Post_Office ;
		$po_prev = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
		$covs = new MN_Covers;
		$cov = new stdClass();

		
		//Get the Post Office record
		if (!$po = $pos->Fetch($prm_po_id)) {
			$wrk_error = "ERROR: Post Office record was not found<br>";
			return $wrk_error;
		}
		
		//Get the Counties/Districts
		$wrk_counties_array = $cnts->Get_Array_Counties();
		
		$wrk_curr_user_id = $_SESSION["ssn_id"];

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Display Cover Information</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">District:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$wrk_counties_array[$po->county]."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";
		$wrk_msg .= "</table>";
		$wrk_msg .= "<p><br>";

		//Build SQL
		$sql = "select id from MN_Covers where po_id = $prm_po_id  order by cancel_date";
		
		//Build list of PO's
		$wrk_cover_array = array();
		
		if ($wrk_cover_array = $covs->Fetch_All($sql)) {

			foreach ($wrk_cover_array as $key=>$cov) {
				//Get the Cover Display for each Cover
				$wrk_msg = str_replace("[buttons]", "", $wrk_msg);
				$wrk_msg .= Build_Cover_Display($cov->id);
				$wrk_msg .= "<p>";
				
			}
		}

		//Buttons
		$wrk_button_msg .= "<div class=\"w3-container w3-center\">";
		
		//Get Previous
		if ($po_prev = $pos->Fetch_Previous_Post_Office_By_Name($po->po_name)) {
			$wrk_button_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_prev\" value=\"Prev. PO\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=17&id=".$po_prev->id."'\">&nbsp;";
		}
		//Return
		$wrk_button_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=10#".$po->id."'\">&nbsp;";
		if ($po_next = $pos->Fetch_Next_Post_Office_By_Name($po->po_name)) {
			$wrk_button_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_next\" value=\"Next PO\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=17&id=".$po_next->id."'\"><br>&nbsp;";
		}
		$wrk_button_msg .= "</div>";
		
		//Replace the Buttons tag
		$wrk_msg = str_replace("[buttons]", $wrk_button_msg, $wrk_msg);
		
		return $wrk_msg;
		
		
	}

	function Display_Covers_for_a_County($prm_county) {
		//This function will display cover information for all the covers in the system for a particular County in Post Office name order
		
		Write_Log_Record("Display_Covers_for_a_County");
		
		$po = new MN_Post_Office ;
		$po_next = new MN_Post_Office ;
		$po_prev = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
		$covs = new MN_Covers;
		$cov = new stdClass();
		$cnts = new MN_Counties;

		$wrk_counties_array = $cnts->Get_Array_Counties();
		
		////Get the Post Office record
		//if (!$po = $pos->Fetch($prm_po_id)) {
			//$wrk_error = "ERROR: Post Office record was not found<br>";
			//return $wrk_error;
		//}
		
		//$wrk_curr_user_id = $_SESSION["ssn_id"];

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Display Covers for ".$wrk_counties_array[$prm_county]."</div>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}

		//Get the Covers for the County
		$wrk_prev_po_name = "";
		$wrk_prev_period = "";
		
		
		$sql = "SELECT a.*, b.id as id_po, b.po_name,b.county,b.period,b.rarity FROM MN_Covers a, `MN_Post_Offices` b WHERE a.po_id=b.id and county = '$prm_county' order by b.po_name asc, b.period asc, a.cancel_date asc";
		if ($wrk_cover_result = $covs->Fetch_All($sql)) {
			foreach ($wrk_cover_result as $key=>$cov) {
		
				if (($wrk_prev_po_name != $cov->po_name) || ($wrk_prev_period != $cov->period)) {
					$wrk_msg .= "<p><br>";
					$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
					$wrk_msg .= "<tr class=\"mws-min-row-height\">";
					$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
					$wrk_msg .= "<td class=\"large-font\">".$cov->po_name."</td>";
					$wrk_msg .= "</tr>";

					$wrk_msg .= "<tr class=\"mws-min-row-height\">";
					$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
					$wrk_msg .= "<td class=\"large-font\">".$cov->period."</td>";
					$wrk_msg .= "</tr>";

					//Rarity Tim
					if (strlen($po->rarity_tim) > 0) {
						$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
					}
					else {
						$wrk_rarity_tim_lit = "";		
					}

					$wrk_msg .= "<tr class=\"mws-min-row-height\">";
					$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
					$wrk_msg .= "<td class=\"large-font\">".$cov->rarity."$wrk_rarity_tim_lit</td>";
					$wrk_msg .= "</tr>";
					$wrk_msg .= "</table>";
					$wrk_msg .= "<p>";
					$wrk_prev_po_name = $cov->po_name;
					$wrk_prev_period = $cov->period;
				}

				//Get the Cover Display for each Cover
				$wrk_msg = str_replace("[buttons]", "", $wrk_msg);
				$wrk_msg .= Build_Cover_Display($cov->id);
				$wrk_msg .= "<p>";
				
			}
		}
				
		////Build SQL
		//$sql = "select id from MN_Covers where po_id = $prm_po_id  order by cancel_date";
		
		////Build list of PO's
		//$wrk_cover_array = array();
		
		//if ($wrk_cover_array = $covs->Fetch_All($sql)) {

			//foreach ($wrk_cover_array as $key=>$cov) {
				////Get the Cover Display for each Cover
				//$wrk_msg = str_replace("[buttons]", "", $wrk_msg);
				//$wrk_msg .= Build_Cover_Display($cov->id);
				//$wrk_msg .= "<p>";
				
			//}
		//}

		//Buttons
		$wrk_button_msg .= "<div class=\"w3-container w3-center\">";
		
		//Return
		$wrk_button_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=62#".$cov->county."'\"><br>&nbsp;";
		$wrk_button_msg .= "</div>";
		
		//Replace the Buttons tag
		$wrk_msg = str_replace("[buttons]", $wrk_button_msg, $wrk_msg);
		
		return $wrk_msg;
		
		
	}

	
	function Build_Cover_Display($prm_cover_id) {
		//This function builds the cover display. It is used from two different screens
		$cov = new MN_Cover;
		$covs = new MN_Covers;

		//Get the Cover record
		if (!$cov = $covs->Fetch($prm_cover_id)) {
			$wrk_error = "ERROR: Cover record was not found<br>";
			return $wrk_error;
		}

		
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<div class=\"w3-cell-row\">";
		
		
		//Destination
		$wrk_msg .= "<div class=\"w3-cell w3-twothird w3-mobile\">";
		$wrk_msg .= "<br>";
		$wrk_msg .= "<label class=\"w3-text-white\"><b>Destination:</b></label>";
		$wrk_msg .= "<div class=\"w3-container w3-text-darkgreen\">";
		if ($cov->destination == "") {
			$wrk_destination_lit = "&nbsp;";
		}
		else {
			$wrk_destination_lit = $cov->destination;
		}
			
		$wrk_msg .= $wrk_destination_lit;
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";
		
		//Cancel Date
		$wrk_msg .= "<div class=\"w3-cell w3-third w3-mobile\">";
		$wrk_msg .= "<br>";
		$wrk_msg .= "<label class=\"w3-text-white\"><b>Cancel Date:</b></label>";
		$wrk_msg .= "<div class=\"w3-container w3-text-darkgreen\">";
		$wrk_msg .= mws_format_date($cov->cancel_date);
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "</div>"; //w3-cell-row
		
		$wrk_msg .= "<div class=\"w3-cell-row\">";
		//Notes:
		$wrk_msg .= "<div class=\"w3-cell w3-twothird w3-mobile\">";
		$wrk_msg .= "<br>";
		$wrk_msg .= "<label class=\"w3-text-white\"><b>Notes:</b></label><br>";
		$wrk_msg .= "<div class=\"w3-container w3-text-darkgreen\">";
		$wrk_msg .= nl2br($cov->description);
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";

		//Flags:
		$wrk_msg .= "<div class=\"w3-cell w3-third w3-mobile\">";
		$wrk_msg .= "<br>";
		$wrk_msg .= "<label class=\"w3-text-white\"><b>Flags:</b></label><br>";
		$wrk_msg .= "<div class=\"w3-container w3-text-darkgreen\">";
		if ($cov->favorite_flag == "Y") {
			$wrk_fav_flag_lit = "Yes";
		}
		else {
			$wrk_fav_flag_lit = "No";
		}
		if ($cov->needs_research_flag == "Y") {
			$wrk_needs_research_lit = "Yes";
		}
		else {
			$wrk_needs_research_lit = "No";
		}
		
		//Label Number:
		$wrk_label_num_lit = "";
		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_label_num_lit = "<br>Label Number: ".$cov->label_num;
		}
		
		$wrk_msg .= "Favorite: $wrk_fav_flag_lit <br>Needs Research: $wrk_needs_research_lit $wrk_label_num_lit";
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "</div>"; //w3-cell-row
		
		$wrk_msg .= "<div class=\"w3-container w3-center\">";
		$wrk_msg .= "<p><br>";	
		$wrk_msg .= "<img class=\"w3-image\" SRC=\"".$cov->picture1."\" >";
		$wrk_msg .= "</div>";
		
		if ($cov->picture2 != "") {
			$wrk_msg .= "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p><br>";	
			$wrk_msg .= "<img class=\"w3-image w3-center\" SRC=\"".$cov->picture2."\" >";
			$wrk_msg .= "</div>";
		}
		
		
		//Buttons
		$wrk_msg .= "[buttons]";
				
		$wrk_msg .= "</div>";
		
		return $wrk_msg;
		
	}

	function Display_Rarity_Factor() {
		//This function displays the Rarity Factor screen
		
		Write_Log_Record("Display_Rarity_Factor");
		
		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Rarity Factor</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
	
		$wrk_msg .= "<div class=\"w3-container w3-line-height-large\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>The approach taken in calculating the Rarity Factor (or Scarcity Index) used on this website has been taken from Richard W. Helbock’s book “United Status Post Offices Volume III – The Upper Midwest”. &nbsp;The Post Office information has been taken from Robin M. Startup's book \"New Zealand Post Offices\", which is an excellent resource for anyone interested in collecting postal cancellations from New Zealand.</p>";
		$wrk_msg .= "<p>Rarity Factors range from 0 to 9. Zero indicates that a post office is still in operation and so a collector can easily acquire an example of cancellation from that post office. This is not to say that cancellations with a Rarity Factor of “0” have no value and a cancellation from the early 19th century can be very collectible even though the post office is still operating today. Rarity factors in the range 1 to 3 indicate Discontinued Post Offices that have closed in recent years or from post offices where large volumes of covers have survived. Rarity Factors in the range 4 – 6 are for quite rare post offices that were open for only a few years or were closed prior to the 1930’s when postmark collection became popular. Rarity Factors in the range 7 – 9 are reserved for very rare cancellations. It is quite possible that cancels with a Rarity Factor of 8 or 9 may not exist as the post offices were open for just a very short time (perhaps just a few months) or were in places that didn’t process a high volume of postal material.</p>";
		$wrk_msg .= "</div>";
			
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\" width=\"25%\">Rarity</th>";
		$wrk_msg .= "<th align=\"left\" width=\"25%\">Range</th>";
		$wrk_msg .= "<th align=\"left\" width=\"25%\">Rarity</th>";
		$wrk_msg .= "<th align=\"left\" width=\"25%\">Range</th>";
		$wrk_msg .= "</tr>";
		
		$wrk_msg .= "<tr class=\"alternate\">";
		$wrk_msg .= "<td>0</td>";
		$wrk_msg .= "<td>Operating</td>";
		$wrk_msg .= "<td>5</td>";
		$wrk_msg .= "<td>$25 to $50</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<td>1</td>";
		$wrk_msg .= "<td>$2 or less</td>";
		$wrk_msg .= "<td>6</td>";
		$wrk_msg .= "<td>$50 to $100</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"alternate\">";
		$wrk_msg .= "<td>2</td>";
		$wrk_msg .= "<td>$2 to $8</td>";
		$wrk_msg .= "<td>7</td>";
		$wrk_msg .= "<td>$100 to $200</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<td>3</td>";
		$wrk_msg .= "<td>$8 to $15</td>";
		$wrk_msg .= "<td>8</td>";
		$wrk_msg .= "<td>$200 to $500</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"alternate\">";
		$wrk_msg .= "<td>4</td>";
		$wrk_msg .= "<td>$15 to $25</td>";
		$wrk_msg .= "<td>9</td>";
		$wrk_msg .= "<td>Over $500</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "</table>";
		$wrk_msg .= "</div>";
		$wrk_msg .= "<br>";
		
		return $wrk_msg;
	}
	
	function List_Rare_Post_Offices() {
		//This function list Post Offices that have a Rarity Factor of 4 or greater

		Write_Log_Record("List_Rare_Post_Offices");
		
		$cnts = new MN_Counties;
		$wrk_counties_array = $cnts->Get_Array_Counties();

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">List of Rare Post Offices</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
	
		$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>Post Offices shown in this list are included because they have a Rarity Factor of 4 or greater.  To learn more about the Rarity Factor click <a href=\"/?a=20\">here</a>.</p>";
		$wrk_msg .= "</div>";
			
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\" width=\"150px\">Post Office Name</th>";
		$wrk_msg .= "<th align=\"left\" width=\"100px\">District</th>";
		$wrk_msg .= "<th align=\"left\" width=\"100px\">Period</th>";
		$wrk_msg .= "<th align=\"left\" width=\"80px\">Rarity</th>";
		$wrk_msg .= "</tr>";

		//Build SQL
		$sql = "select * from MN_Post_Offices where rarity > 4 order by rarity desc, po_name asc";
		
		//Build list of PO's
		$po = new stdClass();
		$pos = new MN_Post_Offices;
		$wrk_po_array = array();
		
		if ($wrk_po_array = $pos->Fetch_All($sql)) {
			$wrk_alt_flag = "Y";
			foreach ($wrk_po_array as $key=>$po) {
				if ($wrk_alt_flag == "Y") {
					$wrk_alt_lit = " class=\"alternate\"";
					$wrk_alt_flag = "N";
				}
				else {
					$wrk_alt_lit = " class=\"alternate2\"";
					$wrk_alt_flag = "Y";
				}
					
				$wrk_msg .= "<tr$wrk_alt_lit>";
				//setup the Cover Icon
				$wrk_msg .= "<td>".$po->po_name."</td>";
				$wrk_msg .= "<td>".$wrk_counties_array[$po->county]."</td>";
				$wrk_msg .= "<td>".$po->period."</td>";
				$wrk_msg .= "<td>".$po->rarity."</td>";
				$wrk_msg .= "</tr>";
				
			}
		}
		else {
			$wrk_msg .= "<tr>";
			$wrk_msg .= "<td colspan=4>There are no Post Offices found in the system that have a Rarity Factor greater than 4<br></td>";
			$wrk_msg .= "</tr>";
		}
		
		$wrk_msg .= "</table>";
		//Buttons
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Return\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "</div>";

		
		
		
		//Close Div
		$wrk_msg .= "</div>";
		
		return $wrk_msg;
		
		
	}

	function List_Earliest_Covers() {
		//This function lists 50 oldest covers in the system

		Write_Log_Record("List_Earliest_Covers");

		Set_Request_URI();
		
		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">List of Earliest Covers</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
	
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>This screen lists the 70 earliest covers that are held in the collection.</p>";
		$wrk_msg .= "</div>";
			
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\" width=\"25%\">Post Office Name</th>";
		$wrk_msg .= "<th align=\"left\" width=\"45%\">Destination</th>";
		$wrk_msg .= "<th class=\"w3-center\" width=\"15%\">Rarity</th>";
		$wrk_msg .= "<th align=\"left\" width=\"15%\">Cancel Date</th>";
		$wrk_msg .= "</tr>";

		//Build SQL
		$sql = "select a.id, a.destination, a.cancel_date, a.po_id, a.picture1_tn, b.po_name, DATE_FORMAT(a.cancel_date,'%m/%d/%Y') as canceldate, b.rarity  from MN_Covers a left join MN_Post_Offices b on a.po_id=b.id order by cancel_date asc limit 70";
		
		//Build list of PO's
		$cv = new stdClass();
		$pos = new MN_Post_Offices;
		$wrk_cv_array = array();
		
		if ($wrk_cv_array = $pos->Fetch_All($sql)) {
			$wrk_alt_flag = "Y";
			foreach ($wrk_cv_array as $key=>$cv) {
				if ($wrk_alt_flag == "Y") {
					$wrk_alt_lit = " class=\"alternate\"";
					$wrk_alt_flag = "N";
				}
				else {
					$wrk_alt_lit = " class=\"alternate2\"";
					$wrk_alt_flag = "Y";
				}
					
				$wrk_msg .= "<tr$wrk_alt_lit>";
				//setup the Cover Icon
				$wrk_msg .= "<td>".$cv->po_name."</td>";
				$wrk_msg .= "<td><a name=\"".$cv->id."\" href=\"/?a=16&id=".$cv->id."\" >".$cv->destination."</a></td>";
				//$wrk_msg .= "<td>".mws_format_date($cv->cancel_date)."</td>";
				$wrk_msg .= "<td class=\"w3-center\">".$cv->rarity."</td>";
				$wrk_msg .= "<td align=\"right\">".$cv->canceldate."</td>";
				$wrk_msg .= "</tr>";
				
			}
		}
		else {
			$wrk_msg .= "<tr>";
			$wrk_msg .= "<td colspan=4>There are no Cover found in the system<br></td>";
			$wrk_msg .= "</tr>";
		}
		
		$wrk_msg .= "</table>";
		//Buttons
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Return\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "</div>";

		
		
		
		//Close Div
		$wrk_msg .= "</div>";
		
		return $wrk_msg;
		
		
	}

	function List_Recently_Added_Covers() {
		//This function lists the 50 most recently added covers to the system

		Write_Log_Record("List_Recently_Added_Covers");
		
		Set_Request_URI();
		
		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Recently Added Covers</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
	
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p>This screen lists the 50 latest covers added to the collection.</p>";
		$wrk_msg .= "</div>";
			
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 900px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\" width=\"15%\">Date Updated</th>";
		$wrk_msg .= "<th align=\"left\" width=\"22%\">Post Office Name</th>";
		$wrk_msg .= "<th align=\"left\" width=\"35%\">Destination</th>";
		$wrk_msg .= "<th class=\"w3-center\" width=\"14%\">Rarity</th>";
		$wrk_msg .= "<th align=\"left\" >Cancel Date</th>";
		$wrk_msg .= "</tr>";

		//Build SQL
		$sql = "select a.id, a.destination, a.cancel_date, a.po_id, b.rarity, b.po_name, DATE_FORMAT(a.lastmod_datetime,'%m/%d/%Y') as lastmod_datetime from MN_Covers a left join MN_Post_Offices b on a.po_id=b.id order by a.lastmod_datetime desc limit 50";
		
		//Build list of PO's
		$cv = new stdClass();
		$pos = new MN_Post_Offices;
		$wrk_cv_array = array();
		
		if ($wrk_cv_array = $pos->Fetch_All($sql)) {
			$wrk_alt_flag = "Y";
			foreach ($wrk_cv_array as $key=>$cv) {
				if ($wrk_alt_flag == "Y") {
					$wrk_alt_lit = " class=\"alternate\"";
					$wrk_alt_flag = "N";
				}
				else {
					$wrk_alt_lit = " class=\"alternate2\"";
					$wrk_alt_flag = "Y";
				}
					
				$wrk_msg .= "<tr$wrk_alt_lit>";
				//setup the Cover Icon
				$wrk_msg .= "<td align=\"right\">".$cv->lastmod_datetime."</td>";
				$wrk_msg .= "<td>".$cv->po_name."</td>";
				$wrk_msg .= "<td><a name=\"".$cv->id."\" href=\"/?a=16&id=".$cv->id."\" >".$cv->destination."</a></td>";
				//$wrk_msg .= "<td>".mws_format_date($cv->cancel_date)."</td>";
				$wrk_msg .= "<td class=\"w3-center\">".$cv->rarity."</td>";
				$wrk_msg .= "<td align=\"right\">".mws_format_date($cv->cancel_date)."</td>";
				$wrk_msg .= "</tr>";
				
			}
		}
		else {
			$wrk_msg .= "<tr>";
			$wrk_msg .= "<td colspan=4>There are no Cover found in the system<br></td>";
			$wrk_msg .= "</tr>";
		}
		
		$wrk_msg .= "</table>";
		//Buttons
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Return\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "</div>";

		
		
		
		//Close Div
		$wrk_msg .= "</div>";
		
		return $wrk_msg;
		
		
	}

	function List_My_Favorites() {
		//This function lists the 50 most recently added covers to the system

		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		
		Set_Request_URI();
		
		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">My Favorite Covers</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
	
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 900px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\">Post Office Name</th>";
		$wrk_msg .= "<th align=\"left\">Destination</th>";
		$wrk_msg .= "<th align=\"left\">Cancel Date</th>";
		$wrk_msg .= "<th align=\"left\">Rarity</th>";
		$wrk_msg .= "</tr>";

		//Build SQL
		$sql = "select a.id, a.destination, a.cancel_date, b.po_name, b.rarity from MN_Covers a left join MN_Post_Offices b on a.po_id=b.id where a.user_id = $wrk_curr_user_id and favorite_flag = 'Y' order by b.po_name asc";
		
		//Build list of PO's
		$cv = new stdClass();
		$pos = new MN_Post_Offices;
		$wrk_cv_array = array();
		
		if ($wrk_cv_array = $pos->Fetch_All($sql)) {
			$wrk_alt_flag = "Y";
			foreach ($wrk_cv_array as $key=>$cv) {
				if ($wrk_alt_flag == "Y") {
					$wrk_alt_lit = " class=\"alternate\"";
					$wrk_alt_flag = "N";
				}
				else {
					$wrk_alt_lit = " class=\"alternate2\"";
					$wrk_alt_flag = "Y";
				}
					
				$wrk_msg .= "<tr$wrk_alt_lit>";
				//setup the Cover Icon
				$wrk_msg .= "<td>".$cv->po_name."</td>";
				$wrk_msg .= "<td><a name=\"".$cv->id."\" href=\"/?a=16&id=".$cv->id."\" >".$cv->destination."</a></td>";
				$wrk_msg .= "<td align=\"right\">".mws_format_date($cv->cancel_date)."</td>";
				$wrk_msg .= "<td class=\"w3-center\">".$cv->rarity."</td>";
				$wrk_msg .= "</tr>";
				
			}
		}
		else {
			$wrk_msg .= "<tr>";
			$wrk_msg .= "<td colspan=4>There are no Cover found in the system<br></td>";
			$wrk_msg .= "</tr>";
		}
		
		$wrk_msg .= "</table>";
		//Buttons
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Return\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "</div>";
		
		//Close Div
		$wrk_msg .= "</div>";
		
		return $wrk_msg;		
	}

	function List_Of_Covers_Needing_Research() {
		//This function lists the covers that are marked as needing further research

		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		
		Set_Request_URI();
		
		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Covers that need further Research</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
	
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 900px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\">Post Office Name</th>";
		$wrk_msg .= "<th align=\"left\">Destination</th>";
		$wrk_msg .= "<th align=\"left\">Cancel Date</th>";
		$wrk_msg .= "<th align=\"left\">Rarity</th>";
		$wrk_msg .= "</tr>";

		//Build SQL
		$sql = "select a.id, a.destination, a.cancel_date, a.po_id, b.po_name, b.rarity from MN_Covers a left join MN_Post_Offices b on a.po_id=b.id where a.user_id = $wrk_curr_user_id and needs_research_flag = 'Y' order by b.po_name asc";
		
		//Build list of PO's
		$cv = new stdClass();
		$pos = new MN_Post_Offices;
		$wrk_cv_array = array();
		
		if ($wrk_cv_array = $pos->Fetch_All($sql)) {
			$wrk_alt_flag = "Y";
			foreach ($wrk_cv_array as $key=>$cv) {
				if ($wrk_alt_flag == "Y") {
					$wrk_alt_lit = " class=\"alternate\"";
					$wrk_alt_flag = "N";
				}
				else {
					$wrk_alt_lit = " class=\"alternate2\"";
					$wrk_alt_flag = "Y";
				}
					
				$wrk_msg .= "<tr$wrk_alt_lit>";
				//setup the Cover Icon
				$wrk_msg .= "<td>".$cv->po_name."</td>";
				$wrk_msg .= "<td><a name=\"".$cv->id."\" href=\"/?a=16&id=".$cv->id."\" >".$cv->destination."</a></td>";
				$wrk_msg .= "<td align=\"right\">".mws_format_date($cv->cancel_date)."</td>";
				$wrk_msg .= "<td class=\"w3-center\">".$cv->rarity."</td>";
				$wrk_msg .= "</tr>";
				
			}
		}
		else {
			$wrk_msg .= "<tr>";
			$wrk_msg .= "<td colspan=4>There are no Cover found in the system<br></td>";
			$wrk_msg .= "</tr>";
		}
		
		$wrk_msg .= "</table>";
		//Buttons
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Return\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "</div>";
		
		//Close Div
		$wrk_msg .= "</div>";
		
		return $wrk_msg;		
	}
	
	function Edit_Cover_Display($prm_cover_id, $prm_error) {
		//This function provides the display for editing a Cover
		
		$cov = new MN_Cover ;
		$covs = new MN_Covers ;
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		
		//Get the Cover
		if (!$cov =  $covs->Fetch($prm_cover_id)) {
			$wrk_error = "The cover was not found in the Edit Cover Display screen<br>";
			return $wrk_error;
		}

		//Get the Cover
		if (!$po =  $pos->Fetch($cov->po_id)) {
			$wrk_error = "The Post Office was not found in the Edit Cover Display screen<br>";
			return $wrk_error;
		}
		
		//Get the data from the input screen
		if (isset($_POST["pst_save"])) {
			$wrk_destination = stripslashes($_POST["pst_destination"]);
			$wrk_cancel_date = stripslashes($_POST["pst_cancel_date"]);
			$wrk_label_num = stripslashes($_POST["pst_label_num"]);
			$wrk_description = stripslashes($_POST["pst_description"]);
			if (isset($_POST["pst_favorite_flag"])) {
				$wrk_favorite_flag = "Y";
			}
			else {
				$wrk_favorite_flag = "";
			}
			if (isset($_POST["pst_needs_research_flag"])) {
				$wrk_needs_research_flag = "Y";
			}
			else {
				$wrk_needs_research_flag = "";
			}
			
			
		}
		else {
			$wrk_destination = $cov->destination;
			$wrk_cancel_date = mws_format_date($cov->cancel_date);
			$wrk_label_num = $cov->label_num;
			$wrk_description = $cov->description;
			$wrk_favorite_flag = $cov->favorite_flag;
			$wrk_needs_research_flag = $cov->needs_research_flag;
		}
		$wrk_image_1 = $cov->picture1_tn;
		$wrk_image_2 = 	$cov->picture2_tn;

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Edit a Cover</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";
		$wrk_msg .= "</table><br>";

		//Display Errors
		if ($prm_error != "") {
			$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 800px; margin:auto; \">";
			$wrk_msg .= Display_Errors($prm_error) ;
			$wrk_msg .= "</div>";
		}
		
	
		//Paint Screen
		$wrk_msg .= "<form action=\"/\" method=\"post\">";
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 800px; margin:auto; \">";
		
		//Destination
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Destination:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_destination\" name=\"pst_destination\" value=\"$wrk_destination\" style=\"max-width: 700px; \">";
		$wrk_msg .= "</div>";

		$wrk_msg .= "<p>";
		$wrk_msg .= "<div class=\"w3-cell-row\">";
		
		//Cancel Date
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_msg .= "<label><b>Cancel Date:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_cancel_date\" name=\"pst_cancel_date\" value=\"$wrk_cancel_date\" style=\"max-width: 300px; \">";
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";

		//Label Number
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_msg .= "<label><b>Label Number:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_label_num\" name=\"pst_label_num\" value=\"$wrk_label_num\" style=\"max-width: 250px; \">";
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";

		$wrk_msg .= "</div>";

		//Description
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Description:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<textarea class=\"w3-input\" id=\"pst_description\" name=\"pst_description\"  rows=\"10\" style=\"max-width: 700px; \">$wrk_description</textarea>";
		$wrk_msg .= "</div>";

		
		//Load Image Cell Row

		$wrk_msg .= "<p><br>";
		$wrk_msg .= "<label><b>Load Images:</b></label>";
		
		$wrk_msg .= "<div class=\"w3-cell-row\">";		
		//Image 1
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Upload\" value=\"Upload Picture 1\" onclick=\"javascript:document.location.href='/?a=50&id=$prm_cover_id&pic=1'\">";
		if ($wrk_image_1 != "") {	
			$wrk_msg .= "<br><br><a class=\"w3-image\" title=\"Click on Image to return to Cover Screen\" href=\"".$cov->picture1."\" target=\"__blank\"><img class=\"w3-image\" src=\"$wrk_image_1\" alt=\"Picture1 Thumbnail\" /></a><br>";
		}
		$wrk_msg .= "</div>";  //End Container
		$wrk_msg .= "</div>"; //End Cell

		//Image 2
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Upload\" value=\"Upload Picture 2\" onclick=\"javascript:document.location.href='/?a=50&id=$prm_cover_id&pic=2'\">";
		if ($wrk_image_2 != "") {	
			$wrk_msg .= "<br><br><a class=\"w3-image\" title=\"Click on Image to return to Cover Screen\" href=\"".$cov->picture2."\" target=\"__blank\"><img src=\"$wrk_image_2\" alt=\"Picture2 Thumbnail\" /></a><br>";
		}
		$wrk_msg .= "</div>";  //End Container
		$wrk_msg .= "</div>"; //End Cell
		$wrk_msg .= "</div>";  //End Cell Row

		//Flags
		$wrk_msg .= "<p><br>";
		$wrk_msg .= "<label><b>Flags:</b></label>";
		
		$wrk_msg .= "<div class=\"w3-cell-row\">";
		
		//Favorite Flag
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_favorite_flag_lit = "";
		if ($wrk_favorite_flag == "Y") {
			$wrk_favorite_flag_lit = "checked";
		}
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-check\" type=\"checkbox\" id=\"pst_favorite_flag\" name=\"pst_favorite_flag\" $wrk_favorite_flag_lit>";
		$wrk_msg .= "&nbsp;<label><b>Favorite Flag:</b></label>";
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "</div>"; //End Cell

		//Needs Research Flag
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_needs_research_flag_lit = "";
		if ($wrk_needs_research_flag == "Y") {
			$wrk_needs_research_flag_lit = "checked";
		}
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-check\" type=\"checkbox\" id=\"pst_needs_research_flag\" name=\"pst_needs_research_flag\" $wrk_needs_research_flag_lit>";
		$wrk_msg .= "&nbsp;<label><b>Needs Research Flag:</b></label> ";

		$wrk_msg .= "</div>";
		
		$wrk_msg .= "</div>"; //End Cell
		$wrk_msg .= "</div>";  //End Cell Row
		
		//Buttons
		$wrk_msg .= "<p>";
		$wrk_msg .= "<div class=\"w3-container w3-center\" >";
		$wrk_msg .= "<input type=\"hidden\" id=\"step\" name=\"step\" value=\"C1\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_id\" name=\"pst_id\" value=\"".$cov->id."\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_po_id\" name=\"pst_po_id\" value=\"".$cov->po_id."\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_user_id\" name=\"pst_user_id\" value=\"".$cov->user_id."\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=15&po=".$cov->po_id."'\">&nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" name=\"pst_save\" value=\"Save\" style=\"cursor:pointer; margin:0;\">";
		$wrk_msg .= "</div><br>";
		
		//End Paint Screen
		$wrk_msg .= "</div>";
		$wrk_msg .= "</form>";
		
		
		return $wrk_msg;
		
	}
	
		function Edit_Cover_Process() {
		//This function will process the Cover that is being Added
		
		$cvr = new MN_Cover ;
		$cvrs = new MN_Covers;

		if ($cvr = $cvrs->Fetch($_POST["pst_id"])) {

			//Retrieve fields from the screen
			$cvr->destination = stripslashes($_POST["pst_destination"]);
			$cvr->description = stripslashes($_POST["pst_description"]);
			$cvr->cancel_date = $_POST["pst_cancel_date"];
			$cvr->label_num = trim($_POST["pst_label_num"]);
			$cvr->po_id = $_POST["pst_po_id"];
			$cvr->user_id = $_POST["pst_user_id"];
			$cvr->id = $_POST["pst_id"];
			if (isset($_POST["pst_favorite_flag"])) {
				$cvr->favorite_flag = "Y";
			}
			else {
				$cvr->favorite_flag = "";
			}
			if (isset($_POST["pst_needs_research_flag"])) {
				$cvr->needs_research_flag = "Y";
			}
			else {
				$cvr->needs_research_flag = "";
			}
				
			//Validate the data
			$errors = $cvrs->Validate($cvr);
			if ($errors != "") {
				return Edit_Cover_Display($cvr->id, $errors);
			}
			else {
				//All is good, create the Cover record
				$cvr->cancel_date = mws_reformat_date($cvr->cancel_date);
				$cvr->lastmod_datetime = date("Y-m-d H-i-s") ;
				if (!$cvrs->Update($cvr)) {
					return "ERROR: ".$cvrs->error;
				}
				else {
					return List_Of_Covers_For_Post_Office($cvr->po_id);
				}
			}
		}
		else {
			return "ERROR: Cover # ".$_POST["mws_id"]." was not found when processing the Cover Edit Page.<br>";
		}
	}

	function Add_Cover_Display($prm_po_id, $prm_error) {
		//This function provides the display for adding a Cover
		
		$cov = new MN_Cover ;
		$covs = new MN_Covers ;
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		

		//Get the Cover
		if (!$po =  $pos->Fetch($prm_po_id)) {
			$wrk_error = "The Post Office was not found in the Edit Cover Display screen<br>";
			return $wrk_error;
		}
		
		//Get the data from the input screen
		if (isset($_POST["pst_next"])) {
			$wrk_destination = stripslashes($_POST["pst_destination"]);
			$wrk_cancel_date = stripslashes($_POST["pst_cancel_date"]);
			$wrk_label_num = stripslashes($_POST["pst_label_num"]);
			$wrk_description = stripslashes($_POST["pst_description"]);
			if (isset($_POST["pst_favorite_flag"])) {
				$wrk_favorite_flag = "Y";
			}
			else {
				$wrk_favorite_flag = "";
			}
			if (isset($_POST["pst_needs_research_flag"])) {
				$wrk_needs_research_flag = "Y";
			}
			else {
				$wrk_needs_research_flag = "";
			}
			
			
		}
		else {
			$wrk_destination = "";
			$wrk_cancel_date = "";
			$wrk_label_num = "";
			$wrk_description = "";
			$wrk_favorite_flag = "";
			$wrk_needs_research_flag = "";
		}

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Add a Cover</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";
		$wrk_msg .= "</table><br>";
		
		if ($prm_error != "") {
			$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 800px; margin:auto; \">";
			$wrk_msg .= Display_Errors($prm_error) ;
			$wrk_msg .= "</div>";
		}

	
		//Paint Screen
		$wrk_msg .= "<form action=\"/\" method=\"post\">";
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 800px; margin:auto; \">";
		
		//Destination
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Destination:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_destination\" name=\"pst_destination\" value=\"$wrk_destination\" style=\"max-width: 700px; \">";
		$wrk_msg .= "</div>";

		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<div class=\"w3-cell-row\">";
		
		//Cancel Date
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_msg .= "<label><b>Cancel Date:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_cancel_date\" name=\"pst_cancel_date\" value=\"$wrk_cancel_date\" style=\"max-width: 250px; \">";
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";

		//Label Number
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_msg .= "<label><b>Label Number:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_label_num\" name=\"pst_label_num\" value=\"$wrk_label_num\" style=\"max-width: 250px; \">";
		$wrk_msg .= "</div>";
		$wrk_msg .= "</div>";

		$wrk_msg .= "</div>";

		//Description
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Description:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<textarea class=\"w3-input\" id=\"pst_description\" name=\"pst_description\"  rows=\"10\" style=\"max-width: 700px; \">$wrk_description</textarea>";
		$wrk_msg .= "</div>";

		
		//Flags
		$wrk_msg .= "<p><br>";
		$wrk_msg .= "<label><b>Flags:</b></label>";
		
		$wrk_msg .= "<div class=\"w3-cell-row\">";
		
		//Favorite Flag
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_favorite_flag_lit = "";
		if ($wrk_favorite_flag == "Y") {
			$wrk_favorite_flag_lit = "checked";
		}
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-check\" type=\"checkbox\" id=\"pst_favorite_flag\" name=\"pst_favorite_flag\" $wrk_favorite_flag_lit>";
		$wrk_msg .= "&nbsp;<label><b>Favorite Flag:</b></label>";
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "</div>"; //End Cell

		//Needs Research Flag
		$wrk_msg .= "<div class=\"w3-cell w3-half w3-mobile\">";
		$wrk_needs_research_flag_lit = "";
		if ($wrk_needs_research_flag == "Y") {
			$wrk_needs_research_flag_lit = "checked";
		}
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-check\" type=\"checkbox\" id=\"pst_needs_research_flag\" name=\"pst_needs_research_flag\" $wrk_needs_research_flag_lit>";
		$wrk_msg .= "&nbsp;<label><b>Needs Research Flag:</b></label> ";

		$wrk_msg .= "</div>";
		
		$wrk_msg .= "</div>"; //End Cell
		$wrk_msg .= "</div>";  //End Cell Row
		
		//Buttons
		$wrk_msg .= "<p>";
		$wrk_msg .= "<div class=\"w3-container w3-center\" >";
		$wrk_msg .= "<input type=\"hidden\" id=\"step\" name=\"step\" value=\"A1\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_po_id\" name=\"pst_po_id\" value=\"".$prm_po_id."\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_user_id\" name=\"pst_user_id\" value=\"".$wrk_curr_user_id."\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=15&po=".$prm_po_id."'\">&nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" name=\"pst_next\" value=\"Next\" style=\"cursor:pointer; margin:0;\">";
		$wrk_msg .= "</div><br>";
		
		//End Paint Screen
		$wrk_msg .= "</div>";
		$wrk_msg .= "</form>";
		
		
		return $wrk_msg;
		
	}
	
	function Add_Cover_Process() {
		//This function will process the Cover that is being Added
		
		$cvr = new MN_Cover ;
		$cvrs = new MN_Covers;

		//Retrieve fields from the screen
		$cvr->destination = stripslashes($_POST["pst_destination"]);
		$cvr->description = stripslashes($_POST["pst_description"]);
		$cvr->cancel_date = $_POST["pst_cancel_date"];
		$cvr->label_num = trim($_POST["pst_label_num"]);
		$cvr->po_id = $_POST["pst_po_id"];
		$cvr->user_id = $_POST["pst_user_id"];
		if (isset($_POST["pst_favorite_flag"])) {
			$cvr->favorite_flag = "Y";
		}
		else {
			$cvr->favorite_flag = "";
		}
		if (isset($_POST["pst_needs_research_flag"])) {
			$cvr->needs_research_flag = "Y";
		}
		else {
			$cvr->needs_research_flag = "";
		}
		
		//Validate the data
		$errors = $cvrs->Validate($cvr);
		if ($errors != "") {
			return Add_Cover_Display($cvr->po_id, $errors);
		}
		else {
			//All is good, create the Cover record
			$cvr->cancel_date = mws_reformat_date($cvr->cancel_date);
			$cvr->lastmod_datetime = date("Y-m-d H-i-s") ;
			if (!$cvrs->Create($cvr)) {
				return "ERROR: ".$cvrs->error;
			}
			else {
				$prm_picture_num = 1;
				return Upload_Image_Display ($cvr, $prm_picture_num);
			}
		}
	}

	function Delete_Cover_Display($prm_cover_id) {
		//This function provides the display for deleting a Cover
		
		$cov = new MN_Cover ;
		$covs = new MN_Covers ;
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		
		//Get the Cover
		if (!$cov =  $covs->Fetch($prm_cover_id)) {
			$wrk_error = "The cover was not found in the Edit Cover Display screen<br>";
			return $wrk_error;
		}

		//Get the Cover
		if (!$po =  $pos->Fetch($cov->po_id)) {
			$wrk_error = "The Post Office was not found in the Edit Cover Display screen<br>";
			return $wrk_error;
		}
		

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Delete a Cover</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Destination:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$cov->destination."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "</table><br>";

		
	
		//Paint Screen
		$wrk_msg .= "<form action=\"/\" method=\"post\">";
		$wrk_msg .= "<div class=\"w3-container \" style=\"max-width: 800px; margin:auto; \">";
		
		
		//Buttons
		$wrk_msg .= "<p>";
		$wrk_msg .= "<div class=\"w3-container w3-center\" >";
		$wrk_msg .= "<input type=\"hidden\" id=\"step\" name=\"step\" value=\"D1\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_id\" name=\"pst_id\" value=\"".$cov->id."\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=15&po=".$cov->po_id."'\">&nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" name=\"pst_delete\" value=\"Confirm Deletion\" style=\"cursor:pointer; margin:0;\">";
		$wrk_msg .= "</div><br>";
		
		//End Paint Screen
		$wrk_msg .= "</div>";
		$wrk_msg .= "</form>";
		
		
		return $wrk_msg;
		
	}
	
	function Delete_Cover_Process($prm_cover_id) {
		//This function provides the process for deleting a Cover
		
		$cov = new MN_Cover ;
		$covs = new MN_Covers ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}

		//Get the Cover
		if (!$cov =  $covs->Fetch($prm_cover_id)) {
			$wrk_error = "The cover was not found in the Edit Cover Display screen<br>";
			return $wrk_error;
		}


		//delete the Cover
		if (!$covs->Delete($prm_cover_id)) {
			return "ERROR: ".$cvrs->error;
		}
		else {
			$prm_picture_num = 1;
			return List_Of_Covers_For_Post_Office($cov->po_id);
		}
	}

	function Add_Post_Office_Display($prm_error) {
		//This function provides the display for adding a Cover
		
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		

		//Get the data from the input screen
		if (isset($_POST["pst_submit"])) {
			$wrk_po_name = stripslashes($_POST["pst_po_name"]);
			$wrk_county = stripslashes($_POST["pst_county"]);
			$wrk_period = stripslashes($_POST["pst_period"]);
			$wrk_notes = stripslashes($_POST["pst_notes"]);
			$wrk_rarity = $_POST["pst_rarity"];			
			
		}
		else {
			$wrk_po_name = "";
			$wrk_county = "";
			$wrk_period = "";
			$wrk_notes = "";
			$wrk_rarity = "";
		}

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Add a Post Office</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		
		if ($prm_error != "") {
			$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 800px; margin:auto; \">";
			$wrk_msg .= Display_Errors($prm_error) ;
			$wrk_msg .= "</div>";
		}

	
		//Paint Screen
		$wrk_msg .= "<form action=\"/\" method=\"post\">";
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 800px; margin:auto; \">";
		
		//Post Office Name
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Post Office Name:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_po_name\" name=\"pst_po_name\" value=\"$wrk_po_name\" style=\"max-width: 700px; \">";
		$wrk_msg .= "</div>";

		//County
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>County:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_county\" name=\"pst_county\" value=\"$wrk_county\" style=\"max-width: 500px; \">";
		$wrk_msg .= "</div>";

		//Period
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Period:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_period\" name=\"pst_period\" value=\"$wrk_period\" style=\"max-width: 500px; \">";
		$wrk_msg .= "</div>";

		//Rarity
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Rarity:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_rarity\" name=\"pst_rarity\" value=\"$wrk_rarity\" style=\"max-width: 100px; \">";
		$wrk_msg .= "</div>";

		//Notes
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Notes:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<textarea class=\"w3-input\" id=\"pst_notes\" name=\"pst_notes\"  rows=\"10\" style=\"max-width: 700px; \">$wrk_notes</textarea>";
		$wrk_msg .= "</div>";

		
		
		//Buttons
		$wrk_msg .= "<p>";
		$wrk_msg .= "<div class=\"w3-container w3-center\" >";
		$wrk_msg .= "<input type=\"hidden\" id=\"step\" name=\"step\" value=\"P1\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_po_id\" name=\"pst_po_id\" value=\"".$prm_po_id."\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_user_id\" name=\"pst_user_id\" value=\"".$wrk_curr_user_id."\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" name=\"pst_submit\" value=\"Submit\" style=\"cursor:pointer; margin:0;\">";
		$wrk_msg .= "</div><br>";
		
		//End Paint Screen
		$wrk_msg .= "</div>";
		$wrk_msg .= "</form>";
		
		
		return $wrk_msg;
		
	}

	function Add_Post_Office_Process() {
		//This function provides the process for adding a Cover
		
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		

		//Get the data from the input screen
		if (isset($_POST["pst_submit"])) {
			$wrk_po_name = stripslashes($_POST["pst_po_name"]);
			$wrk_county = stripslashes($_POST["pst_county"]);
			$wrk_period = stripslashes($_POST["pst_period"]);
			$wrk_notes = stripslashes($_POST["pst_notes"]);
			$wrk_rarity = $_POST["pst_rarity"];			
			
		}
		
		//Validate Input
		$wrk_error = "";
		if (trim($wrk_po_name) == "") {
			$wrk_error .= "<li>The Post Office Name can not be blank</li>";
		}
		else {
			if ($wrk_invalid_char = strpos($wrk_po_name, "<")) {
				$wrk_error .= "<li>The Post Office Name contains invalid characters</li>";
			}	
			elseif ($po = $pos->Fetch_By_PO_Name ($wrk_po_name)) {
				$wrk_error .= "<li>The Post Office Name already exists</li>";
			}
		}
		
		if (trim($wrk_county) == "") {
			$wrk_error .= "<li>The County can not be blank</li>";
		}	
		else {
			if ($wrk_invalid_char = strpos($wrk_county, "<")) {
				$wrk_error .= "<li>The County contains invalid characters</li>";
			}
		}	

		if (trim($wrk_period) == "") {
			$wrk_error .= "<li>The Period can not be blank</li>";
		}	
		else {
			if ($wrk_invalid_char = strpos($wrk_period, "<")) {
				$wrk_error .= "<li>The Period contains invalid characters</li>";
			}
		}	

		if ($wrk_invalid_char = strpos($wrk_notes, "<")) {
			$wrk_error .= "<li>The notes contains invalid characters</li>";
		}

		if (trim($wrk_rarity) == "") {
			$wrk_error .= "<li>The Rarity can not be blank</li>";
		}
		elseif (!is_numeric($wrk_rarity)) {
			$wrk_error .= "<li>The Rarity must be numeric between 0 and 9</li>";
		}	
		elseif ($wrk_rarity > 9) {
			$wrk_error .= "<li>The Rarity must be numeric between 0 and 9</li>";
		}	
		
		if ($wrk_error != "") {
			return Add_Post_Office_Display($wrk_error);
		}
		else {
			//All is OK, create the Post Office record
			$po = new MN_Post_Office;
			$po->po_name = $wrk_po_name;
			$po->county = $wrk_county;
			$po->period = $wrk_period;
			$po->rarity = $wrk_rarity;
			$po->notes = $wrk_notes;
			if(!$pos->Create($po)) {
				$wrk_msg = "ERROR: Create of Post Office record failed. ".$pos->error."<br>";
				return $wrk_msg;
			}
			else {
				$wrk_msg = Display_Home_Page();
				return $wrk_msg;
			}
		}
	}			

	function Edit_Post_Office_Display($prm_po_id, $prm_error) {
		//This function provides the display for Editing a Post Office
		
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		

		//Get the data from the input screen
		if (isset($_POST["pst_submit"])) {
			$wrk_po_name = stripslashes($_POST["pst_po_name"]);
			$wrk_county = stripslashes($_POST["pst_county"]);
			$wrk_period = stripslashes($_POST["pst_period"]);
			$wrk_notes = stripslashes($_POST["pst_notes"]);
			$wrk_rarity_tim = stripslashes($_POST["pst_rarity_tim"]);
			$wrk_rarity = $_POST["pst_rarity"];			
			
		}
		elseif ($po = $pos->Fetch($prm_po_id)) {
			$wrk_po_name = $po->po_name;
			$wrk_county = $po->county;
			$wrk_period = $po->period;
			$wrk_notes = $po->notes;
			$wrk_rarity = $po->rarity;
			$wrk_rarity_tim = $po->rarity_tim;
		}

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Edit a Post Office</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		
		if ($prm_error != "") {
			$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 800px; margin:auto; \">";
			$wrk_msg .= Display_Errors($prm_error) ;
			$wrk_msg .= "</div>";
		}

	
		//Paint Screen
		$wrk_msg .= "<form action=\"/\" method=\"post\">";
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 800px; margin:auto; \">";
		
		//Post Office Name
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Post Office Name:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_po_name\" name=\"pst_po_name\" value=\"$wrk_po_name\" style=\"max-width: 700px; \">";
		$wrk_msg .= "</div>";

		//County
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>County:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_county\" name=\"pst_county\" value=\"$wrk_county\" style=\"max-width: 500px; \">";
		$wrk_msg .= "</div>";

		//Period
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Period:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_period\" name=\"pst_period\" value=\"$wrk_period\" style=\"max-width: 500px; \">";
		$wrk_msg .= "</div>";

		//Rarity
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Rarity:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_rarity\" name=\"pst_rarity\" value=\"$wrk_rarity\" style=\"max-width: 100px; \">";
		$wrk_msg .= "</div>";

		//Rarity_Tim
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>T:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<input class=\"w3-input\" type=\"text\" id=\"pst_rarity_tim\" name=\"pst_rarity_tim\" value=\"$wrk_rarity_tim\" style=\"max-width: 100px; \">";
		$wrk_msg .= "</div>";

		//Notes
		$wrk_msg .= "<p>";
		$wrk_msg .= "<label><b>Notes:</b></label>";
		$wrk_msg .= "<div class=\"w3-container\">";
		$wrk_msg .= "<textarea class=\"w3-input\" id=\"pst_notes\" name=\"pst_notes\"  rows=\"10\" style=\"max-width: 700px; \">$wrk_notes</textarea>";
		$wrk_msg .= "</div>";

		
		
		//Buttons
		$wrk_msg .= "<p>";
		$wrk_msg .= "<div class=\"w3-container w3-center\" >";
		$wrk_msg .= "<input type=\"hidden\" id=\"step\" name=\"step\" value=\"E1\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_po_id\" name=\"pst_po_id\" value=\"".$prm_po_id."\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_user_id\" name=\"pst_user_id\" value=\"".$wrk_curr_user_id."\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=10'\">&nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" name=\"pst_submit\" value=\"Submit\" style=\"cursor:pointer; margin:0;\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large w3-right\" type=\"button\" name=\"pst_delete_PO\" value=\"Delete PO\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=49&id=$prm_po_id'\">";
		$wrk_msg .= "</div><br>";
		
		//End Paint Screen
		$wrk_msg .= "</div>";
		$wrk_msg .= "</form>";
		
		
		return $wrk_msg;
		
	}
	
	function Edit_Post_Office_Process() {
		//This function provides the process for Editing a Post Office
		
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
				
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		

		//Get the data from the input screen
		if (isset($_POST["pst_submit"])) {
			$wrk_po_id = $_POST["pst_po_id"];
			$wrk_po_name = stripslashes($_POST["pst_po_name"]);
			$wrk_county = stripslashes($_POST["pst_county"]);
			$wrk_period = stripslashes($_POST["pst_period"]);
			$wrk_notes = stripslashes($_POST["pst_notes"]);
			$wrk_rarity_tim = stripslashes($_POST["pst_rarity_tim"]);
			$wrk_rarity = $_POST["pst_rarity"];			
			
		}
		
		
		//Validate Input
		$wrk_error = "";
		if (trim($wrk_po_name) == "") {
			$wrk_error .= "<li>The Post Office Name can not be blank</li>";
		}
		else {
			if ($wrk_invalid_char = strpos($wrk_po_name, "<")) {
				$wrk_error .= "<li>The Post Office Name contains invalid characters</li>";
			}	
		}
		
		if (trim($wrk_county) == "") {
			$wrk_error .= "<li>The County can not be blank</li>";
		}	
		else {
			if ($wrk_invalid_char = strpos($wrk_county, "<")) {
				$wrk_error .= "<li>The County contains invalid characters</li>";
			}
		}	

		if (trim($wrk_period) == "") {
			$wrk_error .= "<li>The Period can not be blank</li>";
		}	
		else {
			if ($wrk_invalid_char = strpos($wrk_period, "<")) {
				$wrk_error .= "<li>The Period contains invalid characters</li>";
			}
		}	

		if ($wrk_invalid_char = strpos($wrk_notes, "<")) {
			$wrk_error .= "<li>The notes contains invalid characters</li>";
		}

		if (trim($wrk_rarity) == "") {
			$wrk_error .= "<li>The Rarity can not be blank</li>";
		}
		elseif (!is_numeric($wrk_rarity)) {
			$wrk_error .= "<li>The Rarity must be numeric between 0 and 9</li>";
		}	
		elseif ($wrk_rarity > 9) {
			$wrk_error .= "<li>The Rarity must be numeric between 0 and 9</li>";
		}	
		
		if ($wrk_error != "") {
			return Edit_Post_Office_Display($wrk_po_id, $wrk_error);
		}
		else {
			//All is OK, update the Post Office record
			$po = new MN_Post_Office;
			if ($po = $pos->Fetch($wrk_po_id)) {
				$po->po_name = $wrk_po_name;
				$po->county = $wrk_county;
				$po->period = $wrk_period;
				$po->rarity = $wrk_rarity;
				$po->rarity_tim = $wrk_rarity_tim;
				$po->notes = $wrk_notes;
				if(!$pos->Update($po)) {
					$wrk_msg = "ERROR: Update of Post Office record failed. ".$pos->error."<br>";
					return $wrk_msg;
				}
				else {
					$prm_error = "";
					$wrk_msg = MN_PO_Lookup($prm_error);
					return $wrk_msg;
				}
			}
		}
	}		
	
	function Delete_Post_Office_Display($prm_po_id) {
		//This function displays the confirmation for a Post Office delete
		$cov = new stdClass();
		$covs = new MN_Covers;
		$po = new MN_Post_Office ;
		$pos = new MN_Post_Offices ;
			
		//Get the Post Office record
		if (!$po = $pos->Fetch($prm_po_id)) {
			$wrk_error = "ERROR: Post Office record was not found<br>";
			return $wrk_error;
		}
		
		$sql = "select count(*) as cnt from MN_Covers where po_id = $prm_po_id";
		$wrk_po_array = array();
		$wrk_cover_count = 0;
		
		if ($wrk_cov_array = $covs->Fetch_All($sql)) {
			foreach ($wrk_cov_array as $key=>$cov) {
				$wrk_cover_count = $cov->cnt;
			}
		}
		
		$wrk_curr_user_id = $_SESSION["ssn_id"];

		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Delete a Post Office</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<form action=\"/\" method=\"post\">";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Cover Count:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$wrk_cover_count."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "</table>";
		
		$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 600px; margin:auto; \">";
		$wrk_msg .= "<br>";
		if ($wrk_cover_count > 0) {
			$wrk_msg .= "<p>This Post Office can not be deleted as there are covers assigned to it.</p>";
			$wrk_conf_button = "";
		}
		else {
			$wrk_conf_button = "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" name=\"pst_delete\" value=\"Confirm PO Delete\" style=\"cursor:pointer; margin:0;\">";
		}
		$wrk_msg .= "</div>";
		
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<input type=\"hidden\" id=\"step\" name=\"step\" value=\"D2\">";
		$wrk_msg .= "<input type=\"hidden\" id=\"pst_po_id\" name=\"pst_po_id\" value=\"".$prm_po_id."\">";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=10'\">&nbsp;$wrk_conf_button";
		
		$wrk_msg .= "</form>";
		$wrk_msg .= "</div>";
		
		return $wrk_msg;
	}		

	function Delete_Post_Office_Process() {
		//This function deletes a Post Office
		
		$pos = new MN_Post_Offices;
		
		$wrk_po_id = $_POST["pst_po_id"];
		if (!$pos->Delete($wrk_po_id)) {
			echo "ERROR: Post Office Delete Failed.".$pos->error;
		}
		else {
			$prm_error = "";
			$wrk_msg = MN_PO_Lookup($prm_error);
			return $wrk_msg;
		}
	}
	
	function Display_Cover_Statistics() {
		//This function displays they cover statistics by County
		$cov = new stdClass();
		$po = new stdClass();
		$covs = new MN_Covers;
		$cnts = new MN_Counties;
		
		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}
		
				
		//Build the PO array
		$sql = "select id, county from MN_Post_Offices";
		$wrk_county_array = array();
		$wrk_county_cover_array = array();  //Count PO's in a County that we have an example of
		$wrk_county_po_array = array();  //Array containing PO's that we have examples of
		$wrk_po_array = array();
		$wrk_result_array = array();
		$wrk_pos_in_county_array = array();
		$wrk_total_po_count = 0;
		$wrk_total_pos_covered = 0;
		$wrk_county_name_array = $cnts->Get_Array_Counties();
		
		if ($wrk_result_array = $covs->Fetch_All($sql)) {
			foreach ($wrk_result_array as $key=>$po) {
				$wrk_po_array[$po->id] = trim($po->county);
				$wrk_county_array[trim($po->county)] = 0;
				$wrk_county_cover_array[trim($po->county)] = 0;
				$wrk_total_po_count = $wrk_total_po_count + 1;
				
				if (isset($wrk_pos_in_county_array[trim($po->county)])) {
					$wrk_calc_po_in_county = $wrk_pos_in_county_array[trim($po->county)];
					$wrk_calc_po_in_county = $wrk_calc_po_in_county + 1;
					$wrk_pos_in_county_array[trim($po->county)] = $wrk_calc_po_in_county;
				}
				else {
					$wrk_pos_in_county_array[trim($po->county)] = 1;
				}
			}
		}
		$wrk_counties_in_MN = count($wrk_county_array);
		
		//Read through the MN_Covers and build a count by County
		$sql = "select po_id, user_id from MN_Covers where user_id = $wrk_curr_user_id";
		
		$wrk_result2_array = array();
		$wrk_cover_count = 0;
		
		if ($wrk_result2_array = $covs->Fetch_All($sql)) {
			foreach ($wrk_result2_array as $key=>$cov) {
				$wrk_cover_count = $wrk_cover_count + 1;
				$wrk_county = $wrk_po_array[$cov->po_id];
				if (isset($wrk_county_array[$wrk_county])) {
					$wrk_calc = $wrk_county_array[$wrk_county];
					$wrk_calc = $wrk_calc + 1;
					$wrk_county_array[$wrk_county] = $wrk_calc;
				}
				else {
					$wrk_county_array[$wrk_county] = 1;
				}
				//Check to see if we already have an example of this PO
				if (!isset($wrk_county_po_array[$cov->po_id])) {
					$wrk_calc_count_cover_po = $wrk_county_cover_array[$wrk_county];
					$wrk_calc_count_cover_po = $wrk_calc_count_cover_po + 1;
					$wrk_county_cover_array[$wrk_county] = $wrk_calc_count_cover_po; 
					$wrk_total_pos_covered = $wrk_total_pos_covered + 1;
					$wrk_county_po_array[$cov->po_id] = "Y";
				}
			}
		}
		
		ksort($wrk_county_array);	
		
		//Paint the Screen
		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">My Cover Statistics</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
	
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 900px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\">District</th>";
		$wrk_msg .= "<th align=\"left\">POs in District</th>";
		$wrk_msg .= "<th align=\"left\">POs Covered</th>";
		$wrk_msg .= "<th align=\"left\">Cover Count</th>";
		$wrk_msg .= "</tr>";

		$wrk_alt_flag = "Y";
		foreach ($wrk_county_array as $key=>$value) {
			if ($wrk_alt_flag == "Y") {
				$wrk_alt_lit = " class=\"alternate\"";
				$wrk_alt_flag = "N";
			}
			else {
				$wrk_alt_lit = " class=\"alternate2\"";
				$wrk_alt_flag = "Y";
			}
				
			$wrk_msg .= "<tr$wrk_alt_lit>";
			//setup the Cover Icon
			if ($value > 0) {
				$wrk_county_lit = "<a href=\"/?a=18&id=$key\" name=\"$key\">$wrk_county_name_array[$key]</a>";
			}
			else {
				$wrk_county_lit = $wrk_county_name_array[$key];
			}
			$wrk_msg .= "<td>$wrk_county_lit</td>";
			$wrk_msg .= "<td>".$wrk_pos_in_county_array[$key]."</td>";
			$wrk_msg .= "<td>".$wrk_county_cover_array[$key]."</td>";
			$wrk_msg .= "<td>$value</td>";
			$wrk_msg .= "</tr>";
			
		}

		//Display the Total
		if ($wrk_alt_flag == "Y") {
			$wrk_alt_lit = " class=\"alternate\"";
			$wrk_alt_flag = "N";
		}
		else {
			$wrk_alt_lit = " class=\"alternate2\"";
			$wrk_alt_flag = "Y";
		}
			
		$wrk_msg .= "<tr$wrk_alt_lit>";
		//setup the Cover Icon
		$wrk_msg .= "<td>Totals (County Count: $wrk_counties_in_MN)</td>";
		$wrk_msg .= "<td>$wrk_total_po_count</td>";
		$wrk_msg .= "<td>$wrk_total_pos_covered</td>";
		$wrk_msg .= "<td>$wrk_cover_count</td>";
		$wrk_msg .= "</tr>";
		
		
		$wrk_msg .= "</table>";
		//Buttons
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Return\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "</div>";
		
		//Close Div
		$wrk_msg .= "</div>";
		
		return $wrk_msg;		
		
	}	
	
	function Log_File_Read() {

		if (!isset($_SESSION["ssn_un"])) {
			$wrk_msg = "<div class=\"w3-container w3-center\">";
			$wrk_msg .= "<p>You need to be logged in to use this function.</p><br>";
			$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"pst_login\" value=\"Login\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/?a=1'\">";
			$wrk_msg .= "</div>";
			
			return $wrk_msg;
		}
		else {
			$wrk_curr_user_id = $_SESSION["ssn_id"];
		}

		//Paint the Screen
		$wrk_msg = "";
		$wrk_msg .= "<br><div class=\"heading1\">Display Log Records</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}

		$wrk_log_file = "/home/nzcancels/www/log/access.log";
		$myfile = fopen("$wrk_log_file", "r") or die("Unable to open file!");
	
		$wrk_msg .= "<div class=\"w3-responsive\" style=\"max-width: 1100px; margin:auto; \">";
		$wrk_msg .= "<p>&nbsp;<table class=\"w3-table mws_disp\" align=\"left\" width=\"100%\">";
		$wrk_msg .= "<tr class=\"alternate1\">";
		$wrk_msg .= "<th align=\"left\">Date/Time</th>";
		$wrk_msg .= "<th align=\"left\">IP Address</th>";
		$wrk_msg .= "<th align=\"left\">Function</th>";
		$wrk_msg .= "<th align=\"left\">Country</th>";
		$wrk_msg .= "<th align=\"left\">Provider</th>";
		$wrk_msg .= "</tr>";

		$wrk_alt_flag = "Y";

		// Output one line until end-of-file
		while(!feof($myfile)) {
			$wrk_record =  fgets($myfile) ;
			//Date
			$wrk_start = strpos($wrk_record, "<datatime>");
			$wrk_end = strpos($wrk_record, "</datatime>");
			$wrk_datetime = substr($wrk_record, $wrk_start + 10, $wrk_end - ($wrk_start + 10));
			
			//IP Address
			$wrk_start = strpos($wrk_record, "<ip_address>");
			$wrk_end = strpos($wrk_record, "</ip_address>");
			$wrk_ip_address = substr($wrk_record, $wrk_start + 12, $wrk_end - ($wrk_start + 12));

			//IP Function
			$wrk_start = strpos($wrk_record, "<function>");
			$wrk_end = strpos($wrk_record, "</function>");
			$wrk_function = substr($wrk_record, $wrk_start + 10, $wrk_end - ($wrk_start + 10));

			//Country
			$wrk_start = strpos($wrk_record, "<country>");
			$wrk_end = strpos($wrk_record, "</country>");
			$wrk_country = substr($wrk_record, $wrk_start + 9, $wrk_end - ($wrk_start + 9));

			//Provider
			$wrk_start = strpos($wrk_record, "<provider>");
			$wrk_end = strpos($wrk_record, "</provider>");
			$wrk_provider = substr($wrk_record, $wrk_start + 10, $wrk_end - ($wrk_start + 10));
			
			if ($wrk_alt_flag == "Y") {
				$wrk_alt_lit = " class=\"alternate\"";
				$wrk_alt_flag = "N";
			}
			else {
				$wrk_alt_lit = " class=\"alternate2\"";
				$wrk_alt_flag = "Y";
			}
				
			if ($wrk_datetime != "") {
				$wrk_msg .= "<tr$wrk_alt_lit>";
				//setup the Cover Icon
				$wrk_msg .= "<td>$wrk_datetime</td>";
				$wrk_msg .= "<td>$wrk_ip_address</td>";
				$wrk_msg .= "<td>$wrk_function</td>";
				$wrk_msg .= "<td>$wrk_country</td>";
				$wrk_msg .= "<td>$wrk_provider</td>";
				$wrk_msg .= "</tr>";
			}
		}
		
		fclose($myfile);
		
		$wrk_msg .= "</table>";
		//Buttons
		$wrk_msg .= "<div class=\"w3-container w3-center\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Return\" value=\"Return\" style=\"cursor:pointer; margin:0;\" onclick=\"javascript:document.location.href='/'\">&nbsp;";
		$wrk_msg .= "</div>";
		
		//Close Div
		$wrk_msg .= "</div>";
		
		return $wrk_msg;		
		
	}	

	
		//// Output one line until end-of-file
		//while(!feof($myfile)) {
			//$wrk_record =  fgets($myfile) ;
			//$log = new SimpleXMLElement($wrk_record);
			//echo "Data/Time: ".$log->datatime."<br>";
			//echo "IP Address: ".$log->ip_address."<br>";
			//echo "SSN ID: ".$log->ssn_id."<br>";
			//echo "Function: ".$log->function."<br>";
			//echo "Country: ".$log->country."<br>";
			//echo "Provider: ".$log->provider."<br>";
			
			//echo "*********************************************<br>";
		//}
		//fclose($myfile);

	
			////$results = new SimpleXMLElement($result);
		
	//}
	
	
	//****** IMAGE UPLOAD FUNCTIONS *********
	function Upload_Image_Display ($prm_cover, $prm_picture_num) {
		//This function will upload an image file resizing it to three different sizes: thumbnail, normal and large.

		$cvr = new MN_Cover ;
		$cvrs = new MN_Covers;
		$pos = new MN_Post_Offices ;
		$po = new MN_Post_Office;

		//Initialize Variables
		$wrk_msg = "";
		$cvr = $prm_cover;
		
		//print_r($cvr);
		
		//Fetch the Post Office for the Header
		if (!$po = $pos->Fetch($cvr->po_id)) {
			$wrk_msg = "ERROR: Post Office not found on Image Upload<br>";
			return $wrk_msg;
		}
		
		$wrk_msg .= "<br><div class=\"heading1\">Image Upload - Picture $prm_picture_num </div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<p>";
		$wrk_msg .= "<table style=\"max-width: 500px; margin:auto; \">";
		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\" width=\"48%\">PO Name:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->po_name."</td>";
		$wrk_msg .= "</tr>";

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Period:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->period."</td>";
		$wrk_msg .= "</tr>";

		//Rarity Tim
		if (strlen($po->rarity_tim) > 0) {
			$wrk_rarity_tim_lit = "&nbsp;&nbsp;T".$po->rarity_tim;
		}
		else {
			$wrk_rarity_tim_lit = "";		
		}

		$wrk_msg .= "<tr class=\"mws-min-row-height\">";
		$wrk_msg .= "<td class=\"w3-right-align large-font\">Rarity:</td>";
		$wrk_msg .= "<td class=\"large-font\">".$po->rarity."$wrk_rarity_tim_lit</td>";
		$wrk_msg .= "</tr>";
		$wrk_msg .= "</table><br>";
		
		
		//Setup the screen
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p><form enctype=\"multipart/form-data\" action=\"/\" method=\"post\">
				<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1200000\" />
				<table border=0>
				<tr>
				<td>Choose the file to upload: </td><td><input class=\"button\" name=\"pst_file_name\" type=\"file\" size=50 /></td>
				</tr>
				<tr>
				<input type=\"hidden\" name=\"step\" value=\"U1\" />
				<input type=\"hidden\" name=\"pst_picture_num\" value=\"$prm_picture_num\" />
				<input type=\"hidden\" name=\"pst_cvr_id\" value=\"".$cvr->id."\" />";
		
		$wrk_msg .= "</td></tr>";
		$wrk_msg .= "<tr><td colspan=2 align=\"left\">";
		$wrk_msg .= "<p>";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" value=\"Upload\" /> &nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" onclick=\"javascript:document.location.href='/?a=30&id=".$cvr->id."'\">";
		$wrk_msg .= "</td></tr>";
		//Display Warning
		$wrk_msg .= "<tr>";
		$wrk_msg .= "<td colspan=2><p><b>Note:</b><br>Do not use the back key to go back to the previous screen and change something on the lot you are posting. Proceed to the Review screen and press the Edit button if you wish to make a change.  Pressing the Back key and making a change will result in multiple auction lots created.</td>";
		$wrk_msg .= "</tr>";
		$wrk_msg .= "</table>";

		$wrk_msg .= "</form>"; 	
		$wrk_msg .= "</div>"; //End Container

		return $wrk_msg;
	}
	
	function Upload_Image_Process ($prm_cover_id, $prm_picture_num) {
		//This function will upload the image file and update the MN_Covers record
		
		$cvr = new MN_Cover ;
		$cvrs = new MN_Covers;

		//Initialize Variables
		$wrk_msg = "";
		
		if (!$cvr = $cvrs->Fetch($prm_cover_id)) {
			return "ERROR: Cover: $prm_cover_id, not found.<br>";
		}
				
		//Now, upload the file
		if((!empty($_FILES["pst_file_name"])) && ($_FILES["pst_file_name"]["error"] == 0)) {
			//Check if the file is JPEG image or a GIF image and it's size is less than 350Kb
			$filename = basename($_FILES["pst_file_name"]["name"]);
			//echo "Filename:$filename<br>";
			$ext = substr($filename, strrpos($filename, ".") + 1);
			if ((strtoupper($ext) == "GIF") || (strtoupper($ext) == "JPG") || (strtoupper($ext) == "JPEG") || (strtoupper($ext) == "PNG")) {
				if (($_FILES["pst_file_name"]["type"] == "image/jpeg") || ($_FILES["pst_file_name"]["type"] == "image/pjpeg") || ($_FILES["pst_file_name"]["type"] == "image/gif") || ($_FILES["pst_file_name"]["type"] == "image/png")) {
					if ($_FILES["pst_file_name"]["size"] < 1200000) {
						//Determine the path to which we want to save this file
						$id = $cvrs->Get_Next_Doc_ID();
						$newname = $_SERVER["DOCUMENT_ROOT"]."/upload/$id.$ext";
						//$newname = "/upload/$id.$ext";
						//echo "$newname<br>";
						$newname_save = "/upload/$id.$ext";
						$newthumbnail = "/upload/$id"."_tn.$ext";
						$newname_tn = $_SERVER["DOCUMENT_ROOT"]."/upload/$id"."_tn.$ext";
						$newname_small = $_SERVER["DOCUMENT_ROOT"]."/upload/$id"."_small.$ext";
						$cover_small = "/upload/$id"."_small.$ext";
						
						//Check if the file with the same name is already exists on the server
						if (!file_exists($newname)) {
							//Attempt to move the uploaded file to it's new place
							if ((move_uploaded_file($_FILES["pst_file_name"]["tmp_name"],$newname))) {
								//echo "It's done! The file has been saved as: ".$newname;
								require_once $_SERVER["DOCUMENT_ROOT"]."/thumb/ThumbLib.inc.php";  
								$thumb = PhpThumbFactory::create($newname);  
								$thumb->resize(120, 120);  
								$thumb->save($newname_tn);

								$thumb = PhpThumbFactory::create($newname);  
								$thumb->resize(576, 576);  
								$thumb->save($newname_small);
				
								//Now update the MN_Covers record with the image file name
								if ($prm_picture_num == 1) {
									$cvr->picture1 = $newname_save;
									$cvr->picture1_small = $cover_small;
									$cvr->picture1_tn = $newthumbnail;
								}
								else {
									$cvr->picture2 = $newname_save;
									$cvr->picture2_small = $cover_small;
									$cvr->picture2_tn = $newthumbnail;
								}
								$cvr->lastmod_datetime = date("Y-m-d H-i-s");
								if (!$cvrs->Update($cvr)) {
									return "ERROR: ".$cvrs->error;
								}
								else {
									$prm_error = "";
									return Edit_Cover_Display($prm_cover_id, $prm_error);
								}
							} 
							else {
								//A problem occurred during file upload!";
								$error_num = 20;
								return Display_Error($error_num,$cvr);	
							}
						} 
						else {
					 		//File ".$_FILES["uploaded_file"]["name"]." already exists";
							$error_num = 21;
							return Display_Error($error_num,$cvr);	

				  		}
					}
					else {
						//The file uploaded is greater than 850,000 bites
						$error_num = 23;
						return Display_Error($error_num,$cvr);	
			  		}
				}
				else {
					//The uploaded file doesn't have an image type of "image/jpeg" or "image/gif"
					$error_num = 24;
					return Display_Error($error_num,$cvr);	
				}
			
		  	} 
			else {
				//The uploaded file does is not a .jpg image or .gif image.";
				$error_num = 22;
				return Display_Error($error_num,$cvr);	
		    }
		} 
		else {
			if (empty($_FILES["mws_file_name"])) {
				$wrk_msg .= "<center>A file must be select for uploading.<br>";
				$wrk_msg .= "<input type=\"button\" name=\"Upload\" value=\"Return to Upload New Image\" onclick=\"javascript:document.location.href='$wrk_perm_link?action=10&po=".$cvr->po_id."&id=".$cvr->user_id."'\"></center>"; 
				return $wrk_msg;
			}
			else {
		 		//An error was returned
				$error_num =$_FILES["mws_file_name"]["error"];
				return Display_Error($error_num,$cvr);
			}
		}			


	}
	
	function Display_Error($error_num,$prm_cover) {
		
		$cvr = new MN_Cover ;
		
		//Initialize Variables
		$wrk_msg = "";
		$cvr = $prm_cover;
			
		switch ($error_num) {
			case 1:
				$error = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
				break;
			case 2:
				$error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form, which is 850,000 bites. ";
				break;
			case 3:
				$error = "The uploaded file was only partially uploaded.";
				break;
			case 4:
				$error = "No file was uploaded. It could be that no file was selected.";
				break;
			case 6:
				$error = "Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.";
				break;
			case 7:
				$error = "Failed to write file to disk. Introduced in PHP 5.1.0. ";
				break;
			case 8:
				$error = "File upload stopped by extension. Introduced in PHP 5.2.0.";
				break;
			case 20:
				$error = "A problem occurred during file upload!";
				break;
			case 21:
				$error = "File ".$_FILES["uploaded_file"]["name"]." already exists.";
				break;
			case 22:
				$error = "The uploaded file does is not a .jpg image or .jpeg image or .gif image or png image.";
				break;
			case 23:
				$error = "The file uploaded is greater than 850,000 bites.";
				break;
			case 24:
				$error = "The uploaded file doesn't have an image type of \"image/jpeg\" or \"image/gif\" or \"image/png\". The image type of the file that you tried to upload is \"".$_FILES["uploaded_file"]["type"]."\"";
				break;
			
			
			default:
				$error = "An unknown error occurred.";
		}
		$wrk_msg .= "<center>Stamp Page Image Upload:</center><br>";
		$wrk_msg .= "<center>Error: No file uploaded<br>";					
		$wrk_msg .= "Error Number $error_num, \"$error\" was returned<br>";
		

		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Upload\" value=\"Return to Upload New Image\" onclick=\"javascript:document.location.href='/?a=30&id=".$cvr->id."'\"></center>"; 
		return $wrk_msg;
		
	}	

	function Upload_PO_File_Display($prm_error) {
		//This function will upload a file of Post Office information.

		$pos = new MN_Post_Offices ;
		$po = new MN_Post_Office;

		//Initialize Variables
		$wrk_msg = "";
		
				
		$wrk_msg .= "<br><div class=\"heading1\">Post Office Data File Upload</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		if ($prm_error != "") {
			$wrk_msg .= "<div class=\"w3-container\" style=\"max-width: 800px; margin:auto; \">";
			$wrk_msg .= Display_Errors($prm_error) ;
			$wrk_msg .= "</div>";
		}
		
		
		$wrk_msg .= "<p>";
		
		
		//Setup the screen
		$wrk_msg .= "<div class=\"w3-container w3-background-light-gray\" style=\"max-width: 800px; margin:auto; \">";
		$wrk_msg .= "<p><form enctype=\"multipart/form-data\" action=\"/\" method=\"post\">
				<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1200000\" />
				<table border=0>
				<tr>
				<td>Choose the file to upload: </td><td><input class=\"button\" name=\"pst_file_name\" type=\"file\" size=50 /></td>
				</tr>
				<tr>
				<input type=\"hidden\" name=\"step\" value=\"U2\" />";
		
		$wrk_msg .= "</td></tr>";
		$wrk_msg .= "<tr><td colspan=2 align=\"left\">";
		$wrk_msg .= "<p>";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"submit\" value=\"Upload\" /> &nbsp;";
		$wrk_msg .= "<input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"Cancel\" value=\"Cancel\" onclick=\"javascript:document.location.href='/'\">";
		$wrk_msg .= "</td></tr>";
		$wrk_msg .= "</table>";

		$wrk_msg .= "</form>"; 	
		$wrk_msg .= "</div>"; //End Container

		return $wrk_msg;
	}
	
	function Upload_PO_File_Process() {

		$pos = new MN_Post_Offices;	
		$po = new MN_Post_Office;	
		$cnts = new MN_Counties;
		$cnt = new MN_County;
		//Get the Delimiter Character
		$wrk_delim_char = "|";
		
		//Get the Country Array
		$wrk_countries_array = array();
		$wrk_countries_array = $cnts->Get_Array_Counties();

		
		//Сheck that we have a file
		$first_file_OK = "no";
		
		if((!empty($_FILES["pst_file_name"])) && ($_FILES["pst_file_name"]["error"] == 0)) {
			//Check if the file is JPEG image or a GIF image and it's size is less than 350Kb
			$filename = basename($_FILES["pst_file_name"]["name"]);
			$ext = substr($filename, strrpos($filename, ".") + 1);

			if ((strtoupper($ext) == "CSV") || (strtoupper($ext) == "TXT")) {
				if ($_FILES["pst_file_name"]["size"] < 1000000) {
					$file_array = array();
					$file_array = file($_FILES["pst_file_name"]["tmp_name"]);
					$file_row_count = count($file_array);
					
					//Go through and check for errors
					$errors = "";
					for ($i = 1; $i < $file_row_count; $i++) {
						$file_row = array();
						$file_row = explode($wrk_delim_char,$file_array[$i]);
						//print_r($file_row);
						//$file_row = str_getcsv($file_array[$i],$wrk_delim_char,'"','\\');
						//The following test for a blank Title and Description has been added to work with Grant Wagoners Stampcommune script
						//if ((trim($file_row[0]) != "") || (trim($file_row[1]) != "")) {
						//echo "Title:".strtoupper(trim($file_row[0])).":End<br>";
												
						if (((trim($file_row[0]) != "") || (trim($file_row[1]) != "")) && (strtoupper(trim($file_row[0])) != "FALSE")) {	
							if (count($file_row) != 5) {
								$errors .= "Record $i: The number of fields should be 5.  ".count($file_row)." fields were found.<br>";
							}
							if ($file_row[0] == "") {
								$errors .= "Record $i; The PO Name cannot be blank.<br>";
							}
							if ($file_row[1] == "") {
								$errors .= "Record $i; The District cannot be blank.<br>";
							}
							if ($file_row[2] == "") {
								$errors .= "Record $i; The Period cannot be blank.<br>";
							}
							if ($file_row[3] == "") {
								$errors .= "Record $i; The Rarity cannot be blank.<br>";
							}
							if (!is_numeric(trim($file_row[3]))) {
								$errors .= "Record $i; The Rarity is not numeric<br>";
							}
							if (strlen(trim($file_row[3])) > 1) {
								$errors .= "Record $i; The Rarity must be 0 to 9<br>";
							}
							
							//Check that the record isn't duplicate
							$wrk_po_name = trim($file_row[0]);
							$wrk_county = trim($file_row[1]);
							$wrk_period = trim($file_row[2]);
							$wrk_rarity = trim($file_row[3]);
							$wrk_notes = trim($file_row[4]);
							
							if ($po = $pos->Fetch_By_PO_Name_County_Period($wrk_po_name, $wrk_county, $wrk_period)) {
								$errors .= "Record $i; The record already exists for PO Name, County, Period.<br>";
							}
							
							if (!isset($wrk_countries_array[$wrk_county])) {
								$errors .= "Record $i; The County ($wrk_county) is invalid.<br>";
							}

							$file_row = null;		
						}
					}
				}
				else {
					$errors .= "The Upload File was bigger than 1MB<br>";
				}
			}
			else {
				$errors .= "The Upload File must be either a CSV or a TXT file.<br>";
			}
		}
		else {
			if (empty($_FILES["pst_file_name"])) {
				$errors .= "A file must be selected for uploading.<br>";
			}
			else {
				$error_num = $_FILES["pst_file_name"]["error"];
				switch ($error_num) {
					case 1:
						$errors .= "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
						break;
					case 2:
						$errors .= "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form, which is 1,000,000 bites. ";
						break;
					case 3:
						$errors .= "The uploaded file was only partially uploaded.";
						break;
					case 4:
						$errors .= "No file was uploaded. It could be that no file was selected.";
						break;
					case 6:
						$errors .= "Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.";
						break;
					case 7:
						$errors .= "Failed to write file to disk. Introduced in PHP 5.1.0. ";
						break;
					case 8:
						$errors .= "File upload stopped by extension. Introduced in PHP 5.2.0.";
						break;
					case 20:
						$errors .= "A problem occurred during file upload!";
						break;
					case 21:
						$errors .= "File ".$_FILES["pst_file_name"]["name"]." already exists.";
						break;
					case 22:
						$errors .= "The uploaded file does is not a .jpg image or .jpeg image or .gif image.";
						break;
					case 23:
						$errors .= "The file uploaded is greater than 1,000,000 bites.";
						break;
					case 24:
						$errors .= "The uploaded file doesn't have an image type of \"image/jpeg\" or \"image/gif\". The image type of the file that you tried to upload is \"".$_FILES["pst_file_name"]["type"]."\"";
						break;
					
					
					default:
						$errors .= "An unknown error occurred in the file upload.";
				}
			}				
		}		
						
		if ($errors != "") {
			return Upload_PO_File_Display ($errors);
			
		}
		else {
			//All is good, create the MN_Post_Ofice records
			$wrk_rec_count = 0;
			
			for ($i = 1; $i < $file_row_count; $i++) {
				$file_row = array();
				$file_row = explode($wrk_delim_char,$file_array[$i]);
				//print_r($file_row);
				//$file_row = str_getcsv($file_array[$i],$wrk_delim_char,'"','\\');
				//The following test for a blank Title and Description has been added to work with Grant Wagoners Stampcommune script
				//if ((trim($file_row[0]) != "") || (trim($file_row[1]) != "")) {
				//echo "Title:".strtoupper(trim($file_row[0])).":End<br>";
										
				if (((trim($file_row[0]) != "") || (trim($file_row[1]) != "")) && (strtoupper(trim($file_row[0])) != "FALSE")) {	
					
					$wrk_po_name = trim($file_row[0]);
					$wrk_county = trim($file_row[1]);
					$wrk_period = trim($file_row[2]);
					$wrk_rarity = trim($file_row[3]);
					$wrk_notes = trim($file_row[4]);

					$po = new MN_Post_Office;
					$po->po_name = $wrk_po_name;
					$po->county = $wrk_county;
					$po->period = $wrk_period;
					$po->rarity = $wrk_rarity;
					$po->rarity_tim = "";
					$po->notes = $wrk_notes;
					if (!$pos->Create($po)) {
						echo "ERROR: The create of the Post Office record failed. ".$pos->error."<br>";
						return;
					}
					
					$wrk_rec_count = $wrk_rec_count + 1;
					$file_row = null;		
				}
			}
			
			return Upload_PO_File_Result($wrk_rec_count) ;

		}
	}
	
	function Upload_PO_File_Result($prm_rec_count) {
		//This function displays the result of the PO File Upload process

		//Initialize Variables
		$wrk_msg = "";
		
				
		$wrk_msg .= "<br><div class=\"heading1\">Post Office Data File Upload</div><br>";

		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<div class=\"w3-container w3-center w3-text-yellow\" style=\"margin:auto; \">";
			$wrk_msg .= "(Admin Mode On)<br>";
			$wrk_msg .= "</div>";
		}
		
		$wrk_msg .= "<div class=\"w3-container w3-center w3-background-light-gray\">";
		$wrk_msg .= "<p>";
		$wrk_msg .= "The Post Office Data File was successfully uploaded.  $prm_rec_count records were written to the database.<br><br>";
		$wrk_msg .= "<br><input class=\"w3-btn w3-light-grey w3-border w3-border-grey w3-round-large\" type=\"button\" name=\"return\" value=\"Return\" onclick=\"javascript:document.location.href='/'\">"; 
		$wrk_msg .= "</p>";
		$wrk_msg .= "</div>";
		
		return $wrk_msg;	
	}	
	
	//****** SETUP FUNCTIONS *******
	
	function Initialization() {
		//This function provides the initialization process for this program

		session_start();
		header("Cache-control: private");
		
		error_reporting(E_ERROR | E_PARSE);
		ini_set('display_errors', 0);
	
		//Get System Defaults
		global $sys_defaults ;
		global $glb_db ;	//Global database connection
		
		error_reporting(E_ERROR);
		if (($sys_defaults = parse_ini_file("../nzcancels.ini", true)) === FALSE) {
			if (($sys_defaults = parse_ini_file("../../nzcancels.ini", true)) === FALSE) {
				echo "System defaults could not be found.<br>";
				exit;
			}
		}
		
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		date_default_timezone_set('America/Toronto');
	}

	function Security() {
		//This function checks to see if the user has logged in already and sets session variables accordingly
		$usr = new MN_User ;
		$usrs = new MN_Users ;
		
		if (!isset($_SESSION["ssn_un"])) {
			if (isset($_COOKIE["cookie_user_guid"])) {
				$wrk_user_guid = $_COOKIE["cookie_user_guid"];
				if ($usr = $usrs->Fetch_by_user_guid($wrk_user_guid)) {
					$_SESSION["ssn_id"] = $usr->user_id;
					$_SESSION["ssn_un"] = $usr->username;
					$_SESSION["ssn_given_name"] = $usr->given_name;
				}
			}
		}
		//$_SESSION["ssn_un"] = "auldstampguy";
		//echo "ssn_un:".$_SESSION["ssn_un"]."<br>";
		
	}	
	
	function Cookie_Settings() {
		//This function applies the Cookie Settings
	
		//Check to see if the Guid Cookie needs to be set
		if (isset($_SESSION["set_user_guid"])) {
			if ($_SESSION["set_user_guid"] != "") {
				setcookie("cookie_user_guid", $_SESSION["set_user_guid"], time()+(3600*24*30),'/');
				$_SESSION["set_user_guid"] = null;
			}
		}
	}

	
	//****** HEADING AND FOOTER FUNCTIONS
	
	function Build_Heading() {
		//This function builds the html code for the heading section down to an including the BODY statement
		global $sys_defaults;
		
		$wrk_msg = "";
		$wrk_msg .= "<!DOCTYPE html>";
		$wrk_msg .= "<html>";
		$wrk_msg .= "<head>";
		$wrk_msg .= "<title>New Zealand Cancels</title>";
		$wrk_msg .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
		$wrk_msg .= "<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\" >";
		$wrk_msg .= "<meta name=\"keywords\" content=\"New Zealand Cancels, postal history, postage cancels, Discontinued Post Office, DPO, Railway Post Office, RPO\">";
		
		$wrk_msg .= "<meta name=\"description\" content=\"New Zealand Cancels, postal history, postage cancels\">";
		$wrk_msg .= "<meta name=\"robots\" content=\"noindex\" />";
		$wrk_msg .= "<link rel=\"stylesheet\" href=\"/w3/w3.css\" type=\"text/css\">";

		$wrk_css_style1_version = $sys_defaults["css"]["style1"] ;
		$wrk_msg .= "<link href=\"/style1.css?v=$wrk_css_style1_version\" rel=\"stylesheet\" type=\"text/css\">";


		$wrk_msg .= "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">"; 
		$wrk_msg .= "<SCRIPT language=\"JavaScript\" SRC=\"nz_functions.js\"></SCRIPT>";
		
		//$wrk_msg .= "<!-- Global site tag (gtag.js) - Google Analytics -->";
		//$wrk_msg .= "<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-T761TQHSWW\"></script>";
		//$wrk_msg .= "<script>";
		//$wrk_msg .= "window.dataLayer = window.dataLayer || []; ";
		//$wrk_msg .= "function gtag(){dataLayer.push(arguments);} ";
		//$wrk_msg .= "gtag('js', new Date()); ";

		//$wrk_msg .= "gtag('config', 'G-T761TQHSWW'); ";
		//$wrk_msg .= "</script>";		
		$wrk_msg .= "</head>";

		$wrk_msg .= "<body class=\"w3-content\" style=\"max-width:1110px\">";

		//Page Heading
		//Large and Medium Screen
		$wrk_msg .= "<div class=\"w3-container w3-center w3-hide-small  w3-hide-medium\">";
		$wrk_msg .= "<p><br>";
		if (isset($_GET["a"])) {
			$wrk_msg .= "<h1 class=\"site-title\">New Zealand Cancels</h1>";
		}
		else {
			$wrk_msg .= "<h1 class=\"site-title-main\"><img src=\"/images/leaf-150-clear.png\"/> New Zealand Cancels</h1>";
		}
		$wrk_msg .= "</div>";
		//Small Screen
		$wrk_msg .= "<div class=\"w3-container w3-center w3-hide-large\">";
		$wrk_msg .= "<p><br>";
		$wrk_msg .= "<h1 class=\"site-title\">New Zealand Cancels</h1>";
		$wrk_msg .= "</div>";
		
		//Build Menu
		//Large Screen
		$wrk_msg .= "<br>";
		$wrk_msg .= "<div class=\"w3-bar\">";
		$wrk_msg .= "<a href=\"/\" class=\"w3-bar-item w3-button w3-hover-none w3-border-darkgreen w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Home</a>";
		$wrk_msg .= "<a href=\"/?a=10\" class=\"w3-bar-item w3-button w3-hover-none w3-border-darkgreen w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Post Office Lookup</a>";
		$wrk_msg .= "<a href=\"/?a=20\" class=\"w3-bar-item w3-button w3-hover-none w3-border-darkgreen w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Rarity Factor</a>";
		$wrk_msg .= "<a href=\"/?a=21\" class=\"w3-bar-item w3-button w3-hover-none w3-border-darkgreen w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Rare Post Offices</a>";
		$wrk_msg .= "<a href=\"/?a=22\" class=\"w3-bar-item w3-button w3-hover-none w3-border-darkgreen w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Earliest Covers</a>";
		$wrk_msg .= "<a href=\"/?a=23\" class=\"w3-bar-item w3-button w3-hover-none w3-border-darkgreen w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Recently Added</a>";

		//More dropdown
		if (isset($_SESSION["ssn_id"])) {
			$wrk_msg .= "<div class=\"w3-dropdown-hover \">";
			$wrk_msg .= "<a class=\"w3-button  w3-hide-small w3-hide-medium\"  onmouseover=\" \">More  <i class=\"fa fa-caret-down\"></i></a>";
			$wrk_msg .= "<div  class=\"w3-dropdown-content w3-dropdown-content-override w3-bar-block w3-card-4 \">";
			//$wrk_msg .= "<a href=\"/auction/auct_lot_display.php\" class=\"w3-bar-item w3-button w3-hide-small\">View Open Lots</a>";

			$wrk_msg .= "<a href=\"/?a=24\" class=\"w3-bar-item w3-button w3-text-white w3-hover-none w3-border-darkgray w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">My Favs</a>";
			$wrk_msg .= "<a href=\"/?a=25\" class=\"w3-bar-item w3-button w3-text-white w3-hover-none w3-border-darkgray w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Needs Research</a>";
			$wrk_msg .= "<a href=\"/?a=62\" class=\"w3-bar-item w3-button w3-text-white w3-hover-none w3-border-darkgray w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">My Cover Statistics</a>";
			
			if (isset($_SESSION["ssn_admin_mode"])) {
				$wrk_msg .= "<a href=\"/?a=45\" class=\"w3-bar-item w3-button w3-text-white w3-hover-none w3-border-darkgray w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Add PO</a>";
				$wrk_msg .= "<a href=\"/?a=70\" class=\"w3-bar-item w3-button w3-text-white w3-hover-none w3-border-darkgray w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">PO File Upload</a>";
				$wrk_msg .= "<a href=\"/?a=61\" class=\"w3-bar-item w3-button w3-text-white w3-hover-none w3-border-darkgray w3-hover-text-white w3-bottombar w3-hover-border-white w3-hide-small w3-hide-medium\">Log File</a>";
			
			}	
			$wrk_msg .= "</div>";
			$wrk_msg .= "</div>";
		}
		
		
		$wrk_msg .= "<span  class=\"w3-bar-item w3-hide-large\">Menu</span>";
		$wrk_msg .= "<a href=\"javascript:void(0)\" class=\"w3-bar-item w3-button w3-large w3-right w3-hide-large \" onclick=\"NavFunction()\">&#9776;</a> ";

		$wrk_msg .= "</div>";

		//Small Screen
		$wrk_msg .= "<div id=\"NavSmall\" class=\"w3-bar-block w3-nav-backgroup w3-hide w3-hide-large\">";
		$wrk_msg .= "<a href=\"/index.php\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Home</a>";
		$wrk_msg .= "<a href=\"/?a=10\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Post Office Lookup</a>";
		$wrk_msg .= "<a href=\"/?a=20\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Rarity Factor</a>";
		$wrk_msg .= "<a href=\"/?a=21\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Rare Post Offices</a>";			  
		$wrk_msg .= "<a href=\"/?a=22\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Earliest Covers</a>";
		$wrk_msg .= "<a href=\"/?a=23\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Recently Added</a>";
		if (isset($_SESSION["ssn_id"])) {
			$wrk_msg .= "<a href=\"/?a=24\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;My Favs</a>";
			$wrk_msg .= "<a href=\"/?a=25\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Needs Research</a>";
			$wrk_msg .= "<a href=\"/?a=62\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;My Cover Statistics</a>";
		}
		if (isset($_SESSION["ssn_admin_mode"])) {
			$wrk_msg .= "<a href=\"/?a=45\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Add PO</a>";
			$wrk_msg .= "<a href=\"/?a=61\" class=\"w3-bar-item w3-button\">&nbsp;&nbsp;&nbsp;&nbsp;Log File</a>";
		}
		$wrk_msg .= "</div>";
		$wrk_msg .= "<br>";

		$wrk_msg .= "<script>";
		$wrk_msg .= "function NavFunction() { ";
		$wrk_msg .= "  var x = document.getElementById(\"NavSmall\"); ";
		$wrk_msg .= "  if (x.className.indexOf(\"w3-show\") == -1) { ";
		$wrk_msg .= "	x.className += \" w3-show\"; ";
		$wrk_msg .= "  } else { "; 
		$wrk_msg .= "	x.className = x.className.replace(\" w3-show\", \"\"); ";
		$wrk_msg .= "  } ";
		$wrk_msg .= "} ";
		$wrk_msg .= "</script>";		
		
		return $wrk_msg;
			
	}
	
	function Build_Footer() {
		//This function builds the Footer code
		
		$wrk_msg = "";
		$wrk_msg .= "<br>";
		$wrk_msg .= "<center><font size=-1>Copyright &copy;".date("Y")." NZCancels.org - All Rights Reserved</font></center>";
		$wrk_msg .= "<br>";
		
		$wrk_msg .= "</body>";
		$wrk_msg .= "</html>";
		
		Return $wrk_msg;	
		
	}	
	
	function Admin_Mode_On() {
		//This function will turn on the Admin Mode
		
		$_SESSION["ssn_admin_mode"] = "Y";
		
		$wrk_msg = Display_Home_Page();
		return $wrk_msg;
	}

	function Admin_Mode_Off() {
		//This function will turn off the Admin Mode
		
		$_SESSION["ssn_admin_mode"] = null;
		
		$wrk_msg = Display_Home_Page();
		return $wrk_msg;
	}

	function Write_Log_Record($prm_function) {
		//This function writes a record to the log file
		global $sys_defaults;
		
		//Write to log
		$wrk_ip_info_array = array();
		$wrk_log_file = "/home/nzcancels/www/log/access.log";
		
		$wrk_datetime = date("Y-m-d H-i-s");
		$wrk_ip_address = $_SERVER['REMOTE_ADDR'] ;
		if (isset($_SESSION["ssn_id"])) {
			$wrk_ssn_id = $_SESSION["ssn_id"];
			$wrk_country = "";
			$wrk_provider =  "";

		}
		else {
			if ($wrk_ip_address != $sys_defaults["ip"]["myip"]) {
				$wrk_ssn_id = 0;
				$wrk_ip_info_array = Get_County_From_IP2($wrk_ip_address);
				$wrk_country = $wrk_ip_info_array[1];
				$wrk_provider =  $wrk_ip_info_array[2];

				$myfile = fopen("$wrk_log_file", "a") or die("Unable to open file!");
				$wrk_record = "<record><datatime>$wrk_datetime</datatime><ip_address>$wrk_ip_address</ip_address><ssn_id>$wrk_ssn_id</ssn_id><function>$prm_function</function><country>$wrk_country</country><provider>$wrk_provider</provider></record>\n";
				fwrite($myfile, $wrk_record);	
				fclose($myfile);
			}
		}
		
		
		//$wrk_record = date("Y-m-d H-i-s")."-".$ct->firstname.",".$ct->lastname.",".$ct->membershipno.",".$wrk_pass1."\n";

	}
	

?>
