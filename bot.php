<pre>
<?php 

$depremDatas = file_get_contents("https://api.orhanaydogdu.com.tr/deprem/live.php?limit=10000");
$depremDatas = json_decode($depremDatas,true);
$resultDatas = $depremDatas['result'];

$refactoringData = [];

foreach($resultDatas AS $resultData) {


    $title = $resultData['title'];
    preg_match('#\((.*?)\)#', $title, $match);

    
    $resultData['city'] = $match[1];
    $refactoringData[] = $resultData;
    
}
print_r($refactoringData);
  ?>
 </pre>
 <?php 


$result = json_encode($refactoringData, JSON_UNESCAPED_UNICODE);

file_put_contents("today.json",$result); ?>
