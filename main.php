<?php

include_once "encabezado.php";
// Conexión a la base de datos...



//La página por defecto será 1 si no es $_GET["pagina"] ya ya tiene datos.
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}
// El orden en la ID sera ascendente por defecto.
// Si la url ya trae el valor lo cambiaremos para cuando
// Vuelvan a darle al botón
$Id = "ASC";
$siguientePaginaID = "ASC";
if (isset($_GET["Id"])) {

    $Id  = $_GET["Id"];
    $siguientePaginaID = $_GET["Id"];

    if($Id  == "ASC") {
        $Id  = "DESC";
    } else {
        $Id  = "ASC";
    } 
} 

// Hago lo mismo para la ordenación del Cognom
$Cognom = "ASC";
$siguientePaginaCognom = "ASC";
if (isset($_GET["Cognom"])) {
    
    $Cognom  = $_GET["Cognom"];
    $siguientePaginaCognom = $_GET["Cognom"];

    if($Cognom  == "ASC") {
        $Cognom  = "DESC";
    } else {
        $Cognom  = "ASC";
    } 
}



// MOSTRAMOS LOS BOTONES DE: ordenación ascendente o descendente ID o Apellidos
        ?>  
        <div class="ordenacion">
            <?php
            echo '<a href="./main.php?Id=', urlencode($Id), '&pagina=',urlencode($pagina),'"> Ordenar por ID ' . $Id;
            ?>

     
            <?php
            echo '<a href="./main.php?Cognom=', urlencode($Cognom), '&pagina=',urlencode($pagina),'"> Ordenar por Cognom ' . $Cognom;
            ?>
        </div>
       <?php
// FIN BOTONES DE ORDENACIÓN

// MOSTRAMOS LA INFORMACIÓN





// FIN MOSTRAR INFORMACIÓN



// Paginado
// Para conservar la ordenación si la url tie información la mantengo.
echo '<a href="./main.php?pagina=0"> Primera Página ';

if (isset($_GET["Cognom"])) {
    
    
    echo '<a href="./main.php?Cognom=', urlencode($siguientePaginaCognom), '&pagina=',urlencode($pagina - 1),'"> Atrás ';
    echo '<a href="./main.php?Cognom=', urlencode($siguientePaginaCognom), '&pagina=',urlencode($pagina + 1),'"> Adelante ';


} elseif (isset($_GET["Id"])){

    //$Cognom  = $_GET["Id"];
    echo '<a href="./main.php?Id=', urlencode($siguientePaginaID), '&pagina=',urlencode($pagina - 1),'"> Atrás ';
    echo '<a href="./main.php?Id=', urlencode($siguientePaginaID), '&pagina=',urlencode($pagina + 1),'"> Adelante ';


} else {

    echo '<a href="./main.php?pagina=',urlencode($pagina - 1),'"> Atrás ';
    echo '<a href="./main.php?pagina=',urlencode($pagina + 1),'"> Adelante ';

}

//TODO: Calcular última página
echo '<a href="./main.php?pagina=100"> Última Página Página ';




 
include_once "pie.php";
?>

