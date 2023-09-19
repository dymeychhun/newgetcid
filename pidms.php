<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PID Microsoft</title>
  <link rel="icon" type="image/x-icon" href="assets/img/windows.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body class="bg-light">
  <div class="container-fluid bg-white shadow-sm border mb-3">
    <div class="container">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
          <a class="navbar-brand" href="/"><img src="#" width="32" alt="windows logo" /></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
              <!-- <a class="nav-link active" aria-current="page" href="/">Home</a> -->
              <!-- <a class="nav-link" href="#">Features</a>
              <a class="nav-link" href="#">Pricing</a>
              <a class="nav-link disabled">Disabled</a> -->
            </div>
          </div>
        </div>
      </nav>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <label class="form-label">Enter product key(s) separated by line breaks(maximum 100 product
          keys):</label>
        <textarea id="inputKey" rows="5" class="form-control" placeholder="93MGM-NTFKD-6BK63-R6FY.."></textarea>
      </div>
      <div class="col-md-12 my-3 text-center">
        <button class="btn btn-outline-danger" id="btnCheck">
          Check Online
        </button>
      </div>
      <div class="col-md-12">
        <div class="position-relative">
          <textarea id="getKey" rows="10" class="form-control" readonly></textarea>
          <div class="position-absolute top-0 end-0">
            <button class="btn" id="btnCopy">
              <i class="bi bi-clipboard"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="mt-2">
        <span id="timer">Speed: 0.00(sec)</span>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script>
    // toastr alert configuration
    toastr.options = {
      closeButton: false,
      debug: false,
      newestOnTop: false,
      progressBar: false,
      positionClass: "toast-top-right",
      preventDuplicates: false,
      onclick: null,
      showDuration: "300",
      hideDuration: "1000",
      timeOut: "5000",
      extendedTimeOut: "1000",
      showEasing: "swing",
      hideEasing: "linear",
      showMethod: "fadeIn",
      hideMethod: "fadeOut",
    };

    // Cache selected elements
    let $inputKey = $("#inputKey");
    let $btnCheck = $("#btnCheck");
    let $getKey = $("#getKey");
    let $timerElement = $("#timer");
    // fetch the data from the server API
    function fetchData() {
      let key = $inputKey.val();

      if (key !== "") {
        $btnCheck.text("Checking data...");

        let startTime = performance.now();

        $.ajax({
            type: "POST",
            url: 'fetch.php',
            data: {
              key: key,
              submit: true
            },
            async: true
          })
          .then(function(response) {
            $btnCheck.text("Check Online");

            let endTime = performance.now();
            let elapsedTime = Math.round((endTime - startTime) / 10) / 100;

            let jdata = JSON.parse(response);
            let res = '';

            for (var i = 0; i < jdata.data.length; i++) {
              let result = jdata.data[i].response_message;
              res += result;
            }
            res = res.trim(); // Remove leading/trailing whitespace characters
            $getKey.text(res);
            $timerElement.text(`Speed: ${elapsedTime}(sec)`);
          })
          .catch(function() {
            $btnCheck.text("Check Online");
            toastr.error("Error While getting data! Please try again later.");
            $timerElement.text(`Speed: ${elapsedTime}(sec)`);
          });
      } else {
        $inputKey.focus();
        toastr.error("Please enter your product keys!");
      }
    }

    function copyCID() {
      let text = $("#getKey").val();
      if (text != "") {
        navigator.clipboard
          .writeText(text)
          .then(() => {
            toastr.success("Copied to clipboard");
          })
          .catch((err) => {
            toastr.error("Failed to copy text: ", err);
          });
      }
    }

    $("#btnCheck").on("click", function() {
      // Call the fetchData function
      fetchData();
    });
    $("#btnCopy").on("click", function() {
      // Call the copyCID
      copyCID();
    });
  </script>
</body>

</html>