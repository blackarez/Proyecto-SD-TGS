<?php
    include_once('esp-database.php');
    if (isset($_GET["readingsCount"]) && $_GET["readingsCount"]) {
        $data = $_GET["readingsCount"];
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $readings_count = $_GET["readingsCount"];
    }
    // default readings count set to 20
    else {
        $readings_count = 20;
    }

    $last_reading = getLastReadings();
    $last_reading_temp = isset($last_reading["value1"]) ? $last_reading["value1"] : 0;
    $last_reading_humi = isset($last_reading["value2"]) ? $last_reading["value2"] : 0;
    $last_reading_time = isset($last_reading["reading_time"]) ? $last_reading["reading_time"] : '20XX-XX-XX XX:XX:XX';

    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time - 1 hours"));
    // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
    //$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time + 7 hours"));

    $min_temp = minReading($readings_count, 'value1');
    $max_temp = maxReading($readings_count, 'value1');
    $avg_temp = avgReading($readings_count, 'value1');

    $min_humi = minReading($readings_count, 'value2');
    $max_humi = maxReading($readings_count, 'value2');
    $avg_humi = avgReading($readings_count, 'value2');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="esp-style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
  </head>
  <header class="header center">
    <h1>📊 Estación Metereologica</h1>
    
  </header>
  <body>
    <section class="container">
      <div class="row">
        <div class="col-xs-12 col-md-4">
          <div class="row header">
            <div class="col">
                <h6>  Lectura de datos:</h6>
                <p>  <?php echo $last_reading_time; ?></p>
                <hr/>
                <form method="get" class="row g-3">
                    <div class="col-auto">
                        <input
                            type="number"
                            class="form-control"
                            name="readingsCount"
                            min="1"
                            placeholder="Cant. de datos (<?php echo $readings_count; ?>)"
                        />
                    </div>
                    <div class="col-auto">
                        <input type="submit" class="btn btn-primary mb-3" value="Actualizar" />
                    </div>
                </form>
            </div>
        </div>
          <div class="row">
            <div class="col">
              <div class="box gauge--1">
                <h3>TEMPERATURA</h3>
                <div class="mask">
                  <div class="semi-circle"></div>
                  <div class="semi-circle--mask"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <p style="font-size: 30px" id="temp">--</p>
              <p>
                <b>
                  Temperatura de
                  <?php echo $readings_count; ?>
                  registros
                </b>
              </p>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Min</th>
                      <th scope="col">Max</th>
                      <th scope="col">Promedio</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <?php echo $min_temp['min_amount']; ?>
                        &deg;C
                      </td>
                      <td>
                        <?php echo $max_temp['max_amount']; ?>
                        &deg;C
                      </td>
                      <td>
                        <?php echo round($avg_temp['avg_amount'], 2); ?>
                        &deg;C
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row">
            <br/>
            <div class="box gauge--2">
              <h3>HUMEDAD</h3>
              <div class="mask">
                <div class="semi-circle"></div>
                <div class="semi-circle--mask"></div>
              </div>
              <p style="font-size: 30px" id="humi">--</p>
              <p>
                <b>
                  Humedad de
                  <?php echo $readings_count; ?>
                  registros
                </b>
              </p>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Min</th>
                      <th scope="col">Max</th>
                      <th scope="col">Promedio</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <?php echo $min_humi['min_amount']; ?>
                        %
                      </td>
                      <td>
                        <?php echo $max_humi['max_amount']; ?>
                        %
                      </td>
                      <td>
                        <?php echo round($avg_humi['avg_amount'], 2); ?>
                        %
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-8">
          <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                <button
                  class="accordion-button"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#panelsStayOpen-collapseOne"
                  aria-expanded="true"
                  aria-controls="panelsStayOpen-collapseOne"
                >
                  Datos
                </button>
              </h2>
              <div
                id="panelsStayOpen-collapseOne"
                class="accordion-collapse collapse show"
                aria-labelledby="panelsStayOpen-headingOne"
              >
                <div class="accordion-body">
                <?php
                    $result = getAllReadings($readings_count);
                    if ($result) {
                    echo '
                    <h2>Ver los ultimos ' . $readings_count . ' Registros</h2>
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sensor</th>
                                    <th>Location</th>
                                    <th>Temperatura</th>
                                    <th>Humedad</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                    '; while ($row = $result->fetch_assoc()) {
                    $row_id = $row["id"]; $row_sensor = $row["sensor"]; $row_location =
                    $row["location"]; $row_value1 = $row["value1"]; $row_value2 =
                    $row["value2"]; $row_value3 = $row["value3"]; $row_reading_time =
                    $row["reading_time"]; 
                    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
                    //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
                    // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
                    //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 7 hours"));
                    echo '
                    <tr>
                        <td>' . $row_id . '</td>
                        <td>' . $row_sensor . '</td>
                        <td>' . $row_location . '</td>
                        <td>' . $row_value1 . '</td>
                        <td>' . $row_value2 . '</td>
                        <td>' . $row_reading_time . '</td>
                    </tr>
                    '; } echo '
                    </tbody>
                    </table>
                    </div>
                    '; $result->free(); } ?>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                <button
                  class="accordion-button collapsed"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#panelsStayOpen-collapseTwo"
                  aria-expanded="false"
                  aria-controls="panelsStayOpen-collapseTwo"
                >
                  Mapa
                </button>
              </h2>
              <div
                id="panelsStayOpen-collapseTwo"
                class="accordion-collapse collapse"
                aria-labelledby="panelsStayOpen-headingTwo"
              >
                <div class="accordion-body">
                  Proximamente...
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script>
      var value1 = <?php echo (isset($last_reading_temp)) ? $last_reading_temp : 0;  ?>;
      var value2 = <?php echo (isset($last_reading_temp)) ? $last_reading_humi : 0;  ?>;
      setTemperature(value1);
      setHumidity(value2);

      function setTemperature(curVal){
          //set range for Temperature in Celsius -5 Celsius to 38 Celsius
          var minTemp = -5.0;
          var maxTemp = 38.0;
          //set range for Temperature in Fahrenheit 23 Fahrenheit to 100 Fahrenheit
          //var minTemp = 23;
          //var maxTemp = 100;

          var newVal = scaleValue(curVal, [minTemp, maxTemp], [0, 180]);
          $('.gauge--1 .semi-circle--mask').attr({
              style: '-webkit-transform: rotate(' + newVal + 'deg);' +
              '-moz-transform: rotate(' + newVal + 'deg);' +
              'transform: rotate(' + newVal + 'deg);'
          });
          $("#temp").text(curVal + ' ºC');
      }

      function setHumidity(curVal){
          //set range for Humidity percentage 0 % to 100 %
          var minHumi = 0;
          var maxHumi = 100;

          var newVal = scaleValue(curVal, [minHumi, maxHumi], [0, 180]);
          $('.gauge--2 .semi-circle--mask').attr({
              style: '-webkit-transform: rotate(' + newVal + 'deg);' +
              '-moz-transform: rotate(' + newVal + 'deg);' +
              'transform: rotate(' + newVal + 'deg);'
          });
          $("#humi").text(curVal + ' %');
      }

      function scaleValue(value, from, to) {
          var scale = (to[1] - to[0]) / (from[1] - from[0]);
          var capped = Math.min(from[1], Math.max(from[0], value)) - from[0];
          return ~~(capped * scale + to[0]);
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>
