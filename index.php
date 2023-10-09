<!DOCTYPE html>
<html>

<head>
  <title>Consultar Aluno</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />
  <link rel="stylesheet" href="src/css/removeAds.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="src/css/style.css" />

  <script>
    // JavaScript function to hide the error message after 3 seconds
    function hideErrorMessage() {
      var errorMessage = document.getElementById("error-message");
      if (errorMessage) {
        setTimeout(function() {
          errorMessage.style.display = "none";
        }, 5000);
      }
    }
  </script>
</head>

<body onload="hideErrorMessage();">
  <div class="container">
    <form class="form-control ajuste-form-control" action="pesquisar" method="GET">
      <label for="aluno">Identificação do aluno:</label>
      <input type="text" id="aluno" name="aluno" required="required" onkeypress="return isNumberKey(event)" placeholder="Apenas números" /><br />
      <input type="submit" class="btn btn-primary" value="Procurar Aluno" />
    </form>

    <script>
      function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
        }
        return true;
      }
    </script>

    <?php
    // Check if the mensagem query parameter is set
    if (isset($_GET['mensagem'])) {
      $mensagem = urldecode($_GET['mensagem']);
      echo '<div id="error-message" style="background-color: #ff5252;" class="alert alert-danger">' . $mensagem . '</div>';
    }
    ?>
  </div>
</body>

</html>