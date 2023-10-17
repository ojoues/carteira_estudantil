<!DOCTYPE html>
<html>

<head>
  <title>Consultar Aluno</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="src/css/removeAds.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="src/css/style.css" />
  <!-- Inclua a biblioteca Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!--Impedir letras (apenas números)-->
    <script src="src/js/numberKey.js"></script>

    <?php
    // Check if the mensagem query parameter is set
    if (isset($_GET['mensagem'])) {
      $mensagem = urldecode($_GET['mensagem']);
      echo '<div id="error-message" style="background-color: #ff5252;" class="alert alert-danger">' . $mensagem . '</div>';
    }
    ?>

    <?php
    include('dark_mode.php');
    ?>
  </div>
</body>

</html>