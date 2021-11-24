<?php

// Encabezado html.
include_once "encabezado.php";

// Conexión a la base de datos...
include_once "base_de_datos.php";



// Establecemos los productos por página que deseamos mostrar.
$productosPorPagina = 5;

//La página por defecto será 1 si no, es $_GET["pagina"]
$pagina = 1;
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
 
}


// El orden en la ID sera ascendente por defecto.
// Si la url ya trae el valor lo cambiaremos para cuando
// Vuelvan a darle al botón
$Id = "ASC";
$ahoraPaginaID = "ASC"; // Este es el valor que tenía cuando se envía un submit
if (isset($_GET["Id"])) {

    $Id  = $_GET["Id"];
    $ahoraPaginaID = $_GET["Id"]; // Se mantiene el valor para la query pero se cambia para los botones.

    if($Id  == "ASC") {
        $Id  = "DESC";
    } else {
        $Id  = "ASC";
    } 
} 

// Hago lo mismo para la ordenación del Cognom
$Cognom = "ASC";
$ahoraPaginaCognom = "ASC"; // Este es el valor que tenía cuando se envía un submit
if (isset($_GET["Cognom"])) {
    
    $Cognom  = $_GET["Cognom"];
    $ahoraPaginaCognom = $_GET["Cognom"];  // Se mantiene el valor para la query pero se cambia para los botones. // Se mantiene el valor para la query pero se cambia para los botones.

    if($Cognom  == "ASC") {
        $Cognom  = "DESC";
    } else {
        $Cognom  = "ASC";
    } 
}

// Preparamos las Querys
// El limit es los productos por página:
$limit = $productosPorPagina;

// El offset para que saltemos X productos que viene dado por:
// multiplicar la página - 1 * los productos por página
$offset = ($pagina - 1) * $productosPorPagina;

// Necesitamos saber cuantas páginas vamos a mostrar
$sentencia = $base_de_datos->query("SELECT count(*) AS n_contactos FROM contactes");
$conteo = $sentencia->fetchObject()->n_contactos;


// Para saber el número de páginas dividimos el conteo
// entre los productos por página, y redondeamos hacia arriba
$paginas = ceil($conteo / $productosPorPagina);

// Defino la ordenación por defecto.
$orderBy = 'id';
$orderType = 'ASC';

// Configuro la ordenación para la query
if(isset($_GET["Id"])){
    $orderBy = 'id';
    $orderType = $_GET["Id"];
}else if(isset($_GET["Cognom"])){
    $orderBy = 'cognoms';
    $orderType = $_GET["Cognom"];
}

$orderbyAndType = $orderBy . " " . $orderType;


# Ahora obtenemos los productos usando ya el OFFSET y el LIMIT
$sentencia = $base_de_datos->prepare("SELECT * FROM contactes ORDER BY " . $orderbyAndType  ." LIMIT ? OFFSET ?");
$sentencia->execute([$limit, $offset]);
$contactes = $sentencia->fetchAll(PDO::FETCH_OBJ);


// MOSTRAMOS LOS BOTONES DE: ordenación ascendente o descendente ID o Apellidos
echo "<div class='ordenacion'>";         
echo '<a href="./main.php?Id=', urlencode($Id), '&pagina=',urlencode($pagina),'"> Ordenar por ID ' . $Id . "</a>";
echo '<a href="./main.php?Cognom=', urlencode($Cognom), '&pagina=',urlencode($pagina),'"> Ordenar por Cognom ' . $Cognom. "</a>";
echo "</div>";
// FIN BOTONES DE ORDENACIÓN


echo "<table>";
echo '<thead>';
echo '<tr>';
echo '<th>Id</th>';
echo '<th>Cornom</th>';
echo '<th>Nom</th>';
echo '</tr>';
echo '</thead>';

echo '<tbody>';



// MOSTRAMOS LA INFORMACIÓN
echo "<div class='información'>";    
foreach ($contactes as $contacte) { 
    echo '<tr>';
    echo "<td>" . $contacte->id ."</td>";
    //echo " ";
    echo "<td>" . $contacte->cognoms ."</td>";
    //echo " ";
    echo "<td>" . $contacte->nom ."</td>";
    //echo "<br>";
    echo '</tr>';
}




echo '</tbody>';
echo "</table>";


echo "</div>";
// FIN MOSTRAR INFORMACIÓN


// Inicio paginado
echo "<div class='navegacion'>"; 
echo '<a href="./main.php?pagina=1"> <img src="./assets/img/home.png" alt="inicio" /></a> ';

// Para conservar la ordenación si la url tie variables las mantengo para la siguiente petición.
// Además prevengo que no vayan a paginas inexistentes. Soy consciente de que de alguna forma
// podría ahorrar código repetido, si tengo tiempo miraré como
// intenté solucionarlo con un if aquí arriba y no me funcionaba :o
if (isset($_GET["Cognom"])) {


    if($pagina - 1 == 0 ){
        echo '<a href="./main.php?Cognom=', urlencode($ahoraPaginaCognom), '&pagina=',urlencode($pagina),'"><img src="./assets/img/left.png" alt="atrás"/></a>';
    } else {
        echo '<a href="./main.php?Cognom=', urlencode($ahoraPaginaCognom), '&pagina=',urlencode($pagina - 1),'"><img src="./assets/img/left.png" alt="atrás"/></a>';
    }
    
    if($pagina + 1 > $paginas){   
        echo '<a href="./main.php?Cognom=', urlencode($ahoraPaginaCognom), '&pagina=',urlencode($pagina),'"><img src="./assets/img/right.png" alt="adelante"/></a>';
    } else {
        echo '<a href="./main.php?Cognom=', urlencode($ahoraPaginaCognom), '&pagina=',urlencode($pagina + 1),'"><img src="./assets/img/right.png" alt="adelante"/></a>';
    }


} elseif (isset($_GET["Id"])){

    if($pagina - 1 == 0 ){
        echo '<a href="./main.php?Id=', urlencode($ahoraPaginaID), '&pagina=',urlencode($pagina),'"><img src="./assets/img/left.png" alt="atrás" /></a>';
    } else {
        echo '<a href="./main.php?Id=', urlencode($ahoraPaginaID), '&pagina=',urlencode($pagina - 1),'"><img src="./assets/img/left.png" alt="atrás"/></a>';
    }
    
    if($pagina + 1 > $paginas){   
        echo '<a href="./main.php?Id=', urlencode($ahoraPaginaID), '&pagina=',urlencode($pagina),'"><img src="./assets/img/right.png" alt="adelante"/></a>';
    } else {
        echo '<a href="./main.php?Id=', urlencode($ahoraPaginaID), '&pagina=',urlencode($pagina + 1),'"><img src="./assets/img/right.png" alt="adelante"/></a> ';
    }

} else {

    if($pagina - 1 == 0 ){
        echo '<a href="./main.php?pagina=',urlencode($pagina),'"><img src="./assets/img/left.png" alt="atrás"/></a>';
    } else {
        echo '<a href="./main.php?pagina=',urlencode($pagina - 1),'"><img src="./assets/img/left.png" alt="atrás"/></a>'; 
    }
    
    if($pagina + 1 > $paginas){   
        echo '<a href="./main.php?pagina=',urlencode($pagina),'"><img src="./assets/img/right.png" alt="adelante"/></a>';
    } else {
        echo '<a href="./main.php?pagina=',urlencode($pagina + 1),'"><img src="./assets/img/right.png" alt="adelante"/></a>';
    }

}

//Mostramos la última página
echo '<a href="./main.php?pagina='.$paginas.'"><img src="./assets/img/end.png" alt="final"/></a>';
echo "</div>";
include_once "pie.php";
?>

