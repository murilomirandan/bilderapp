<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BilderApp</title>
</head>
<body>

<form action="index.php" method="post" enctype="multipart/form-data">
    <label for="namekuenstler">Name des Künstlers:in: </label>
    <input type="text" name="namekuenstler" id="namekuenstler"><br>
    <label for="namebild">Name des Bildes: </label>
    <input type="text" name="namebild" id="namebild"><br>
    <label for="pfadbild">Pfad zu diesem Bild: </label>
    <input type="file" name="pfadbild" id="pfadbild"><br>
    <label for="preis">Verkaufspreis: </label>
    <input type="text" name="preis" id="preis"><br>
    <input type="submit" value="Upload Image" name="submit">
    <input type="submit" value="Datei Image" name="dateien">
</form>

</body>
</html>

<?php
include 'class/Bild.php';
include 'config.php';

// If upload button is clicked ...
if (isset($_POST["submit"])) {
    // Get text
    $namekuenstler = $_POST['namekuenstler'];
    $namebild = $_POST['namebild'];
    $preis = $_POST['preis'];

    // Get image name
    $target_dir = "img/";
    $image = $_FILES['pfadbild']['name'];
    // image file directory
    $pfadbild = $target_dir . basename($image);
    $bildDateiType = strtolower(pathinfo($pfadbild, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["pfadbild"]["tmp_name"]);
        if($check === false) {
            echo "File is not an image.<br>";
            return;
        }
    }

    //Check if file already exists
    if (file_exists($pfadbild)) {
        echo "Sorry, file already exists.<br>";
        return;
    }

    // Check file size
    if ($_FILES["pfadbild"]["size"] > 500000) {
        echo "Sorry, your file is too large.<br>";
        return;
    }

    // Allow certain file formats
    if($bildDateiType != "jpg" && $bildDateiType != "png" && $bildDateiType != "jpeg"
        && $bildDateiType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
        return;
    }

    // Check if $uploadOk is set to 0 by an error
    if (move_uploaded_file($_FILES["pfadbild"]["tmp_name"], $pfadbild)) {
        $bild = new Bild($namekuenstler, $namebild, $pfadbild, $preis);
        echo  "<br>" . $bild . "<br>";
        $bild->upload();
    } else {
        echo "Failed to upload image<br>";
    }
}

if (isset($_POST["dateien"])){
    $allbild = Bild::getAllAsObjects();
    echo '<pre>';
    var_dump($allbild);
    echo '</pre>';
}
?>