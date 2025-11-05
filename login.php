<?php
session_start();

   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('test.db');
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   }
      
   

   $tableExists = $db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='LOGININFO'");

   if(!$tableExists) {
      $sql =<<<EOF
      CREATE TABLE LOGININFO
      (ID INT PRIMARY KEY     NOT NULL,
      NAME           TEXT    NOT NULL,
      SURNAME            TEXT,
      EMAIL        CHAR(50) UNIQUE,
      PASSWORD CHAR(50)) ;
EOF;

      $ret = $db->exec($sql);
      if(!$ret){
         echo $db->lastErrorMsg();
      } 
     
    
   }

?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internetveikals</title>
</head>
<body>


<div class="container_main">

<label>Login</label>
 <form  method="post">


   
                <label for="surname">E-pasts:</label>
        <input type="text" id="email" name="email" placeholder="Jūsu e-pasts" required><br>

   
        <label for="password">Parole:</label>
        <input type="password" id="password" name="password" placeholder="Parole" required><br>

        <input type="checkbox" name="rememberme" value="1"> Atcerēties mani

        <button type="submit" name="submit">Login</button>


</form>


<?php 

$loggedIn = false;



 if (isset($_POST["submit"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
      $stmt = $db->prepare('SELECT * FROM LOGININFO WHERE EMAIl = :email');
      $stmt->bindValue(':email', $email);
          $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_ASSOC);
       
if ($row) {
  
        if (password_verify($password, $row['PASSWORD'])) {
            $loggedIn = true;
        } else {
            echo "Nepareizs epasts vai parole";
            $loggedIn = false;
        }
    } else {
        echo "Nepareizs epasts vai parole";
    }

 }
   




if ($loggedIn) {
   if (!empty($_POST["rememberme"]))
            {

      
                setcookie("user_login", $email, time() +
                                    (10 * 365 * 24 * 60 * 60));

         
                setcookie("user_password", $row['PASSWORD'], time() +
                                    (10 * 365 * 24 * 60 * 60));

      
                $_SESSION["password"] = $row['PASSWORD'];

            }
            else
            {
                if (isset($_COOKIE["user_login"]))
                {
                    setcookie("user_login", "");
                }
                if (isset($_COOKIE["user_password"]))
                {
                    setcookie("user_password", "");
                }
            }
            header("location:products.php");
        }
        else
        {
            $message = "Invalid Login Credentials";
        }



   $db->close();
 





?>



</div>


   
</body>
</html> 