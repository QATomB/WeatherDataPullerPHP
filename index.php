<?php
$output = "";
if(isset($_GET['destination'])) {

  $location = $_GET['destination']; //unsafe

  $queryString = http_build_query([
    'access_key' => '15705c58891c48edff13189a8be8ed34',
    'query' => $location,
  ]);

  $ch = curl_init(sprintf('%s?%s', 'http://api.weatherstack.com/current', $queryString));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $json = curl_exec($ch);
  curl_close($ch);

  $api_result = json_decode($json, true);

  if(isset($api_result['error'])) {
    if($api_result['error']['code'] == 615) {
      $output = "There were no results for the given area, please check spelling";
    }
  }
  else {
    $output = "<div id='output'>$location:<br>Temp: {$api_result['current']['temperature']}℃<br>Humidity: {$api_result['current']['humidity']}%<br>Wind Speed: {$api_result['current']['wind_speed']}<br>Description: {$api_result['current']['weather_descriptions'][0]}</div>";
  }

}
?>
<!DOCTYPE html>
<html>
<head>
  <style>
    body, html {
      font-size: 1.5em;
      font-family: helvetica;
      position: relative;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(rgb(100, 100, 100), rgb(20, 20, 20));
      padding: 0;
    }
    #output-container {
      position: absolute;
      paddding: 100px;
      width: 50%;
      height: auto;
      background: rgba(30, 30, 30, 1);
      color: rgb(160, 160, 160);
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      border-radius: 20px;
    }
    #output {
      padding: 20px;
      padding-top: 70px;
    }
    #close {
      float: right;
      font-size: 1em;
      text-align: center;
      margin: 20px 20px 0 0;
      padding: auto;
      width: 50px;
      height: 50px;
      background: rgba(50, 50, 50, 0.5);
      color: rgb(150, 150, 150);
      border-radius: 30px;
    }

    #search {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    #search input {
      width: 400px;
      height: 20px;
    }
    #search button {
      display: block;
      width: 50px;
      margin: auto;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <form method='GET' id="search">
    <input type='text' name="destination" placeholder="Enter a city..." /><br>
    <button action="submit">GO!</button>
  </form>
  <div id="output-container">
    <div id="close" style="<?php if($output==''){echo 'display: none;';}?>" onclick="CloseWin()">X</div>
    <?php echo $output; ?>
  </div>
</body>
<script type="text/javascript">
function CloseWin() {
  let output = document.getElementById("output-container");
  output.style = "display: none;";
}
</script>
</html>