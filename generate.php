<?php
function qrng($length = 100){
    // Get an array of quantum random numbers generated by the QRNG at ANU.
    // Debugging and dev is faster if we bypass the API and just use a dirty shuffle. Default length to 100, but we can specify this.
    $qrNumbers = range(0,255);
    shuffle($qrNumbers);
    $qrNumbers = array_slice($qrNumbers,0,$length);

    // Test & Return
    echo "<pre>"; print_r($qrNumbers); echo "</pre>";
    return $qrNumbers; // Comment out this line to use API response of ACTUAL quantum randomness

    // Numbers from ANU are random between 0-255. Would be nice if we could specify a range, so instead we
    // just generate an array of 100 numbers, and cycle through later until we have a number within our parameters.
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://qrng.anu.edu.au/API/jsonI.php?length=$length&type=uint8",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    // Test & Return
    echo "<pre>"; print_r(json_decode($response)->data); echo "</pre>";
    return json_decode($response)->data;
}
function quantumRand($min = 0, $max = 100){
    // This function uses our quantum random number function, and is intended to be a direct replacement for a PHP rand(min,max)
    // Default range is 0-100 but we can specify
    $qr = qrng();

    // Our quantum array has a range of 0-255, so we need to cycle through to find a value within our parameters.
    // If it fails to find a value within the parameters, this function calls itself to generate a new list, and loop the cycle.
    // Until I come up with a more elegant solution, this will do.
    foreach($qr as $key => $val){
        if($val >= $min && $val <= $max){

            // Test & Return
            echo "<pre>"; print_r($val); echo "</pre>";
            return $val;
        }
    }

    // Test & Return
    echo "<pre>"; print_r('Failed to satisfy parameters. Calling API again.'); echo "</pre>";
    return quantumRand($min, $max);
}
function quantumColours($length = 100) {
    // Get an array of quantum random hexadecimals to be used as colours. Default length to 100, but we can specify this.
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://qrng.anu.edu.au/API/jsonI.php?length=$length&type=hex16&size=3",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    // Build an array of formatted colours to use directly with CSS. We could even use quantumRand to assign these...
    $colours = array();
    foreach(json_decode($response)->data as $key => $val){
        $colours[] = '#'.$val;
    }

    // Test & Return
    echo "<pre>"; print_r($colours); echo "</pre>";
    return $colours;
}
function quantumRandom($length = 1){
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://qrng.anu.edu.au/API/jsonI.php?length=$length&type=uint8",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    if($length > 1){
        return json_decode($response)->data;
    }else{
        return json_decode($response)->data[0];
    }
}
function qr($min, $max){
    $qr = quantumRandom(255);

    foreach($qr as $key => $val){
        if($val >= $min && $val <= $max){
            return $val;
        }
    }

    return qr($min, $max);
}
function rand_color() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://qrng.anu.edu.au/API/jsonI.php?length=1&type=hex16&size=3',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $hex = '';
    foreach(json_decode($response)->data as $key => $val){
        $hex .= $val." ";
    }

    return '#'.$hex;
}
function readable_random_string($length = 6){
    $string = '';
    $vowels = array("a","e","i","o","u");
    $consonants = array(
        'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
        'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
    );

    $max = $length / 2;
    for ($i = 1; $i <= $max; $i++)
    {
        $string .= $consonants[qr(0, 19)];  // Replace with Quantum Randomness
        $string .= $vowels[qr(0, 4)];  // Replace with Quantum Randomness
    }

    return $string;
}
function generateStripes($stripeCount){
    $stripeArray = array();
    $max = 100;

    for ($x = 1; $x <= $stripeCount; $x++) {
        $size = qr(0, ($stripeCount - $x));// Replace with Quantum Randomness
        $max -= $size;

        if(qr(0, 1) == 0) {  // Replace with Quantum Randomness
            $colour = rand_color();
        }else{
            $colour = 'transparent';
        }

        $stripeArray[] = array('size' => $size, 'colour' => $colour);
    }

    return $stripeArray;
}
function getRandomKey($usedKeys, $max){
    $key = qr(0, $max - 1); // Replace with Quantum Randomness

    if(!in_array($key, $usedKeys)){
        return $key;
    }else{
        return getRandomKey($usedKeys, $max);
    }
}
function renderStripes($stripeArray, $axis){
    $usedKeys = array();
    $word = null;

    for ($x = 1; $x <= count($stripeArray); $x++) {

        $key = getRandomKey($usedKeys, count($stripeArray));
        $usedKeys[] = $key;

        $size = $stripeArray[$key]['size'];
        $colour = $stripeArray[$key]['colour'];

        if($axis == 'y'){
            echo "<div style='width: $size%; height: 100%; background: $colour; float: left;'></div>";
        }else if($axis == 'x'){
            if($size > 3 && !$word){
                $singleWord = readable_random_string();
                global $filename;
                $filename = $singleWord;
                $word = true;
            }else{
                $singleWord = '';
            }
            echo "<div style='height: $size%; width: 100%; color: #000; background: $colour; float: left;'><span style='background: #fff; position: absolute;'>".$singleWord."</span></div>";
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="https://code.jquery.com/jquery-2.2.4.min.js" ></script>
      <script src="html2canvas.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Nova+Mono&display=swap" rel="stylesheet">
      <title>The Quantum Manifest Project, NFT's generated by Quantum Randomness.</title>
      <style>
          body{
              font-family: 'Nova Mono', monospace;
          }
      </style>
  </head>
  <body>
    <div id="capture" style="width: 400px; height: 400px; position: relative;">
        <div style="width: 400px; height: 400px; position: fixed; background: <?=rand_color()?>;">
            <?php renderStripes(generateStripes(qr(2, 20)), 'y') ?>
        </div>
        <div style="width: 400px; height: 400px; position: fixed;">
            <?php renderStripes(generateStripes(qr(2, 20)), 'x') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script type="text/javascript">
      $(function() {
          html2canvas(document.querySelector("#capture")).then((canvas) => {
              document.body.appendChild(canvas)
              var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.

              var anchor = document.createElement('a');
              anchor.setAttribute('download', '<?=$filename?>.png');
              anchor.setAttribute('href', image);
              anchor.click();
          });
      });
  </script>
  </body>
</html>
