<?php

$conn = mysqli_connect("localhost", "root", "", "lkf");

$result = mysqli_query($conn, "SELECT * FROM `plain_problem` ");

$airplaneStates = array();

while ($row = mysqli_fetch_assoc($result)) {
  $airplaneStates[] = $row;
}

$fly_planet = json_encode($airplaneStates, JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Интернет версия</title>

  <!-- Подключение -->
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">

  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.bundle.js">
  <link rel="stylesheet" href="assets/assets/bootstrap/css/bootstrap.bundle.min">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

  <style>

    .scheme {
      height: 393px;
      margin: 0 auto;
      position: relative;
    }
    .scheme svg {
      position: absolute;
      top: 0px;
      left: 0px;
      height: 393px;
    }

    .scheme_plain{
      fill: #fff;*/

}

.selected{
  fill: red;
}

</style>

</head>
<body class="bg-transparent">

  <header class="bg-dark p-4"></header>

    <div class="container mt-5">
      <div class="row">
        <div class="col-6">
          <div class="border rounded p-4">

            <form>
              <p class="mb-2 fs-5">Модель самолета:</p>
              <select id="mySelect selectModel" class="form-select" multiple onchange="showAirplaneInfo(); showAir();">
                <?php 
                $sql = "SELECT * FROM plain";
                $res_data = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($res_data)){?>

                  <option class="fs-5 fw-light mb-1" value="<?php echo ''.$row['model'].''; ?>"><?php echo ''.$row['model'].''; ?></option>
                <?php }?>
              </select>
            </form>

          </div>

          <div class="border rounded p-4 mt-3">
            <p class=" mb-2 fs-5">Причина поломки:</p>
            <select id="selectReason" class="form-select" onchange="showAirplaneInfo()">
              <option value="" selected="selected" disabled>Выбирите причину</option>
              <?php 
              $sql = "SELECT * FROM plain_spare";
              $res_data = mysqli_query($conn, $sql);
              while($row = mysqli_fetch_array($res_data)){?>
                <option value="<?php echo ''.$row['spare_map'].''; ?>"><?php echo ''.$row['spare'].''; ?></option>
              <?php }?>
            </select>

          </div>

          <div class="border rounded p-4 mt-3">
            <div id="output">
              <p class="text-center mb-0 p-2 fs-4">Выберите причину поломки</p>
            </div>
          </div>

        </div>
        <div class="col-6">

          <div class="border p-0 rounded-2 overflow-hidden">

            <div id="result">
              <p class="text-center mb-0 p-5 fs-4">Выберите модель самолета</p>
            </div>

          </div>

          <div class="border rounded p-4 mt-3">
            <div class="scheme">

              <?php include 'assets/img/samolet.php'; ?>

            </div>
          </div>
        </div>
      </div>
    </div>

    <script>

      var airplaneStates = <?php echo $fly_planet; ?>;

      console.log(airplaneStates);

      function showAirplaneInfo() {
        var selectModel = document.getElementById("mySelect selectModel");
        var selectReason = document.getElementById("selectReason");
        var output = document.getElementById("output");

        var model = selectModel.options[selectModel.selectedIndex].value;
        var reason = selectReason.options[selectReason.selectedIndex].value;

        var airplaneState = airplaneStates.find(function(state) {
          return state.model === model && state.reason === reason;
        });

        if (airplaneState) {
          output.innerHTML = 
          "Причина поломки: " + airplaneState.reason_name + "<br>" +
          "Способ устранения поломки: " + airplaneState.repair;
        } else {
          output.innerHTML = "Информация о поломке самолета не найдена";
        }
      }

      function showAir() {
        var selectedOption = document.getElementById("mySelect selectModel").value;
        if (selectedOption == "") {
          document.getElementById("result").innerHTML = "";
          return;
        } 
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("result").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET", "assets/inc/function/plain_info.php?option=" + selectedOption, true);
        xmlhttp.send();
      }

    </script>

    <script>

      const select = document.getElementById('selectReason');
      const squares = document.querySelectorAll('.scheme_plain');

      select.addEventListener('change', () => {
        const color = select.value;
        const selectedSquare = document.querySelector('.scheme_plain.selected');
        if (selectedSquare) {
          selectedSquare.classList.remove('selected');
        }
        if (color) {
          const square = document.querySelector(`.scheme_plain.${color}`);
          square.classList.add('selected');
        }
      });

    </script>

  </body>
  </html>