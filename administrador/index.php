<?php
//si existe un metodo post, que lo redireccione a inicio.php 
if ($_POST) {
  header('location:inicio.php');
}
?>

<!doctype html>
<html lang="es">
  <head>
    <title>Administrador</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  </head>
  <body>
      
  <div class="container">
    <div class="row">

    <!-- codigo para centrar el formulario de login -->
    <div class="col-md-4">
      
    </div>

      <div class="col-md-4">

      <!-- codigo para bajar el formulario -->
      <br><br><br><br><br><br><br><br><br><br>
        
      <div class="card">
        <!-- titulo de contenedor -->
        <div class="card-header">
          Login
        </div>

        <!-- contenido del contenedor -->
        <div class="card-body">

          <!-- formulario de login -->
         <form method="POST" > <!--metodo POST se utiliza para el envio de datos -->
        <div class = "form-group">
        <label>Usuario</label>
        <input type="text" class="form-control" name="usuario"  placeholder="Escribe tu usuario">
        </div>

        <div class="form-group">
        <label>Contraseña</label>
        <input type="password" class="form-control" name="contrasenia" placeholder="Escribe tu contraseña">
        </div>
        <button type="submit" class="btn btn-primary">Entrar al administrador</button>
        </form>
        
        

        </div>


      </div>
      </div>

      </div>
      
    </div>
  </div>
    
  </body>
</html>