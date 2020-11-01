<?php
class Courses {

    private $db;
    
    private $id;
    private $code;
    private $name;
    private $progression;
    private $syllabus;

    function __construct(){
        // Databas anslutning
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DBDATABASE);
        if($this->db->connect_errno > 0){
            die("Fel vid anslutning: " . $this->db->connect_error);
        } 

    }
  
    // läs av kurser 
   function getCourse(){
        $sql = "SELECT * FROM courses ORDER BY id DESC;";  

        $result = $this->db->query($sql);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);

    }

    // Få en specifik kurs via dess id 
	function getCourseById($id){
		// Konvertera till heltal 
		$id = intval($id);
		// SQL-fråga för att läsa ut en specifik inlägg utifrån en specifik id i tabellen post
		$sql = "SELECT * FROM courses WHERE id=$id;";
		// Lagra mysqli:s svar i variabel $result 
		$result = $this->db->query($sql) or die('Fel vid SQL-fråga');
		// Få tillbaka en specik rad 
		$row = mysqli_fetch_array($result); // matcha upp id med kursen
		
		return $row; 
	}

    // Skapa kurs
    function createCourse($code, $name, $progression, $syllabus){ 
        // Kontrollera om värden är korrekta 
		if(!$this->setCode($code)) { return false; }
        if(!$this->setName($name)) { return false; }
        if(!$this->setProgression($progression)) { return false; }
        if(!$this->setSyllabus($syllabus)) { return false; }
        // SQL-fråga 
        $sql = "INSERT INTO courses (code, name, progression, syllabus) 
        VALUES('$code', '$name', '$progression', '$syllabus')";
        // Skicka SQL-fråga och lagra resultatet i variabelt result 
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result; 
    }

    // Uppdatera kurs
    function updateCourse($code, $name, $progression, $syllabus, $id){
		$id = intval($id);
        // Kontrollera om värden är korrekta 
        if(!$this->setCode($code)) { return false; }
        if(!$this->setName($name)) { return false; }
        if(!$this->setProgression($progression)) { return false; }
        if(!$this->setSyllabus($syllabus)) { return false; }

        $sql = "UPDATE courses SET code = '{$code}', name = '{$name}', 
        progression = '{$progression}', syllabus = '{$syllabus}'
        WHERE ID = {$id}";
        
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Radera kurs
    function deleteCourse($id){
        $sql = "DELETE FROM courses WHERE id = $id";
        return $this->db->query($sql);
    }

    /* Skydd mot sql-injection 
    kontroll om specialtecken i en sträng åt en SQL-fråga genom att ersätta citaten (‘) med bakåtvänd snedstreck (\)*/
    function setName($name){
		if($name != ""){
			$this->name = mysqli_real_escape_string($this->db, $name);
			return true;
		}else{
			return false;
		}
    }
    function setProgression($progression){
		if($progression != ""){
			$this->progression = mysqli_real_escape_string($this->db, $progression);
			return true;
		}else{
			return false;
		}
	}
	function setCode($code){
		if($code != ""){
			$this->code = mysqli_real_escape_string($this->db, $code);
			return true;
		}else{
			return false;
		}
    }
    function setSyllabus($syllabus){
		if($syllabus != ""){
			$this->syllabus = mysqli_real_escape_string($this->db, $syllabus);
			return true;
		}else{
			return false;
		}
	}
    
}
