<!-- cabecera de pagina de inicio -->

<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Inicio</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href=".//css/bootstrap.min.css">
  </head>
  <body>

 <?php $url="http://".$_SERVER['HTTP_HOST']."/empresa_musical" ?> <!--creamos una variable url para crear una redireccion -->

<!-- codigo de menu de la pagina  -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="nav navbar-nav">
        <a class="nav-item nav-link active" href="#">Administrador del sitio web <span class="sr-only">(current)</span></a>
        <a class="nav-item nav-link" href="<?php echo $url;?> /administrador/inicio.php">Inicio</a>
        <a class="nav-item nav-link" href="<?php echo $url;?> /administrador/seccion/productos.php">Productos</a>
        <a class="nav-item nav-link" href="<?php echo $url;?> /administrador/seccion/cerrar.php">Cerrar</a>
        <a class="nav-item nav-link" href="<?php echo $url; ?> ">Ver sitio web</a>
    </div>
</nav>

  <!-- contenedor de bootstrap -->
    <div class="container">
<br/>
    <div class="row">