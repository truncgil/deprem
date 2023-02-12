<?php 
$datas = file_get_contents("https://deprem.truncgil.com/today.json");
$datas = json_decode($datas, true); 

$city = $_GET['city'];

$refactoringData = [];

foreach($datas AS $data) {
    if($data['city']==$city) {
        $refactoringData[] = $data;
    }
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($refactoringData, JSON_UNESCAPED_UNICODE);

?>