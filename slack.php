<?php

// Your token
$token = "write-your-token";

// Quantity for delete
$quantity = "100";

$curl = curl_init();

// In CURLOPT_URL insert url and your token for get all archives save in slack.
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://slack.com/api/files.list?token=".$token."&count=".$quantity,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$ids = array();
$I = 0;

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $result = json_decode($response);
  $files = $result->files;

  // Quantity that archives get.
  echo "Quantity: ".count($files)."<br>";

  // Get IDs.
  foreach ($files as $file) {
    $ids[$I] = $file->id;
    $I++;
  }

}

for ($a = 0; $a < $I; $a++) {

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://slack.com/api/files.delete",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "token=".$token."&file=".$ids[$a],
    CURLOPT_HTTPHEADER => array(
      "Cache-Control: no-cache",
      "Content-Type: application/x-www-form-urlencoded"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $ids[$a]." - Delete with success!<br>";
  }

}
