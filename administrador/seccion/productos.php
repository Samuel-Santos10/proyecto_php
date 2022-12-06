
<?php include("../template/cabecera.php");?>

<?php
// creamos nuestras varibles para capturar los datos ingresados

 $txtID=(isset($_POST['txtID']))  ?  $_POST['txtID'] : "";   //isset se encarga de verificar si el campo esta vacio o tiene algo
 $txtNombre=(isset($_POST['txtNombre']))  ?  $_POST['txtNombre'] : "";
 $txtMarca=(isset($_POST['txtMarca']))  ?  $_POST['txtMarca'] : "";
 $txtCantidad=(isset($_POST['txtCantidad']))  ?  $_POST['txtCantidad'] : "";
 $txtPrecio=(isset($_POST['txtPrecio']))  ?  $_POST['txtPrecio'] : "";
 $txtImagen=(isset($_FILES['txtImagen']['name']))  ?  $_FILES['txtImagen']['name'] : "";
 $accion=(isset($_POST['accion']))  ?  $_POST['accion'] : "";

include("../config/bd.php"); //incluimos nuestra base de datos

// validamos la accion agregar,modificar,cancelar , si lo selecciona, que realice esa accion
switch ($accion) {

  case 'Agregar':
    $sentenciaSQL= $conexion->prepare("INSERT INTO productos (nombre, marca, cantidad, precio, imagen) VALUES (:nombre, :marca, :cantidad, :precio, :imagen);");  //Le estamos diciendo que inserte estos valores a la base de datos
    
    //Ie estamos diciendo que el parametro nombre tendra el valor que obtenga de txtnombre
    $sentenciaSQL->bindParam(':nombre',$txtNombre); 
    $sentenciaSQL->bindParam(':marca',$txtMarca); 
    $sentenciaSQL->bindParam(':cantidad',$txtCantidad); 
    $sentenciaSQL->bindParam(':precio',$txtPrecio); 

    //Codigo para adjuntar la imagen en nuestra carpeta img
    
    //utilizamos el date time para que no se repitan el nombre de las imagenes que agreguemos
    $fecha= new DateTime(); 
    //Con este codigo estamos diciendo que si existe un nombre igual de la imagen, que lo cambie agregandole la fecha mas el nombre del archivo
    $nombreArchivo=($txtImagen!="") ? $fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"] : "imagen.jpg";
    
    //imagen temporal (tmp)
    $tmpImagen=$_FILES["txtImagen"]["tmp_name"];
    
    //validamos si la imagen temporal tiene algo
    if ($tmpImagen!="") {
      //Movemos el archivo a nuestra carpeta img
      move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
    }
    
    $sentenciaSQL->bindParam(':imagen',$nombreArchivo); 
    $sentenciaSQL->execute();       //Le estamos diciendo que ejecute nuestra sentenciaSQL  

    header("Location:productos.php");

    break;
    
    // codigo modificar
    case 'Modificar':
      //le estamos diciendo que nos modifique cualquiera de estos 
      $sentenciaSQL= $conexion->prepare("UPDATE productos SET nombre=:nombre, marca=:marca, cantidad=:cantidad, precio=:precio WHERE id=:id"); 
      $sentenciaSQL->bindParam(':nombre',$txtNombre); 
      $sentenciaSQL->bindParam(':marca',$txtMarca); 
      $sentenciaSQL->bindParam(':cantidad',$txtCantidad); 
      $sentenciaSQL->bindParam(':precio',$txtPrecio); 
      $sentenciaSQL->bindParam(':id',$txtID); 
      $sentenciaSQL->execute(); 
      
      //codigo actualizar imagen
      if ($txtImagen!="") {
        
        $fecha= new DateTime(); 
        $nombreArchivo=($txtImagen!="") ? $fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"] : "imagen.jpg";
        //imagen temporal (tmp)
        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];
        move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

        //borramos la imagen anterior cuando actualizamos la imagen (mismo codigo de borrar registro)

        $sentenciaSQL= $conexion->prepare("SELECT imagen FROM productos WHERE id=:id"); 
       $sentenciaSQL->bindParam(':id',$txtID); 
       $sentenciaSQL->execute(); 
       $producto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

       if (isset($producto["imagen"]) &&($producto["imagen"]!="imagen.jpg") ) { //si existe la imagen 

           if (file_exists("../../img/".$producto["imagen"])) { //si existe el archivo en la carpeta img, que lo borre

           unlink("../../img/".$producto["imagen"]); //borramos la imagen desde la carpeta img
           }
        
       }


        //actualizar la imagen nueva imagen 
        $sentenciaSQL= $conexion->prepare("UPDATE productos SET imagen=:imagen WHERE id=:id"); 
          $sentenciaSQL->bindParam(':imagen',$nombreArchivo); 
          $sentenciaSQL->bindParam(':id',$txtID); 
          $sentenciaSQL->execute(); 
        }
        header("Location:productos.php");
        break;
        
        //codigo cancelar
        case 'Cancelar':
          //redirijimos a la seccion de productos
          header("Location:productos.php");
          break;
          
          //codigo seleccionar
          case 'Seleccionar':
            $sentenciaSQL= $conexion->prepare("SELECT * FROM productos WHERE id=:id"); 
            $sentenciaSQL->bindParam(':id',$txtID); 
            $sentenciaSQL->execute(); 
       $producto=$sentenciaSQL->fetch(PDO::FETCH_LAZY); //FETCH_LAZY me permite cargar los datos uno a uno para seleccionarlo

       //con este codigo estamos llamando cada uno de los datos para que los rellene a la hora de seleccionar un campo
       $txtNombre=$producto['nombre'];
       $txtMarca=$producto['marca'];
       $txtCantidad=$producto['cantidad'];
       $txtPrecio=$producto['precio'];
       $txtImagen=$producto['imagen'];
    break;

    //codigo borrar
    case 'Borrar':

      //Borrar imagen y registro

      $sentenciaSQL= $conexion->prepare("SELECT imagen FROM productos WHERE id=:id"); 
       $sentenciaSQL->bindParam(':id',$txtID); 
       $sentenciaSQL->execute(); 
       $producto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

       if (isset($producto["imagen"]) &&($producto["imagen"]!="imagen.jpg") ) { //si existe la imagen 

           if (file_exists("../../img/".$producto["imagen"])) { //si existe el archivo en la carpeta img, que lo borre

           unlink("../../img/".$producto["imagen"]); //borramos la imagen desde la carpeta img
           }
        
       }

      $sentenciaSQL= $conexion->prepare("DELETE FROM productos WHERE id=:id"); 
      $sentenciaSQL->bindParam(':id',$txtID); 
    $sentenciaSQL->execute(); 
      header("Location:productos.php");
    break;
    
  }

//codigo para mostrar los datos almacenados en la base de datos

$sentenciaSQL= $conexion->prepare("SELECT * FROM productos"); //llamamos todos los datos de nuestra tabla productos
$sentenciaSQL->execute();   //ejecutamos la sentencia

//guardamos en la variable listaProductos lo que se ejecuto para poderlo mostrar todos los datos utilizando el metodo fetchAll
$listaProductos=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- codigo para agregar productos -->

<!-- Interfaz de crud de productos-->

<div class="col-md-4">
  
<div class="card">
  <div class="card-header">
    Datos del Producto
  </div>

  <div class="card-body">
    
<!-- formulario de agregar libros -->
<form method="POST" enctype="multipart/form-data"><!--metodo para enviar los datos, enctype para permitir guardar imagenes -->

  <!-- capturamos los datos ingresados -->
  <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" required readonly class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID"  placeholder="ID">
  </div>

  <div class = "form-group">
    <label for="txtNombre">Producto:</label>
    <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre"  placeholder="Nombre del producto">
  </div>

  <div class = "form-group">
    <label for="txtMarca">Marca:</label>
    <input type="text" required class="form-control" value="<?php echo $txtMarca; ?>" name="txtMarca" id="txtMarca"  placeholder="Marca del producto">
  </div>

  <div class = "form-group">
    <label for="txtCantidad">Cantidad:</label>
    <input type="number" required class="form-control" value="<?php echo $txtCantidad; ?>" name="txtCantidad" id="txtCantidad"  placeholder="Cantidad del producto">
  </div>

  <div class = "form-group">
    <label for="txtPrecio">Precio:</label>
    <input type="number" required class="form-control" value="<?php echo $txtPrecio; ?>" name="txtPrecio" id="txtPrecio"  placeholder="Precio del producto">
  </div>
  
  <div class = "form-group">
    <label for="txtImagen">Imagen:</label>

    <br/>
    
    <!-- Mostrar imagen desde seleccionar archivo -->
    <?php    if ($txtImagen!="") {   ?>
      
      <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen;?>" width="50" alt="">
      
      <?php  }  ?>
      
      <br/>
      <br/>

    <input type="file"  class="form-control" name="txtImagen" id="txtImagen">
  </div>

  <div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")? "disabled": ""; ?> value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")? "disabled": ""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")? "disabled": ""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
  </div>

  </form>
  <!-- formulario de agregar libros -->

</div>

</div>

</div>


<div class="col-md-8">
  <!-- tabla de los datos de productos -->

<table class="table table-bordered">
  <thead>
     <tr><!-- columnas -->
      <th>ID</th>
      <th>PRODUCTO</th>
      <th>MARCA</th>
      <th>CANTIDAD</th>
      <th>PRECIO</th>
      <th>IMAGEN</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>

  <?php foreach($listaProductos as $producto) { ?> <!-- rrecorremos todos los datos almacenados -->

<!-- tabla de datos obtenidos de la base de datos -->
    <tr> 
      <td ><?php echo $producto['id']; ?></td>
      <td ><?php echo $producto['nombre']; ?></td>
      <td ><?php echo $producto['marca']; ?></td>
      <td ><?php echo $producto['cantidad']; ?></td>
      <td ><?php echo $producto['precio']; ?></td>
      <td >
        <img class="img-thumbnail rounded" src="../../img/<?php echo $producto['imagen']; ?>" width="50" alt="">
      </td>

      <!-- codigo de seleccionar y borrar -->
      <td>
      <form  method="post">
      <!-- input id -->
         <input type="hidden" name="txtID" id="txtID" value="<?php echo $producto['id']; ?>"/><!-- con hidden le decimos que estara escondido -->

    <!-- boton Seleccionar -->
        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/> 

    <!-- boton borrar -->
        <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/> 
      </form>
      </td>

    </tr>
    <?php }?><!-- termina foreach -->

  </tbody>
</table>

</div>

<?php include("../template/pie.php");?>