<?php include("../includes/config.php"); ?>
<?php

// åtkomst till filen och vilken vilken typ av innehåll den ska returnera
// read.php-fil läsas av vem som helst (asterisk * betyder alla) och returnerar data i JSON-format
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// tillåt att testköra webbtjänsten från annan domän än den är publicerad till.
header("Access-Control-Allow-Origin: *");
// aktivering av verben på serversidan 
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// kontrollerar om en id-argument existera i själva i URL:en
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
 
// initialisera work-objekt för att skicka SQL-frågor till databasen 
// skickas med databasanslutning som argument
$works = new Works();
// funktion file_get_contents används för att läsa en fil i en sträng
// php://input läs in raw data(information lagtrad in i en databas) från den SQL-frågan
$json = file_get_contents('php://input');
// Konverterar JSON-sträng till en PHP-array eller -objekt
$data = json_decode($json, true);
 


// REQUEST_METHOD tar in förfrågningsmetoden som används för att komma åt sidan (GET, HEAD, POST, PUT)
switch ($_SERVER["REQUEST_METHOD"]){

/***********************************/
    case "GET": /* SELECT */
        // kontrollera om det existerar någon id i själva URL:en
        if(isset($id)) {
            // anropa en metod(funktion) av works(objekt) kallad getworks som läser in en specifik rad från tabellen
            $result = $works->getworkById($id);
        } else {
            // metod som läges all data från tabellen 
            $result = $works->getWork();
        }  
        // kontroll om antalet element i en array
        if(sizeof($result) > 0 ) { 
            http_response_code(200); 
        } else 
            { http_response_code(404); // fel meddelande
                $result = array("message" => "Inga jobb hittades"); 
        } 
        break;

/***********************************/
    case "POST": /* INSERT */

        // anropa createWork metod för att lägga till en rad i databastabellen
        if ($works->createWork($data['date'], $data['company'], $data['title'])) { // array access 
            http_response_code(201);
            $result = array("message" => "jobb är skapad!");
        } else {
            http_response_code(500); 
            $result = array("message" => "jobb är inte skapad!");
        }
        
        break;
    

/***********************************/

    case "PUT": /* UPDATE */

        // kontrollera om det existerar någon id i själva URL:en
        if(!isset($id)) {
            http_response_code(500); 
            $result = array("message" => "ingen id skickades");
        } else {


        // anropa createWork metod för att lägga till en rad i databastabellen
        if ($works->updateWork($data['date'], $data['company'], $data['title'], $id)) { // object access 
            http_response_code(200);
            $result = array("message" => "jobb är uppdaterad!");
        } else {
            http_response_code(500); 
            $result = array("message" => "jobb är inte uppdaterad!");
        }
    }
        break;
    
/***********************************/

    case "DELETE":

        // kontrollera om det existerar någon id i själva URL:en
        if(!isset($id)) {
            http_response_code(500); 
            $result = array("message" => "ingen id skickades");
        } else {
            // anropa metoden för att radera en specifik rad i databastabellen 
            if ($works->deleteWork($id)) {
                http_response_code(200);
                $result = array("message" => "jobb är raderad");
            } else {
                http_response_code(500); 
                $result = array("message" => "jobb är inte raderad");
            }
        }  
        break;

/***********************************/
    // om inga 
    default: echo 'Ingen match!'; 

    break;
}


// returnerar resultatet($result) som JSON
echo json_encode($result, true);

?>
