<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<h1>Sample page</h1>
<?php
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    $file = '/tmp/sample-app.log';
    $message = file_get_contents('php://input');
    file_put_contents($file, date('Y-m-d H:i:s') . " Received message: " . $message . "\n", FILE_APPEND);
  }
  else
  {
?>
<?php
  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the EMPLOYEES table exists. */
  Verifytype($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the EMPLOYEES table. */
  $type = htmlentities($_POST['type']);

  if (strlen($type) {
    Addtype($connection, $type);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <table border="0">
    <tr>
      <td>TYPE</td>
    </tr>
    <tr>
      <td>
        <input type="text" name="TYPE" maxlength="45" size="30" />
      </td>
      <td>
        <input type="submit" value="Add Message" />
      </td>
    </tr>
  </table>
</form>

<!-- Display table data. -->
<table border="1" cellpadding="2" cellspacing="2">
  <tr>
    <td>TYPE</td>
  </tr>

<?php

$result = mysqli_query($connection, "SELECT * FROM type");

while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an employee to the table. */
function AddEmployee($connection, $type) {
   $n = mysqli_real_escape_string($connection, $type);

   $query = "INSERT INTO type (type) VALUES ('$n');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function Verifytype($connection, $dbName) {
  if(!TableExists("type", $connection, $dbName))
  {
     $query = "CREATE TABLE type (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         type VARCHAR(90),
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>                        
