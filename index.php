<!DOCTYPE html>
<html>

<head>
  <title>Consultar Aluno</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="./src/css/style.css" />
</head>

<body>
  <div class="container">
    <form class="form-control ajuste-form-control" action="pesquisar.php" method="GET">
      <label for="aluno">Identificação do aluno:</label>
      <input type="text" id="aluno" name="aluno" /><br />
      <input type="submit" class="btn btn-primary" value="Procurar Aluno" />
    </form>
  </div>
</body>

</html>