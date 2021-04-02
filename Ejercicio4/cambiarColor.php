<?php
    //error_reporting(E_ERROR | E_WARNING | E_PARSE);
    include "conection.inc.php";
    $color=$_POST["color"];  
    $usuario=$_POST["usuario"];
    $sql="update usuario set color='".$color."'  where usuario='$usuario'";
    mysqli_query($con, $sql);
    echo $color;
    echo "<br>";
    echo $usuario;
    header('Location: index.html');