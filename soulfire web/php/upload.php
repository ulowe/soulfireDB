<?php
phpinfo();
// Check if the form is submitted
if(isset($_POST["submit"])){

  // Get the file information
  $fileName = basename($_FILES["file"]["name"]);
  $fileType = pathinfo($fileName,PATHINFO_EXTENSION);

  // Check if the file is a CSV file
  if($fileType != "csv"){
      echo "Sorry, only CSV files are allowed.";
  } else {
      // Connect to the database
      $servername = "localhost";
      $username = "username";
      $password = "password";
      $dbname = "database_name";
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check if the connection was successful
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Upload the file to the server
      $tmpName = $_FILES["file"]["tmp_name"];
      move_uploaded_file($tmpName,"uploads/".$fileName);

      // Open the CSV file and insert the data into the database
      $csv = array_map('str_getcsv', file("uploads/".$fileName));
      foreach($csv as $row){
          $sql = "INSERT INTO table_name (column1, column2, column3) VALUES ('".$row[0]."', '".$row[1]."', '".$row[2]."')";
          $conn->query($sql);
      }

      // Close the database connection
      $conn->close();

      echo "The file ".$fileName." has been uploaded and the data has been sent to the database.";
  }
}
?>

<!-- HTML form to upload the CSV file -->
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv">
    <input type="submit" name="submit" value="Upload">
</form>
