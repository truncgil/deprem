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

    /*
    "title": "YASSIPINAR-ALTINYAYLA (SIVAS)",
        "date": "2023.02.12 05:36:45",
        "lokasyon": "YASSIPINAR-ALTINYAYLA (SIVAS)",
        "lat": 39.3008,
        "lng": 36.72,
        "mag": 2.5,
        "depth": 5,
        "coordinates": [36.72, 39.3008],
        "geojson": {
            "type": "Point",
            "coordinates": [36.72, 39.3008]
        },
        "location_properties": null,
        "rev": null,
        "date_stamp": "2023-02-12",
        "date_day": "2023-02-12",
        "date_hour": "05:36:45",
        "timestamp": "1676176605",
        "location_tz": "Europe/Istanbul"
    */
    foreach($response AS $line) {
        $line = preg_split('/\s+/', $line);
        
        $title = $line[8] . " " . $line[9];

        $lat = (double) $line[2];
        $lng = (double) $line[3];
        $date = $line[0];
        $time = $line[1];

        preg_match('#\((.*?)\)#', $title, $match);
        $data = [
            'title' => $title,
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
            'rev' => $line[0],
            'date_stamp' => $line[0],
            'date_day' => $line[0],
            'date_hour' => $line[1],
            'timestamp' => time(),
            'location_tz' => '',
            'city' => $match[1]
        ];
        $refactoringResponse[] = $data;
      //  var_dump($line);
    }

    var_dump( $refactoringResponse );
    $depremDatas = json_decode($response,true);
    $resultDatas = $depremDatas['result'];
    var_dump($depremDatas);

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

    file_put_contents("today.json",$result); 

}
?>
