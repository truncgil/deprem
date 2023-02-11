<pre>
<?php 

$deprem = file_get_contents("https://api.orhanaydogdu.com.tr/deprem/live.php?limit=10000");
$deprem = json_decode($deprem,true);
print_R($deprem);
 ?>
 </pre>
 <?php 

$result = json_encode($deprem['result'], JSON_UNESCAPED_UNICODE);

file_put_contents("today.json",$result); ?>
