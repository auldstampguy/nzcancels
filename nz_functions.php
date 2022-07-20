<?php
/***********************************************************************
	Function Classes for MNCANCELS.ORG
	Written by Tim Auld
************************************************************************/

//  Function Area

	function Display_Errors($prm_errors) {
		//This function will display errors passed in on input
		
		$wrk_msg = "<div class=\"w3-text-yellow\">";
		$wrk_msg .= "<p class=\"w3-text-yellow\">The following errors were found in the information entered:";
		$wrk_msg .= "<ol>$prm_errors</ol>";
		$wrk_msg .= "</p>";
		$wrk_msg .= "</div>";
		return $wrk_msg ;
	}
	
	function Write_Error($prm_error) {
		//This function will write errors past on input to the apache error log
		//$wrk_un = get_current_user_id();
		$wrk_un = "Tim";
		$wrk_message = "MNC - User:$wrk_un, Message:$prm_error";
		error_log($wrk_message, 0);
	}
	
	function Get_Crypt($prm_crypt_data) {
		//This function will encrypt the data passed in on input and will encrypt it using a salt and then return encryption without the salt

		//Get System Defaults
		global $sys_defaults ;
		
		$wrk_password_crypt = "";
		$wrk_password_input = "";
		$wrk_password_calc = "";
		$wrk_password_input = $prm_crypt_data;
		$wrk_pass_count = 1;
		$wrk_done = "N";
		
		while ($wrk_done == "N") {
			if ($wrk_pass_count == 1) {
				$wrk_salt = $sys_defaults["salt"]["salt1"] ;
			}
			elseif ($wrk_pass_count == 2) {
				$wrk_salt = $sys_defaults["salt"]["salt2"] ;
			} 
			else {
				$wrk_salt = $sys_defaults["salt"]["salt3"] ;
			} 
			
			if (strlen($wrk_password_input) < 9) {
				$wrk_password_crypt .= substr(crypt($wrk_password_input, $wrk_salt), 2);
				$wrk_done = "Y";
			}
			else {
				$wrk_password_calc = substr($wrk_password_input, 0, 8);
				$wrk_password_crypt .= substr(crypt($wrk_password_calc, $wrk_salt), 2);
				$wrk_password_input = substr($wrk_password_input, 8);
				$wrk_pass_count = $wrk_pass_count + 1;
			}
		}

		//$wrk_crypt_data = substr(crypt($prm_crypt_data, "RA"), 2);
		
		return $wrk_password_crypt;
		
	}

	//function Get_Crypt($prm_crypt_data) {
		////This function will encrypt the data passed in on input and will encrypt it using a salt and then return encryption without the salt
		
		//$wrk_crypt_data = substr(crypt($prm_crypt_data, "RA"), 2);
		
		//return $wrk_crypt_data;
		
	//}
	
	
	function isValidEmail($email){
		//This function will validate the email address passed in on input
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}	
	
		
	function mws_is_date($prmdate) {
		//This function will validate the date passed on input.  It must be in MM/DD/YYYY format.
		
		//Declarate the necessary variables
		$strdate1="";
		$strdate=$prmdate;
		$error = "";
		
		//Check the length of the entered Date value
		if((strlen($strdate)<10)OR(strlen($strdate)>10)){
			$error .= "Enter the date in 'MM/DD/YYYY' format";
		}
		else{
			//The entered value is checked for proper Date format
			if((substr_count($strdate,"/"))<>2){
				$error .= "Enter the date in 'MM/DD/YYYY' format";
			}
			else{
				$pos=strpos($strdate,"/");
				$month=substr($strdate,0,($pos));
				if(($month<=0)||($month>12)){
					$error .= "Enter a Valid Month";
				}
				$day=substr($strdate,($pos+1),($pos));
				if(($day<=0)||($day>31)){
					$error .= "Enter a Valid Day";
				}
				$year=substr($strdate,($pos+4),strlen($strdate));
				if(($year<1800)OR($year>2200)){
					$error .= "Enter a year between 1800-2200";
				}
			}	
		}
		if ($error == "") {
			return true;
		}
		else {
			return false;
		}
	}	

	function mws_reformat_date($prmdate) {
		//This function will reformat the date passed on input from MM/DD/YYYY format to YYYY-MM-DD.
		
		//Declarate the necessary variables
		$strdate1="";
		$strdate=$prmdate;
		$error = "";
		
		$pos=strpos($strdate,"/");
		$month=substr($strdate,0,($pos));
		$day=substr($strdate,($pos+1),($pos));
		$year=substr($strdate,($pos+4),strlen($strdate));

		$wrk_date_reformat = $year."-".$month."-".$day;
		
		return $wrk_date_reformat;
	}	

	function mws_format_date($prmdate) {
		//This function will reformat the date passed on input from MM/DD/YYYY format to YYYY-MM-DD.
		
		//Declarate the necessary variables
		$strdate1="";
		$strdate=$prmdate;
		$error = "";
		
		$pos=strpos($strdate,"-");
		$year=substr($strdate,0,($pos));
		$month=substr($strdate,($pos+1),2);
		$day=substr($strdate,($pos+4),strlen($strdate));

		$wrk_date_reformat = $month."/".$day."/".$year;
		
		return $wrk_date_reformat;
	}
	
	function Set_Request_URI() {
		//This function will set the URL so that it can be used to redirect after logging on to the Auction
		
		$_SESSION["URI"] = $_SERVER["REQUEST_URI"];
		
	}
	
	//Lookup the IP address
	function Get_County_From_IP2($prm_ip) {

		$IP = $prm_ip;
		//$IP = "50.157.76.53";
		//$IP = "217.169.229.153";
		//echo "IP:$IP<br>";
		$results_array = array();

		$results = file_get_contents('http://api.ip2location.com/?'.'ip='.$IP.'&key=ED0966E939'.'&package=WS2');
		if ($results == "INVALID IP ADDRESS") {
			$output = FALSE;
		}
		else {
			//echo "Results:$results<br>";
			$results_array = explode(";", $results);
			$output = $results_array;
			//print_r($results_array);
		}

		return $output;
		

	}
	
		
	
// create GUID
	function createGUID() {
		if (function_exists('com_create_guid')) {
			return com_create_guid();
		}
		else {
			mt_srand((double)microtime()*10000);
			//optional for php 4.2.0 and up.
			$set_charid = strtoupper(md5(uniqid(rand(), true)));
			$set_hyphen = chr(45);
			// "-"
			$set_uuid = chr(123)
			.substr($set_charid, 0, 8).$set_hyphen
			.substr($set_charid, 8, 4).$set_hyphen
			.substr($set_charid,12, 4).$set_hyphen
			.substr($set_charid,16, 4).$set_hyphen
			.substr($set_charid,20,12)
			.chr(125);
			// "}"
			return $set_uuid;
		}
	}	






/***********************************************************************
	Database Classes
************************************************************************/

	//	MNC Class
	
//	Master Database Class
	
	
	class MNC {
	
		private $db;
		private $db_user;
		private $db_password;
		private $db_name;
				
		public $error ;
		
		
		function OpenDatabase(){
			
			global $sys_defaults;
			global $glb_db; 
			
			if ($glb_db == "") {
				//Get database information
				$this->db_user = $sys_defaults["database"]["userid"] ;
				$this->db_password = $sys_defaults["database"]["password"] ;
				$this->db_name = $sys_defaults["database"]["db_name"] ;
				$this->db = mysqli_connect("localhost", $this->db_user, $this->db_password, $this->db_name);
				
				if (mysqli_connect_errno()) {
					$prm_error = mysqli_connect_error();
					Write_Error($prm_error) ;
					return exit;
				}
				
				if (!mysqli_set_charset($this->db, "utf8")) {
					$prm_error = "Error when setting characterset: ".mysqli_error($this->db);
					Write_Error($prm_error) ;
					return exit;
				}

				if (!mysqli_autocommit($this->db,TRUE)) {
					$prm_error = "Set Auto-commit True Failed: ".mysqli_error($this->db);
					Write_Error($prm_error) ;
					return exit;
				}
				
				//$query = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
				//$result = mysqli_query($this->db, $query);
				//if ($result == FALSE) {
					//$prm_error = "Set ONLY_FULL_GROUP_BY Failed: ".mysqli_error($this->db);
					//Write_Error($prm_error) ;
					//return exit;
				//}
				//$query = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'NO_ZERO_IN_DATE',''))";
				//$result = mysqli_query($this->db, $query);
				//if ($result == FALSE) {
					//$prm_error = "Set NO_ZERO_IN_DATE Failed: ".mysqli_error($this->db);
					//Write_Error($prm_error) ;
					//return exit;
				//}
				//$query = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'NO_ZERO_DATE',''))";
				//$result = mysqli_query($this->db, $query);
				//if ($result == FALSE) {
					//$prm_error = "Set NO_ZERO_DATE Failed: ".mysqli_error($this->db);
					//Write_Error($prm_error) ;
					//return exit;
				//}

					
				$glb_db = $this->db;
			}
			else {
				$this->db = $glb_db;
			}
			
			return $this->db;

		}
	
	
		
		function Fetch_All($prm_sql) {
			//This function will retrieve all records selected by the SQL on input
			
			$wrk_objs = array();
			$wrk_count = 0;
			
			$result = mysqli_query($this->db, $prm_sql);   
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_assoc($result)) {
					$wrk_obj = new stdClass();
					foreach ($row as $key=>$value) {
						$wrk_obj->$key = $value;
					}
					$wrk_count = $wrk_count + 1;
					$wrk_objs[$wrk_count] = $wrk_obj;
					$wrk_obj = null;
				}
				//print_r($this);
				return $wrk_objs ;
			}
			else {
				return false ;
			}
			
		}


		function Exec_Query($prm_sql) {
			//This function will execute the sql transaction passed in on input
			global $wpdb ;
			
			if ($wpdb->query($prm_sql) === FALSE) {
				$this->error = $wpdb->last_error;
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
			else {
				return TRUE ;
			}
		}
		
	}
	
	// MN User Class
	class MN_User {
		
		public $user_id ;
		public $username ;
		public $userpassword;
		public $given_name ;
		public $surname ;
		public $email_address ;
		public $user_guid ;
		public $lastmod_datetime ;
		public $admin_flag ;
		
	}
	
	class MN_Users extends MNC {
		
		public  $error ;
		private $db ;
		
		function __construct(){
			$this->db = MNC::OpenDatabase();
		}
		
		function Fetch($prm_user_id) {
			//This function will retrieve an MN_Users record from the DB using the user_id
			
			$usr = new MN_User;
			
			$sql = "select * from MN_Users where user_id = $prm_user_id";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$usr->$key = $value;
				}
				//print_r($this);
				return $usr ;
			}
			else {
				return false ;
			}

		}

		function Fetch_by_username($prm_username) {
			//This function will retrieve an MN_Users record from the DB using the username
			
			$usr = new MN_User;
			
			$sql = "select * from MN_Users where username = '$prm_username'";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$usr->$key = $value;
				}
				//print_r($this);
				return $usr ;
			}
			else {
				return false ;
			}

		}

		function Fetch_by_user_guid($prm_user_guid) {
			//This function will retrieve an MN_Users record from the DB using the user_guid
			
			$usr = new MN_User;
			
			$sql = "select * from MN_Users where user_guid = '$prm_user_guid'";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$usr->$key = $value;
				}
				//print_r($this);
				return $usr ;
			}
			else {
				return false ;
			}

		}
	}


	// MN Post Office Class
	
	class MN_Post_Office {
		
		public $id ;
		public $po_name ;
		public $county ;
		public $period ;
		public $rarity ;
		public $rarity_tim;
		public $notes ;
		
	}
	
	class MN_Post_Offices extends MNC {
		
		public  $error ;
		private $db ;
		
		function __construct(){
			$this->db = MNC::OpenDatabase();
		}

		function Fetch($prm_id) {
			//This function will retrieve an MN_Post_Office record from the DB
			
			$pst = new MN_Post_Office;
			
			$sql = "select * from MN_Post_Offices where id = $prm_id";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$pst->$key = $value;
				}
				//print_r($this);
				return $pst ;
			}
			else {
				return false ;
			}

		}

		function Fetch_By_PO_Name($prm_po_name) {
			//This function will retrieve an MN_Post_Office record from the DB
			
			$pst = new MN_Post_Office;
			
			$sql = "select * from MN_Post_Offices where  po_name = '$prm_po_name'";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$pst->$key = $value;
				}
				//print_r($this);
				return $pst ;
			}
			else {
				return false ;
			}

		}

		function Fetch_By_PO_Name_County_Period($prm_po_name, $prm_county, $prm_period) {
			//This function will retrieve an MN_Post_Office record from the DB
			
			$pst = new MN_Post_Office;
			
			$sql = "select * from MN_Post_Offices where  po_name = '$prm_po_name' and county = '$prm_county' and period = '$prm_period'";
			//echo "SQL: $sql<br>";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$pst->$key = $value;
				}
				//print_r($this);
				return $pst ;
			}
			else {
				return false ;
			}

		}
		
		function Fetch_Next_Post_Office_By_Name ($prm_po_name) {
			//This function will retrieve the next Post Office Name in Post Office Name order where we have MN_Cover record in the system

			$pst = new MN_Post_Office;
			
			$sql = "select a.* from MN_Post_Offices a, MN_Covers b where a.id=b.po_id and a.po_name > '$prm_po_name' order by a.po_name asc limit 1";
			//echo "SQL:$sql<br>";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$pst->$key = $value;
				}
				//print_r($this);
				return $pst ;
			}
			else {
				return false ;
			}
		}
	
		function Fetch_Previous_Post_Office_By_Name ($prm_po_name) {
			//This function will retrieve the previous Post Office Name in Post Office Name order where we have MN_Cover record in the system

			$pst = new MN_Post_Office;
			
			$sql = "select a.* from MN_Post_Offices a, MN_Covers b where a.id=b.po_id and a.po_name < '$prm_po_name' order by a.po_name desc limit 1";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$pst->$key = $value;
				}
				//print_r($this);
				return $pst ;
			}
			else {
				return false ;
			}
		}

		function Get_String_Post_Offices() {
		//Return the string of Post Offices
		
			$query = "Select po_name  from MN_Post_Offices order by po_name asc";

			$wrk_po_string = "";
			$wrk_count = 1;
			
			$result = mysqli_query($this->db, $query);   
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_assoc($result)) {
					if ($wrk_count == 1) {
						$wrk_po_string = "\"".$row["po_name"]."\"" ;
						$wrk_count = $wrk_count + 1;
					}
					else {
						$wrk_po_string .= ",\"".$row["po_name"]."\"" ;
					}
					//if (strlen($row["alt_au_username"]) > 1) {
						//$wrk_mem_string .= ",\"".$row["alt_au_username"]."\"" ;
					//}
				}

			}
			
			return $wrk_po_string ;

		}
		
		
		function Update ($prm_post_office) {
			//This function will update a row in the Post Offices table

			$po = new MN_Post_Office;
			$po1 = new MN_Post_Office;
			$po1 = $prm_post_office;
			

			if ($po = $this->Fetch($po1->id)) {
				//Build SQL statement
				$sql = "update MN_Post_Offices set ";
				$sql .= "po_name = '".mysqli_real_escape_string($this->db, $po1->po_name)."', ";
				$sql .= "county = '".mysqli_real_escape_string($this->db, $po1->county)."', ";
				$sql .= "period = '".mysqli_real_escape_string($this->db, $po1->period)."', ";
				$sql .= "rarity = ".$po1->rarity.", ";
				$sql .= "rarity_tim = '".$po1->rarity_tim."', ";
				$sql .= "notes = '".mysqli_real_escape_string($this->db, $po1->notes)."' ";
				//$sql .= "notes = '".$po->notes."' ";
				$sql .= "where id = ".$po->id ;
				//echo "SQL:$sql<br>";
				if (mysqli_query($this->db, $sql)) {
					return true ;
				}
				else {
					$this->error = "MN_Post_Office Insert failed : ".mysqli_error($this->db);
					Write_Error($this->error);
					echo "ERROR:".$this->error."<br>";
					return FALSE;
				}
			}
			else {
				$this->error = "The MN Post Office record was not found when trying to Update";
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}		
		}
		
		function Create($prm_post_office) {
			//This function will create a row in the Post Offices table

			$po = new MN_Post_Office;
			$po = $prm_post_office;
			
			//Build insert statement
			$sql = "insert into MN_Post_Offices (";
			$sql .= "po_name, county, period,rarity, rarity_tim, notes) ";
			$sql .= "values (";
			$sql .= "'".mysqli_real_escape_string($this->db, $po->po_name)."', ";
			$sql .= "'".mysqli_real_escape_string($this->db, $po->county)."', ";
			$sql .= "'".mysqli_real_escape_string($this->db, $po->period)."', ";
			$sql .= $po->rarity.", ";
			$sql .= "'".mysqli_real_escape_string($this->db, $po->rarity_tim)."', ";
			$sql .= "'".mysqli_real_escape_string($this->db, $po->notes)."') ";

			//echo "SQL:$sql<br>";
			if (mysqli_query($this->db, $sql)) {
				return true ;
			}
			else {
				$this->error = "MN_Post_Office Create failed : ".mysqli_error($this->db);
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
		}

		function Delete($prm_po_id) {
			//This function will delete a row in the Post Offices table

			//Build insert statement
			$sql = "delete from MN_Post_Offices where id = $prm_po_id";

			//echo "SQL:$sql<br>";
			if (mysqli_query($this->db, $sql)) {
				return true ;
			}
			else {
				$this->error = "MN_Post_Office Delete failed : ".mysqli_error($this->db);
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
		}
		
		function Validate($prm_post_office) {
			//This function will validate the Post Office data passed on input

			$po = new MN_Post_Office;
			$po = $prm_post_office;
			
			$wrk_msg = "";
			
			if (strlen(trim($po->po_name)) == 0) {
				$wrk_msg = "<li>The Post Name cannot be blank</li>";
			}
			if (strlen(trim($po->county)) == 0) {
				$wrk_msg .= "<li>The County cannot be blank</li>";
			}
			if (strlen(trim($po->period)) == 0) {
				$wrk_msg .= "<li>The Period cannot be blank</li>";
			}
			if (strlen(trim($po->rarity)) == 0) {
				$wrk_msg .= "<li>The Rarity cannot be blank</li>";
			}
			if (!is_numeric($po->rarity)) {
				$wrk_msg .= "<li>The Rarity must be a number between 0 and 9</li>";
			}
			else {
				if (($po->rarity < 0) || ($po->rarity > 9)) {
					$wrk_msg .= "<li>The Rarity must be between 0 and 9</li>";
				}
			}
						
			return $wrk_msg;
		}
		
		function Get_Next_PO_Id() {
			//This function will return the next PO ID 
			//This function will create a row in the Post Offices table
			
			$pos = new MN_Post_Offices;

			$wrk_results = $pos->Fetch_All("select max(id) as max_id from MN_Post_Offices");
			foreach ($wrk_results as $key => $obj) {
				$wrk_id = $obj->max_id;
			}
			
			//Next Id
			$wrk_id += 1;
			return $wrk_id;
			
			
			
		}
	}
	
	
	// MN County Class
	
	class MN_County {
		
		public $county ;
		public $county_name ;
		
	}
	
	class MN_Counties extends MNC {
		
		public $error ;
		public $db ;

		function __construct(){
			$this->db = MNC::OpenDatabase();
		}
		
		function Build_County_Select($prm_selected_county) {
			//This function will return a dropdown of all the Counties
			global $wpdb ;
			
			$sql = "select county from MN_Counties";
			$wrk_results = MNC::Fetch_All($sql);
			
			$opt = "<option value=\"\">Select a County&nbsp; --------&nbsp;</option>";
			
			foreach ($wrk_results as $key => $obj) {
				if ($prm_selected_county == $obj->county) {
					$opt .= "<option value=\"".$obj->county."\" selected>".$obj->county."</option>";
				}
				else {
					$opt .= "<option value=\"".$obj->county."\">".$obj->county."</option>";
				}
			}
			
			$select_return = "<select name=\"county\">".$opt."</select>";

			return $select_return;
		}
		
		function Get_String_Counties() {
		//Return the string of Counties
		
			$query = "Select county_name  from MN_Counties order by county_name asc";

			$wrk_county_string = "";
			$wrk_count = 1;
			
			$result = mysqli_query($this->db, $query);   
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_assoc($result)) {
					if ($wrk_count == 1) {
						$wrk_county_string = "\"".$row["county_name"]."\"" ;
						$wrk_count = $wrk_count + 1;
					}
					else {
						$wrk_county_string .= ",\"".$row["county_name"]."\"" ;
					}
					//if (strlen($row["alt_au_username"]) > 1) {
						//$wrk_mem_string .= ",\"".$row["alt_au_username"]."\"" ;
					//}
				}

			}
			
			return $wrk_county_string ;

		}

		function Get_Array_Counties() {
		//Return the array of Counties
		
			$query = "Select *  from MN_Counties order by county_name asc";

			$wrk_county_array = array();
			$wrk_count = 1;
			
			$result = mysqli_query($this->db, $query);   
			if (mysqli_num_rows($result)>0){
				while ($row = mysqli_fetch_assoc($result)) {
					$wrk_county_array[$row["county"]] = $row["county_name"] ;
				}

			}
			
			return $wrk_county_array ;

		}
		
	}
			
	

	// MN_Cover Class
	
	class MN_Cover {
		
		public $id ;
		public $user_id ;
		public $po_id ;
		public $description ;
		public $destination ;
		public $picture1 ;
		public $picture1_small ;
		public $picture1_tn ;
		public $picture2 ;
		public $picture2_small ;
		public $picture2_tn ;
		public $cancel_date ;
		public $lastmod_datetime ;
		public $favorite_flag ;
		public $needs_research_flag ;
		public $label_num ;
		
	}
	
	class MN_Covers extends MNC {
		
		public $error ;
		public $db ;
		
		function __construct(){
			$this->db = MNC::OpenDatabase();
		}
		
		
		function Exists($prm_id) {
			//This function will check to see if an MN_Cover record exists in the DB

			$cvr = new MN_Cover;
			
			$sql = "select * from MN_Covers where id = $prm_id";

			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				return true;
			}
			else {
				return false ;
			}

		}

		function Fetch($prm_id) {
			//This function will retrieve an MN_Cover record from the DB

			$cvr = new MN_Cover;
			
			$sql = "select * from MN_Covers where id = $prm_id";
			//echo "MN_Cover Sql: $sql<br>";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$cvr->$key = $value;
				}
				//print_r($this);
				return $cvr ;
			}
			else {
				return false ;
			}

		}

		function Fetch_By_lastmod_datetime ($prm_cover) {
			//This function will retrieve an MN_Cover record from the DB
			global $wpdb ;
			
			$mnc = new MN_Cover ;
			$mnc = $prm_cover;
			
			$sql = "select * from MN_Covers where user_id = ".$mnc->user_id." and lastmod_datetime = '".$mnc->lastmod_datetime."'";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$mnc->$key = $value;
				}
				//print_r($this);
				return $mnc ;
			}
			else {
				return false ;
			}
		}

		function Create($prm_mws_cover) {
			//This function will create a row in the MN_Covers table
			$mnc = new MN_Cover;
			$mnc = $prm_mws_cover;
			
			//Build insert statement
			$sql = "insert into MN_Covers (";
			$sql .= "id, user_id, po_id, description,destination, picture1, picture1_small, picture1_tn, picture2, picture2_small, picture2_tn, cancel_date,lastmod_datetime, favorite_flag, needs_research_flag, label_num) ";
			$sql .= "values (";
			$mnc->id = $this->Get_Next();
			$sql .= $mnc->id.", ";				
			$sql .= $mnc->user_id.", ";
			$sql .= $mnc->po_id.", ";
			$sql .= "'".mysqli_real_escape_string($this->db, $mnc->description)."', ";
			$sql .= "'".mysqli_real_escape_string($this->db, $mnc->destination)."', ";
			$sql .= "'".$mnc->picture1."', ";
			$sql .= "'".$mnc->picture1_small."', ";
			$sql .= "'".$mnc->picture1_tn."', ";
			$sql .= "'".$mnc->picture2."', ";
			$sql .= "'".$mnc->picture2_small."', ";
			$sql .= "'".$mnc->picture2_tn."', ";
			$sql .= "'".$mnc->cancel_date."', ";			
			$sql .= "'".$mnc->lastmod_datetime."', ";
			$sql .= "'".$mnc->favorite_flag."', ";
			$sql .= "'".$mnc->needs_research_flag."', ";
			$sql .= "'".$mnc->label_num."') ";
			//echo "SQL: $sql<br>";
			if (mysqli_query($this->db, $sql)) {
				return true ;
			}
			else {
				$this->error = "MN_Cover Insert failed : ".mysqli_error($this->db);
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
		}
		
		function Update ($prm_mn_cover) {
			//This function will update a row in the MN_Covers table
			
			$mnc = new MN_Cover;
			$mnc = $prm_mn_cover;

			if ($this->Exists($mnc->id)) {
				//Build SQL statement
				$sql = "update MN_Covers set ";
				$sql .= "id = ".$mnc->id.", ";
				$sql .= "user_id = ".$mnc->user_id.", ";
				$sql .= "po_id = ".$mnc->po_id.", ";
				$sql .= "description = '".mysqli_real_escape_string($this->db, $mnc->description)."', ";
				$sql .= "destination = '".mysqli_real_escape_string($this->db, $mnc->destination)."', ";
				$sql .= "picture1 = '".$mnc->picture1."', ";
				$sql .= "picture1_small = '".$mnc->picture1_small."', ";
				$sql .= "picture1_tn = '".$mnc->picture1_tn."', ";
				$sql .= "picture2 = '".$mnc->picture2."', ";
				$sql .= "picture2_small = '".$mnc->picture2_small."', ";
				$sql .= "picture2_tn = '".$mnc->picture2_tn."', ";
				$sql .= "cancel_date = '".$mnc->cancel_date."', ";
				$sql .= "lastmod_datetime = '".$mnc->lastmod_datetime."',  ";
				$sql .= "favorite_flag = '".$mnc->favorite_flag."',  ";
				$sql .= "needs_research_flag = '".$mnc->needs_research_flag."',  ";
				$sql .= "label_num = '".$mnc->label_num."' ";
				$sql .= "where id = ".$mnc->id ;
				//echo "SQL:$sql<br>";
				if (mysqli_query($this->db, $sql)) {
					return true ;
				}
				else {
					$this->error = "MN_Cover Update failed : ".mysqli_error($this->db);
					Write_Error($this->error);
					echo "ERROR:".$this->error."<br>";
					return FALSE;
				}
			}
			else {
				$this->error = "The MN Cover record was not found when trying to Update";
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}		
		}

		function Delete ($prm_mn_cover) {
			//This function will delete a row in the MN_Covers table
			
			$mnc = new MN_Cover;
			$mnc = $prm_mn_cover;

			if ($this->Exists($prm_mn_cover)) {
				//Build SQL statement
				$sql = "delete from MN_Covers  ";
				$sql .= "where id = ".$prm_mn_cover ;
				//echo "SQL:$sql<br>";
				if (mysqli_query($this->db, $sql)) {
					return true ;
				}
				else {
					$this->error = "MN_Cover Delete failed : ".mysqli_error($this->db);
					Write_Error($this->error);
					echo "ERROR:".$this->error."<br>";
					return FALSE;
				}
			}
			else {
				$this->error = "The MN Cover record was not found when trying to Delete";
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}		
		}

	
		function Get_Next() {
			//this function will retrieve the next bank_account_id
			$ctl = new MN_Cover_CTL ;
			$ctls = new MN_Cover_CTLs ;
			
			if ($ctl = $ctls->Fetch()) {
				$ctl->id = $ctl->id + 1 ;
				if (!$ctls->Update($ctl)) {
					$error = "Update failed getting next MN_Cover id";
					Write_Error($error);
					echo "ERROR:".$error."<br>";
					return FALSE;
				}
				else {
					return $ctl->id ;
				}
			}
			else {
				//create a new Bank_Account_CTL record
				$ctl = new MN_Cover_CTL ;
				$ctl->id = 1 ;
				
				if (!$ctls->Create($ctl)) {
					$error = "Create failed getting next MN_Cover id";
					Write_Error($error);
					echo "ERROR:".$error."<br>";
					return FALSE;
				}
				else {
					return $ctl->id ;
				}
			}
		}

		function Get_Next_Doc_ID() {
			//this function will retrieve the next MN_Doc_CTL value
			$ctl = new MN_Doc_CTL ;
			$ctls = new MN_Doc_CTLs ;
			
			if ($ctl = $ctls->Fetch()) {
				$ctl->id = $ctl->id + 1 ;
				if (!$ctls->Update($ctl)) {
					$error = "Update failed getting next MN_Doc_CTL id";
					Write_Error($error);
					echo "ERROR:".$error."<br>";
					return FALSE;
				}
				else {
					return $ctl->id ;
				}
			}
			else {
				//create a new MN_Doc_CTL record
				$ctl = new MN_Doc_CTL ;
				$ctl->id = 1 ;
				
				if (!$ctls->Create($ctl)) {
					$error = "Create failed getting next MN_Doc_CTL id";
					Write_Error($error);
					echo "ERROR:".$error."<br>";
					return FALSE;
				}
				else {
					return $ctl->id ;
				}
			}
		}
		
		function Validate ($prm_cover) {
			//This function will validate the data in the Cover object passed on input
			
			$cvr = new MN_Cover ;
			$po = new MN_Post_Office ;
			$pos = new MN_Post_Offices ;
			
			//Initialize Variables
			$errors = "";
			$cvr = $prm_cover ;
			
			//Validate the data
			if (trim($cvr->destination) == "") {
				$errors .= "<li>The destination can't be blank</li>";
			}
			if ((strpos(strtoupper($cvr->destination),"<")) > -1) {
				$errors .= "<li>An invalid character was found in the Destination</li>";
			}
			if (!mws_is_date($cvr->cancel_date)) {
				$errors .= "<li>An invalid date was entered.  It must be in MM/DD/YYYY format.</li>";
			}
			if (trim($cvr->label_num) != "") {
				if (!is_numeric(trim($cvr->label_num))) {
					$errors .= "<li>An invalid character was entered in the Label Number.</li>";
				}
			}
			return $errors;
		}
	}
	
	// MWS Cover CTL
	
	class MN_Cover_CTL {
		
		public $id ;
		
	}
	

	class MN_Cover_CTLs extends MNC {
		
		public $error ;
		public $db ;
		
		function __construct(){
			$this->db = MNC::OpenDatabase();
		}
		
		function Fetch() {
			//This function will retrieve a MN_Cover_CTL record from the DB
			$mcctl = new MN_Cover_CTL;
			
			$sql = "select * from MN_Cover_CTL";
			$result = mysqli_query($this->db, $sql);   
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$mcctl->$key = $value;
				}
				//print_r($this);
				return $mcctl ;
			}
			else {
				return false ;
			}
			
		}
		
		function Create($prm_mn_cover_ctl) {
			//This function will create a row in the ecb_Bank_Account_CTL table
			
			$mnc = new MN_Cover_CTL;
			$mnc = $prm_mn_cover_ctl;
			
			//Build insert statement
			$sql = "insert into MN_Cover_CTL (";
			$sql .= "id) ";
			$sql .= "values (";
			$sql .= $mnc->id.") ";

			if (mysqli_query($this->db, $sql)) {
				return true ;
			}
			else {
				$this->error = "MN_Cover_CTL Insert failed : ".mysqli_error($this->db);
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
		}
		
		function Update ($prm_mn_cover_ctl) {
			//This function will update a row in the MN_Cover_CTL table
			
			$mnc = new MN_Cover_CTL;
			$mnc = $prm_mn_cover_ctl;

			//Build SQL statement
			$sql = "update MN_Cover_CTL set ";
			$sql .= "id = ".$mnc->id ;

			if (mysqli_query($this->db, $sql)) {
				return true ;
			}
			else {
				$this->error = "MN_Cover_CTL Update failed : ".mysqli_error($this->db);
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
		}
	}

	// MWS Doc CTL
	
	class MN_Doc_CTL {
		
		public $id ;
		
	}
	
	class MN_Doc_CTLs extends MNC {
		
		public $error ;
		public $db ;
		
		function __construct(){
			$this->db = MNC::OpenDatabase();
		}
		
		function Fetch() {
			//This function will retrieve a MN_Doc_CTL record from the DB
			
			$md_ctl = new MN_Doc_CTL ;
			
			$sql = "select * from MN_Doc_CTL";
			$result = mysqli_query($this->db, $sql);
			if (mysqli_num_rows($result)>0){
				$row = mysqli_fetch_assoc($result);
				foreach ($row as $key=>$value) {
					$md_ctl->$key = $value;
				}
				//print_r($this);
				return $md_ctl ;
			}
			else {
				return false ;
			}
		}
		
		function Create($prm_mn_doc_ctl) {
			//This function will create a row in the MN_Doc_CTL table
			
			$mnc = new MN_Doc_CTL;
			$mnc = $prm_mn_doc_ctl;
			
			//Build insert statement
			$sql = "insert into MN_Doc_CTL (";
			$sql .= "id) ";
			$sql .= "values (";
			$sql .= $mnc->id.") ";

			if (mysqli_query($this->db, $sql)) {
				return true ;
			}
			else {
				$this->error = "MN_Doc_CTL Create failed : ".mysqli_error($this->db);
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
		}
		
		function Update ($prm_mn_doc_ctl) {
			//This function will update a row in the MN_Doc_CTL table
			
			global $wpdb ;
			$mnc = new MN_Doc_CTL;
			$mnc = $prm_mn_doc_ctl;

			//Build SQL statement
			$sql = "update MN_Doc_CTL set ";
			$sql .= "id = ".$mnc->id ;

			if (mysqli_query($this->db, $sql)) {
				return true ;
			}
			else {
				$this->error = "MN_Doc_CTL Update failed : ".mysqli_error($this->db);
				Write_Error($this->error);
				echo "ERROR:".$this->error."<br>";
				return FALSE;
			}
		}
	}


?>
