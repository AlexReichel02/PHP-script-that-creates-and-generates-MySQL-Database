<!-------------------------------------------------------------------
   session2.php
   This page: 
     - demonstrates prsistence of a session variable
 -------------------------------------------------------------------->
<?php
if (!isset($_SESSION)) {
   session_start();
}
?>
<html>
  <head>
	 <title>Form Demo</title>
    <style>
      body {
        background-color: rgb(220, 220, 220);
        background-size: 100%;
      }
    </style>
  </head>
  <body>
  <center><h2>Query Form</h2></center>
  
  <form method="post">
   Select one of the following: <br/><br/>
   Show All records? <input type="checkbox" name="allRecords" value="Yes" /><br/>
   Enter Zip : <input type="text" name="zip"><br/>
   Enter Age : <input type="text" name="age"><br/>
  <input type="submit" value="CLick to issue query" name="Submit1"> <br/>

</form>
    <?php

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

if(isset($_POST['Submit1'])){ 
  
    $dbhandle = connectToDB($dbparams[0],$dbparams[1],$dbparams[2],$dbparams[3]); 

    if(isset($_POST['allRecords']) &&  $_POST['allRecords'] == 'Yes') {
      $result = mysqli_query($dbhandle, "SELECT first, last, street ,city ,state ,zip, age,salary FROM project5Table" );
    }

    if(!empty($_POST["zip"])){
        $result = mysqli_query($dbhandle, "SELECT first, last, street ,city ,state ,zip, age,salary FROM project5Table where zip=".$_POST["zip"] );
    }

    if(!empty($_POST["age"])){
        $result = mysqli_query($dbhandle, "SELECT first, last, street ,city ,state ,zip, age,salary FROM project5Table where age=".$_POST["age"] );
    }

    while ($row = mysqli_fetch_array($result)) {
     for ($i = 0; $i <= 7; $i++) {
      print($row[$i]);
      print(" ");
    }
      print("<br>");

    }

    mysqli_close($dbhandle);
    }
   
  ?>
  </form>

  </body> 


  