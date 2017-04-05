<?php
session_start();
if(isset($_GET['id'])){
	$_SESSION['id']=$_GET['id'];
}
if(isset($_POST['reply']))
{
	$_SESSION['reply']=$_GET['reply'];
}
?>
<html>
<head><title>Message Board</title>
<link href="style.css" rel="stylesheet">
</head>
<body bgcolor="#85adad">
<?php
echo '<center>';
echo '<font size="10">';

echo "Welcome ",$_SESSION['id'];
echo '</font>';
echo '</center>';
?>
<center>
<form method="POST" action="board.php">
<textarea rows=4 cols=30 placeholder="TYPE MESSAGE HERE.." name=message></textarea>
<br>
<input type=submit id=button name=postmsg value=Post>
<input type=submit id=button name=logout value=Logout>
</center>
<?php	
try 
  {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));  
  $dbh->beginTransaction();
  }
 catch (PDOException $e)
 {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
 }
if(isset($_POST['logout']))
{
session_unset();
 header('location:login.php');	
}
  
  if(isset($_POST['reply']))
{
	$msg=$_POST['message'];
	$rply=$_SESSION['reply'];
  
  $dbh->exec('insert into posts values("'.uniqid().'","'.$rply.'","'.$_SESSION['id'].'",now(),"'.$msg.'")');
  $dbh->commit();  
  header('location:board.php');
        
  
}
if(isset($_POST['postmsg']))
{
	$msg=$_POST['message'];
  
  $dbh->exec('insert into posts values("'.uniqid().'",null,"'.$_SESSION['id'].'",now(),"'.$msg.'")');
        $dbh->commit();
		header('location:board.php');
    
}
  $stmt = $dbh->prepare('select * from posts as p,users as u where p.postedby=u.username order by datetime desc; ');
  $stmt->execute();
  echo '<center>';
  echo '<table id=data cellspacing="15" frame="box">';
  echo '<tr>';
  echo '<th id=heading>',"Message ID",'</th>';
  echo '<th id=heading>',"Username",'</th>';
  echo '<th id=heading>',"Full Name",'</th>';
  echo '<th id=heading>',"Message Time",'</th>';
  echo '<th id=heading>',"Reply to",'</th>';
  echo '<th id=heading>',"Message",'</th>';
  echo '<th id=heading>',"Reply",'</th>';
  echo '</tr>';
  
  while ($row = $stmt->fetch()) 
	{
	echo '<tr>';
    echo '<td>',$row['id'],'</td>';
	echo '&nbsp';
	echo '<td>',$row['username'],'</td>';
	echo '&nbsp';
	echo '<td>',$row['fullname'],'</td>';
	echo '&nbsp';
	echo '<td>',$row['datetime'],'</td>';
	echo '&nbsp';
	echo '<td>',$row['replyto'],'</td>';
	echo '&nbsp';
	echo '<td>',$row['message'],'</td>';
	echo '&nbsp';
	$by=$row['id'];
	//echo '<td>',$by,'</td>';
	echo '<td>','<input type=submit id=button name=reply value=Reply formaction="board.php?reply='.$by.'">','</td>';
	echo '</tr>';
	}
	echo '</table>';
	echo '</center>';
?>
</form>
</body>
</html>
