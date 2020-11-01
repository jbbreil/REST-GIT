<?php
class Works {

    private $db;
    
    private $id;
    private $date;
    private $company;
    private $title;


    function __construct(){
        // Databas anslutning
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        if($this->db->connect_errno > 0){
            die("Fel vid anslutning: " . $this->db->connect_error);
        } 

    }
  
    // läs av kurser 
   function getWork(){
        $sql = "SELECT * FROM work ORDER BY id DESC;";  

        $result = $this->db->query($sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);

    }

    // läs av en specifik kurs via dess id 
	function getWorkById($id){
		// Konvertera till heltal 
		$id = intval($id);
		// SQL-fråga för att läsa ut en specifik rad utifrån en specifik id i tabellen work
		$sql = "SELECT * FROM work WHERE id=$id;";
		// Lagra mysqli:s svar i variabel $result 
		$result = $this->db->query($sql) or die('Fel vid SQL-fråga');
		// Få tillbaka en specik rad 
		return mysqli_fetch_array($result, MYSQLI_ASSOC);
	}

    // Skapa kurs
    function createWork($date, $company, $title){ 
        // Kontrollera om värden är korrekta 
        if(!$this->setDate($date)) { return false; }
        if(!$this->setCompany($company)) { return false; }
        if(!$this->setTitle($title)) { return false; }
        // SQL-fråga 
        $sql = "INSERT INTO work (date, company, title) 
        VALUES('$date', '$company', '$title')";
        // Skicka SQL-fråga och lagra resultatet i variabelt result 
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result; 
    }

    // Uppdatera kurs
    function updateWork($date, $company, $title, $id){
        $id = intval($id);
        // Kontrollera om värden är korrekta 
        if(!$this->setDate($date)) { return false; }
        if(!$this->setCompany($company)) { return false; }
        if(!$this->setTitle($title)) { return false; }

        $sql = "UPDATE work SET date = '{$date}', company = '{$company}', 
        title = '{$title}'
        WHERE ID = {$id}";
        
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Radera kurs
    function deleteWork($id){
        $sql = "DELETE FROM work WHERE id = $id";
        return $this->db->query($sql);
    }

    /* Skydd mot sql-injection 
    kontroll om specialtecken i en sträng åt en SQL-fråga genom att ersätta citaten (‘) med bakåtvänd snedstreck (\)*/
    function setDate($date){
		if($date != ""){
			$this->date = mysqli_real_escape_string($this->db, $date);
			return true;
		}else{
			return false;
		}
    }
    function setCompany($company){
		if($company != ""){
			$this->company = mysqli_real_escape_string($this->db, $company);
			return true;
		}else{
			return false;
		}
	}
	function setTitle($title){
		if($title != ""){
			$this->title = mysqli_real_escape_string($this->db, $title);
			return true;
		}else{
			return false;
		}
    }
}
