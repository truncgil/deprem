<pre>
<?php 
ini_get('allow_url_fopen');
include("phpquery.php");
//$url  = "https://api.orhanaydogdu.com.tr/deprem/live.php?limit=10000";
$url  = "http://www.koeri.boun.edu.tr/scripts/lst6.asp";

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$response = curl_exec( $ch );

if ( $response === false) {
    $curl_result = curl_error( $ch );
    print_r( $curl_result );
} else {

    header( 'Content-Type: application/json' );
	 
    $response = select_elements('pre', $response);
    $response = $response[0]['text'];
    $response = explode(PHP_EOL, $response);
    $response = array_slice($response, 7);

    $refactoringResponse = [];

    
    foreach($response AS $line) {
        $line = preg_split('/\s+/', $line);
        
        $title = $line[8] . " " . $line[9];
        $title = str_replace("İlksel", "", $title);
        $title = trim($title);

        $lat = (double) $line[2];
        $lng = (double) $line[3];
        $date = $line[0];
        $time = $line[1];

        preg_match('#\((.*?)\)#', $title, $match);
        $data = [
            'title' => $title,
            'type' => $line[10]=='' ? 'İlksel' : $line[10],
            'date' => $date . ' ' . $time,
            'lat' => $lat,
            'lng' => $lng,
            'md' => $line[5],
            'ml' => $line[6],
            'mw' => $line[7],
            'depth' => $line[4],
            'coordinates' => [
                $lat , $lng
            ],
            'geojson' => [
                'type' => 'Point',
                'coordinates' => [
                    $lat, $lng
                ]
            ],
            'location_properties' => '',
          //  'rev' => $line[0],
          //  'date_stamp' => $line[0],
            'date_day' => $date,
            'date_hour' => $time,
            'timestamp' => strtotime($date . ' ' . $time),
            'location_tz' => '',
            'city' => $match[1]
        ];
        $refactoringResponse[] = $data;
      //  var_dump($line);
    }

    var_dump($refactoringResponse);

   
      ?>
    </pre>
    <?php 


    $result = json_encode($refactoringResponse, JSON_UNESCAPED_UNICODE);
    $last = json_encode($refactoringResponse[0], JSON_UNESCAPED_UNICODE);

    file_put_contents("today.json",$result); 
    file_put_contents("last.json",$last); 

}
?>
