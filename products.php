<?php
session_start();
   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('products.db');
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "Opened database successfully\n";
   }

   $tableExists = $db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='PRODUCTS'");

   if(!$tableExists) {
      $sql =<<<EOF
      CREATE TABLE PRODUCTS
      (ID INT PRIMARY KEY     NOT NULL,
      NAME           TEXT    NOT NULL,
      DESCRIPTION            TEXT,
      PRICE       DECIMAL(10,2),
      IMAGE_URL TEXT,
      CATEGORY TEXT);
      
EOF;

      $ret = $db->exec($sql);
      if(!$ret){
         echo $db->lastErrorMsg();
      } else {
         echo "Table created successfully\n";
      }
   }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/products.css">
    <title>Document</title>
</head>
<body>
    
    <div class="navbar">
<p>Internetveikals</p>
<p>Iepirkumu grozs</p>
<form method="post" style="display:inline;">
<button type="submit" name="logout">Logout</button>
</form>
<?php

if(isset($_POST['logout'])) {
   setcookie('user_login', '', time() - 3600, '/');
   setcookie('user_password', '', time() - 3600, '/');
   unset($_COOKIE['user_login']);
   unset($_COOKIE['user_password']);
   session_unset();
   session_destroy();
   header("location: index.php");
   echo '<script>window.location.href = window.location.pathname;</script>';
}

?>


</div>

<div class="container_main">
    <div class="container_filter">
<?php

   $db2 = new MyDB();
   if(!$db2) {
      echo $db2->lastErrorMsg();
   } else {
      echo "Opened database successfully\n";
   }

   $tableExists = $db2->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='CATEGORIES'");
   if ($tableExists) {
      echo "asdas";  
   }



   if(!$tableExists) {
      $sql =<<<EOF
      CREATE TABLE CATEGORIES
      (ID INTEGER PRIMARY KEY AUTOINCREMENT,
      CATEGORY TEXT);
      
EOF;

      $ret = $db2->exec($sql);
      if(!$ret){
         echo $db2->lastErrorMsg();
      } else {
         echo "Table created successfully\n";
      }
   }

$res2 = $db2->query("SELECT * FROM CATEGORIES");

while($row = $res2->fetchArray(SQLITE3_ASSOC) ) {
$category = htmlspecialchars($row['CATEGORY']);
var_dump($row);

}



?>
</div>
    <div class="container_products">
<?php 

$res = $db->query("SELECT * FROM PRODUCTS");
while($row = $res->fetchArray(SQLITE3_ASSOC) ) {
    $id = $row['ID'];
    $name = htmlspecialchars($row['NAME']);
    $price = htmlspecialchars($row['PRICE']);

    echo '<a class="product-card" href="product.php?id=' . urlencode($id) . '">';
    echo '  <div class="card-inner">';
    echo "    <h3>$name</h3>";
    echo "    <div class='price'>$price</div>";
    echo '  </div>';
    echo '</a>';
}
?>
</div>


</div>

</body>
</html>