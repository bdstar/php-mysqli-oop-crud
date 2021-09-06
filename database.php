<?php
class Database{

	public $hostname = "localhost";
	public $username = "root";
	public $passowrd = "";
	public $database = "test";
	public $connection;
	public $message = array(); 

	//Connect with database for mysql database
	public function __construct()
	{
		$this->connection = new mysqli($this->hostname, $this->username, $this->passowrd, $this->database);

		//Check Connection
		if($this->connection->connect_errno){
			die("Connection Fail ".$this->connection->connect_error);
		}
		else{
			//echo "Connection is ok <br>";
		}
	}// End of constructor


	//Function to Create Table
	public function CreateTable($sql){
		//Create Table
		if ($this->connection->query($sql) === TRUE) {
		    echo "Table has been created successfully";
		} else {
		    echo "Error to creating table: ".$this->connection->error;
		}
		echo "<br>";
	}//End of function CreateTable



	//Fetch data by accepting table name and columns(1 dimentional array) name
	public function fetch($table, array $columns, array $condition){
		
		$where_condition = "";
		if(!empty($condition)){
			$where_condition =" WHERE ";
			foreach ($condition as $key => $value) {
				$where_condition .= $key." = '".$value."' AND ";
			}
			$where_condition = rtrim($where_condition, "AND ");
		}
		$columns = (empty($columns)) ? "*" : rtrim(implode(",",$columns), ",");
		$result=$this->connection->query("SELECT $columns FROM $table $where_condition");
	
		if($this->connection->errno){
			die("Fail Select ".$this->connection->error);
		}

		//return tow dimentional array as required columns result
		return $result->fetch_all(MYSQLI_ASSOC);
	}




	# Insert Data within table by accepting TableName and Table column => Data as associative array
	public function insert($tblname, array $val_cols){

		$keysString = implode(", ", array_keys($val_cols));

		$i=0;
		foreach($val_cols as $key=>$value) {
			$StValue[$i] = "'".$value."'";
		    $i++;
		}

		$StValues = implode(", ",$StValue);
		
		if (mysqli_connect_errno()) {
		  $this->message['msg'] = "Failed to connect to MySQL: " . mysqli_connect_error();
		  $this->message['return'] = false;
		  return $this->message;
		}

		//Perform Insert operation
		if($this->connection->query("INSERT INTO $tblname ($keysString) VALUES ($StValues)") === TRUE){
			$this->message['msg'] =  "New record has been inserted successfully!";
			$this->message['return'] = true;
			return $this->message;
		}else{
			$this->message['msg'] =  "Error ".$this->connection->error;
			$this->message['return'] = false;
			return $this->message;			
		}
	}//End of function insert




	//Delete data form table; Accepting Table Name and Keys=>Values as associative array
	public function delete($tblname, array $val_cols){
		//Append each element of val_cols associative array 
		$i=0;
		foreach($val_cols as $key=>$value) {
			$exp[$i] = $key." = '".$value."'";
		    $i++;
		}

		$Stexp = implode(" AND ",$exp);

		//Perform Delete operation
		if($this->connection->query("DELETE FROM $tblname WHERE $Stexp") === TRUE){
			if(mysqli_affected_rows($this->connection)){
				$this->message['msg'] =  "Record has been deleted successfully";
				$this->message['return'] = true;
				return $this->message;
			}
			else{
				$this->message['msg'] = "The Record you want to delete is no loger exists";
				$this->message['return'] = false;
				return $this->message;
			}
		}
		else{
			$this->message['msg'] = "Error to delete".$this->connection->error;
			$this->message['return'] = false;
			return $this->message;	
		}	
	}




	//Update data within table; Accepting Table Name and Keys=>Values as associative array
	public function update($tblname, array $set_val_cols, array $cod_val_cols){
		
		//append set_val_cols associative array elements 
		$i=0;
		foreach($set_val_cols as $key=>$value) {
			$set[$i] = $key." = '".$value."'";
		    $i++;
		}

		$Stset = implode(", ",$set);

		//append cod_val_cols associative array elements
		$i=0;
		foreach($cod_val_cols as $key=>$value) {
			$cod[$i] = $key." = '".$value."'";
		    $i++;
		}

		$Stcod = implode(" AND ",$cod);

		//Update operation
		if($this->connection->query("UPDATE $tblname SET $Stset WHERE $Stcod") === TRUE){
			if(mysqli_affected_rows($this->connection)){
				$this->message['msg'] =  "Record updated successfully!";
				$this->message['return'] = true;
				return $this->message;
			}
			else{
				$this->message['msg'] =  "The Record you want to updated is no loger exists";
				$this->message['return'] = false;
				return $this->message;
			}
		}else{
			$this->message['msg'] =  "Error to update ".$this->connection->error;
			$this->message['return'] = false;
			return $this->message;
		}
	}


	public function validation($fieldname, $fieldvalue, $fieldlength){
		$errorMSG = "";
		if (isset($fieldvalue) && empty($fieldvalue)) {
			$errorMSG .= "<li>".$fieldname." is required</<li>"; 
			$name_validation = false;
		}
		else{
			if(!preg_match("/^[a-zA-Z]([\w -]*[a-zA-Z])?$/i", $fieldvalue)){
				$errorMSG .= "<li>".$fieldname." only allow alphanumeric, hyphen, underscore and space</<li>"; 
				$name_validation = false;
			}
			else {
				if(strlen($fieldvalue)>100){
					$errorMSG .= "<li>".$fieldname." must be less than ".$fieldlength." characters</<li>"; 
					$name_validation = false;
				}
				else {
					$errorMSG .= "<li>".$fieldname." is correct</<li>"; 
					$name_validation = true;
				}
			}
		}
		$validation_result = array("errorMSG" => $errorMSG, "validation" => $name_validation);
		return $validation_result;
	}



	//Call destructor function 
	public function __destruct() {
		if($this->connection){
			// Close the connection
        	$this->connection->close();
        	//echo "Connection is release";
        }	
	}

}//end of class


// Create Connection
$obj = new Database();



if(isset($_GET["page"])){
	/* ======= START: Insert and Update Tag ======== */
	if($_GET["page"]=="tag" && ($_GET["action"]=="update" || $_GET["action"]=="insert")) {
		$name_validation = $description_validation = false;
		$errorMSG = "";

		if (isset($_GET['page']) && isset($_GET['action'])) {
			$page=$_GET['page'];
			$action=$_GET['action'];

			$name_validation_check = $obj->validation("Name", $_POST["name"], 100);
			$errorMSG .= $name_validation_check["errorMSG"];
			$name_validation = $name_validation_check["validation"];
			if($name_validation){$name = $_POST['name'];}

			$name_validation_check = $obj->validation("Description", $_POST["description"], 200);
			$errorMSG .= $name_validation_check["errorMSG"];
			$description_validation = $name_validation_check["validation"];
			if($description_validation){$description=$_POST['description'];}
		} else {
			$data_result ="failed";
			$data_msg = "There is no PAGE and ACTION";
			$data_message = "Illegal Operator";
			echo json_encode(['result'=>$data_result, 'msg'=>$data_msg, 'message'=>$data_message]); exit;
		}

		if($name_validation && $description_validation){
			$slug = $name;
			$tablename = "tag";
			$ColumnVal = array("name"=>$name,"slug"=>$slug,"description"=>$description);

			if($action=="update"){
				$condition = array("id"=>$_POST['id']);
				$dbreturn = $obj->update($tablename, $ColumnVal, $condition);
			}else{
				$dbreturn = $obj->insert($tablename, $ColumnVal);
			}

			if($dbreturn["return"]){
				$data_result = "success";
				$data_msg = $dbreturn['msg'];
				$data_message = $page." Successfully ".$action;
			}else{
				$data_result ="failed";
				$data_msg = $dbreturn['msg'];
				$data_message = $page." unable to ".$action;
			}
		}else{
			$data_result ="failed";
			$data_msg = "<ul>".$errorMSG."</ul>";
			$data_message = $page." unable to ".$action;
		}
		echo json_encode(['result'=>$data_result, 'msg'=>$data_msg, 'message'=>$data_message]);
	}
	/* ======= END: Insert and Update Tag ======== */




	/* ======= START: Delete Tag ======== */
	if($_GET["page"]=="tag" && $_GET["action"]=="delete") {
		$page=$_GET['page'];
		$action=$_GET['action'];
		$id=$_GET['id'];

		$tablename = "tag";
		$DelColumnVal = array("id"=>$id);
		$dbreturn = $obj->delete($tablename, $DelColumnVal);

		if($dbreturn["return"]){
			$data_result = "success";
			$data_msg = $dbreturn['msg'];
			$data_message = $page." Successfully ".$action;
		}else{
			$data_result ="failed";
			$data_msg = $dbreturn['msg'];
			$data_message = $page." unable to ".$action;
		}
		echo json_encode(['result'=>$data_result, 'msg'=>$data_msg, 'message'=>$data_message]);	
	}
	/* ======= END: Delete Tag ======== */
}
/*
// Assign table name
$tablename = "student";
// Create table query
$CreateTableSql = "CREATE TABLE $tablename(Roll INT,Name CHAR(50),Marks DOUBLE)";
//Call Create Table
$obj->CreateTable($CreateTableSql);
*/

/*
//Associative array for insert function
$InsColumnVal = array("Roll"=>4,"Name"=>'Zahan',"Marks"=>64.8);
//Call insert function to insert record
$obj->insert($tablename, $InsColumnVal);
*/


/*
//Associative array for delete function
$DelColumnVal = array("Roll"=>4,"Name"=>'Zahan');
//Call Delete function
$obj->delete($tablename, $DelColumnVal);
*/

/*
//Associative array to set query for update function
$set = array("Roll"=>5,"Marks"=>75.3);
//Associative array to condition query for update function
$condition = array("Roll"=>3,"Name"=>'Hatim');
//call update function
$obj->update($tablename, $set,$condition);
*/

/*
// Fetch data from the table
$show = $obj->fetch($tablename, array("Roll","Name","Marks"));
// Show data from table
echo "<pre>";
print_r($show);
echo "</pre>";
*/
?>