<?php
try
{
$pdo = new PDO('mysql:host=localhost;dbname=fb_student_main_db', 'aashu8193', 'peterpan0804');
}
catch(PDOException $e)
{
exit();
}
?>