<?php include 'header.php'; ?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kalenteri";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Rekisteröinti onnistui";
    } else {
        echo "Virhe: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekisteröidy</title>
</head>
<body>
    <h1>Rekisteröidy</h1>
    <form action="register.php" method="post">
        <label for="username">Käyttäjänimi:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="password">Salasana:</label>
        <input type="password" id="password" name="password" required><br>
        
        <label for="email">Sähköposti:</label>
        <input type="email" id="email" name="email" required><br>
        
        <input type="submit" value="Rekisteröidy">
    </form>
</body>
</html>


<!-- Tää loppukoodi on Jukan file -->
<?php
$display = "d-none";
$message = "";
$success = "";
$lisays = $lisattiin_token = $lahetetty = false;


function hae_kuva($kentta){   
/* Huom. foreach-silmukka on tässä malliksi, ei valmis.
   Nimen tarkistukseen ei ole tässä koodia. */
    $kentat_tiedosto = $GLOBALS['kentat_tiedosto'];   
    $allowed_images = $GLOBALS['allowed_images'];
    $virhe = false;   
    $image = "";
    foreach ($kentat_tiedosto as $kentta){
    if (!isset($_FILES[$kentta])) continue;    
    if (is_uploaded_file($_FILES[$kentta]['tmp_name'])) {
       $random = randomString(3);
       $maxsize = PROFIILIKUVAKOKO;
       $temp_file = $_FILES[$kentta]["tmp_name"];       
       $filesize = $_FILES[$kentta]['size'];
       $pathinfo = pathinfo($_FILES[$kentta]["name"]);
       $filetype = strtolower($pathinfo['extension']);
       $image = $pathinfo['filename']."_$random.$filetype";
       $target_dir = PROFIILIKUVAKANSIO;
       $target_file = "$target_dir/$image";
       /* Check if image file is a actual image or fake image */
       if (!$check = getimagesize($temp_file)) $virhe = "Kuva ei kelpaa.";
       elseif (file_exists($target_file)) $virhe = "Kuvatiedosto on jo olemassa.";
       elseif (!in_array($filetype,$allowed_images)) $virhe = "Väärä tiedostotyyppi.";
       elseif ($filesize > $maxsize) $virhe = "Kuvan koon tulee olla korkeintaan 5 MB.";
       debuggeri("File $image,mime: {$check['mime']}, $filetype, $filesize tavua");
       if (!$virhe){
          if (!move_uploaded_file($temp_file,$target_file)) 
             $virhe = "Kuvan tallennus ei onnistunut.";
          } 
       }
       }
    return [$image,$virhe];
    }


if (isset($_POST['painike'])){
[$errors,$values] = validointi($kentat);  
extract($values);

if (empty($errors['password2']) and empty($errors['password'])) {
    if ($_POST['password'] != $_POST['password2']) {
        $errors['password2'] = $virheilmoitukset['password2']['customError'];
        }
    }
    
if (empty($errors)){
    [$image,$virhe] = hae_kuva($kentat_tiedosto);
    if ($virhe) $errors['image'] = $virhe;
    $image = ($image) ? "'$image'" : "NULL";
    }   
    
if (empty($errors)) {    
$query = "SELECT 1 FROM users WHERE email = '$email'";
$result = $yhteys->query($query);
if ($result->num_rows > 0) {
    $errors['email'] = $virheilmoitukset['email']['emailExistsError'];
    }

/*
$query = "SELECT 1 FROM users WHERE firstname = '$firstname' AND lastname = '$lastname'";
$result = $yhteys->query($query);
if ($result->num_rows > 0) {
    debuggeri($query);
    $errors['firstname'] = $virheilmoitukset['firstname']['nameExistsError'];
    $errors['lastname'] = $virheilmoitukset['lastname']['nameExistsError'];
    }    
*/
}    

debuggeri($errors);    
if (empty($errors)) {
    $created = date('Y-m-d H:i:s');
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (firstname, lastname, email, image, created, password) VALUES ('$firstname', '$lastname', '$email', $image, '$created', '$password')";
    debuggeri($query);
    //$result = $yhteys->query($query);
    $result = mysqli_my_query($query);
    $lisays = $yhteys->affected_rows;
    debuggeri("lisays:$lisays");
    }

if ($lisays) {  
    $id = $yhteys->insert_id;
    $token = md5(rand().time());
    $query = "INSERT INTO signup_tokens (users_id,token) VALUES ($id,'$token')";
    debuggeri($query);
    $result = $yhteys->query($query);
    $lisattiin_token = $yhteys->affected_rows;
    }

if ($lisattiin_token) {
    $msg = "Vahvista sähköpostiosoitteesi alla olevasta linkistä:<br><br>";
    $msg.= "<a href='http://$PALVELIN/$PALVELU/$LINKKI_VERIFICATION?token=$token'>Vahvista sähköpostiosoite</a>";
    $msg.= "<br><br>t. $PALVELUOSOITE";
    $subject = "Vahvista sähköpostiosoite";
    $lahetetty = posti($email,$msg,$subject);
    }   

if ($lahetetty){
    $message = "Tiedot on tallennettu. Sinulle on lähetty antamaasi sähköpostiosoitteeseen ";
    $message.= "vahvistuspyyntö. Vahvista siinä olevasta linkistä sähköpostiosoitteesi.";
    $success = "success";
    //header("Location: ./rekisterointikuittaus.php?message=$message&success=$success");
    //exit;
    }
elseif ($lisays) {
    /* Huom. oikeammin ohjataan vahvistuspyyntöön */    
    $message = "Tallennus onnistui!";
    $success = "light";
    }
else {
    $message = "Tallennus epäonnistui!";
    $success = "danger";
    }
$display = "d-block";

/*
var_export($_POST);
var_export($_FILES);
echo "<br>";
var_export($errors);*/
}

?>