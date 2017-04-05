
<html>
<head><title>LOGIN TO MESSAGE BOARD</title>
<link href="style.css" rel="stylesheet">
</head>
<body bgcolor="#85adad">
<center>


<form method="post" action="login.php">
<br>
<div id="loginform" align="center">

WELCOME TO MESSAGE BOARD
<br>
<br>
Enter Username


<input type=text name=id placeholder="Username">
<br>
<br>
Enter Password
<input type=password name=password placeholder="Password" >
<br>
<br>
<input type=submit id="button" name=login value=LOGIN >
</div>
</form>
<?php
if(isset($_POST['id'])&&isset($_POST['password']))
{
	$id=$_POST['id'];
	$password=$_POST['password'];
	$pwd=md5($password);
	
	try {
		$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
  $dbh->beginTransaction();
  
  

  $stmt = $dbh->prepare('select * from users where username="'.$id.'" and password="'.$pwd.'"; ');
  $stmt->execute();
  
  while ($row = $stmt->fetch()) {
	  header('location:board.php?id='.$row['username']);
    
  }
  
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}
?>


</center>

	
</body>
</html>



