
<?php
if (!isset($_SESSION)) {
   session_start();
}
?>
<html>
  <head>
	 <title>Database Operations</title>
    <style>
      body {
        background-color: rgb(220, 220, 220);
        background-size: 100%;
      }
    </style>
  </head>
  <body>
    <?php

print"<center><h2>Create Table and Data</h2></center>";
print'<form action="formTest.php" method="POST">';
print'<input type="submit" value="Go to the query page"> <br/><br/></form>';

/*********************************************************************
   connectToDB($wdb_host,$wdb_user,$wdb_pass,$wdb_name): mysqli
 *********************************************************************/
if (!function_exists('connectToDB'))   {
  function connectToDB($wdb_host,$wdb_user,$wdb_pass,$wdb_name): mysqli
    {
       $mysqli = new mysqli($wdb_host,$wdb_user,$wdb_pass,$wdb_name);
       if ($mysqli -> connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
          exit();
        }
       return $mysqli; 
    }
 }

 mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
 $myfile = fopen('./auth/vals', "r") or die("Unable to open database settings file."); 
 $theParams=fread($myfile, filesize('./auth/vals'));
 fclose($myfile);
 $dbparams=explode(',',$theParams);

 try
 {
   $mysqli = connectToDB($dbparams[0],$dbparams[1],$dbparams[2],$dbparams[3]); 
  
   
   $query = "Drop Table project5Table";
   print "query: ".$query."<br>";
   $stmt = $mysqli->prepare($query);
   $stmt->execute();

  $query = "CREATE TABLE `project5Table` ( `first` varchar(20) NOT NULL, `last` varchar(20) NOT NULL, `street` varchar(50) NOT NULL, `city` varchar(20) NOT NULL, `state` varchar(5) NOT NULL, `zip` text NOT NULL, `age` varchar(5) NOT NULL, `salary` varchar(15) NOT NULL) ENGINE=CSV DEFAULT CHARSET=utf8mb4";
  print "query: ".$query."<br>";
  $stmt = $mysqli->prepare($query);
  $stmt->execute();

  $csvFilePath = "data.csv";
  $file = fopen($csvFilePath, "r");
  while (($row = fgetcsv($file,null,';')) !== FALSE) {
    print("<br>");
    print "Reading File Data";
    print("<br>");
    foreach ($row as $value) {
      $str_arr = preg_split ("/\,/", $value); 
      print($value);
      print("<br>");
      $mysqli->query('INSERT INTO project5Table VALUES ("'.$str_arr[0].'","'.$str_arr[1].'","  '.$str_arr[2].' "," '.$str_arr[3].' ","  '.$str_arr[4].' "," '.$str_arr[5].' "," '.$str_arr[6].' "," '.$str_arr[7].'  ")');
    }

  }

 } catch (mysqli_sql_exception $e) { 
   echo "MySQLi Error Code: " . $e->getCode() . "<br />";
   echo "Exception Msg: " . $e->getMessage();
   exit; // exit and close connection.
 }

 $mysqli -> close();
 unset($_POST['hiddenField']);


?>