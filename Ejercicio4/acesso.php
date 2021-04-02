<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
include "conection.inc.php";
$usuario = $_POST["usuario"];
$pass = $_POST["pass"];
$resultado = mysqli_query($con, "select * from usuario where usuario='" . $usuario . "'");
$resultado2 =  mysqli_query($con, "select * from persona as p, usuario as u where u.usuario='" . $usuario . "' and u.ci=p.ci");
$resultado3 =  mysqli_query($con, "select * from nota as n, usuario as u where u.usuario='" . $usuario . "' and u.ci=n.ci");
$resultado4 =  mysqli_query($con, "select * from nota");
$resultado5 =  mysqli_query($con, "select * from persona");
$fila = mysqli_fetch_array($resultado);
$fila2 = mysqli_fetch_array($resultado2);
//$fila3 = mysqli_fetch_array($resultado3);
if ($fila['ci'] == null) {
    echo "no existe usuario";
    echo " <a href='index.html'>Volver a intenetarlo</a> ";
} else {

    if ($pass == $fila['pass']) {
        $nombre = $fila2['nombre'];
        $color = $fila['color'];
        $tipo = $fila['tipo'];
        if ($tipo == 'est') {
            $tipo = ' Estudiante';
        } else {
            $tipo = ' Docente';
        }
        echo " <body bgcolor='$color'>";
        echo "<center><h2>Bien venid@ " . $tipo . " " . $nombre . "</h2></center>";
        echo "<center><h4>Esta es tu pantalla de acceso</h4></center>";
        if ($tipo == ' Estudiante') {
?>
            <table border="1px">
                <tr>
                    <td>Materia</td>
                    <td>Nota1</td>
                    <td>Nota2</td>
                    <td>Nota3</td>
                    <td>Nota Final</td>
                </tr>
                <?php
                while ($fila3 = mysqli_fetch_array($resultado3)) {
                    echo "<tr>";
                    echo "<td>$fila3[sigla]</td>";
                    echo "<td>$fila3[nota1]</td>";
                    echo "<td>$fila3[nota2]</td>";
                    echo "<td>$fila3[nota3]</td>";
                    echo "<td>$fila3[notaF]</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        <?php
        } else {
        ?>
            <table border="1px">
            <tr>
                <td>Materia</td>
                <td>CH</td>
                <td>LP</td>
                <td>CB</td>
                <td>OR</td>
                <td>PT</td>
                <td>TJ</td>
                <td>SC</td>
                <td>BN</td>
                <td>PN</td>
            </tr>
            <?php
            $count = 0;
            $mat = "";
            $vecMat[0] = "";
            $vecNotas[0][0]="";
            $vecPersonas[0][0]="";
            $punteroN=0;
            while ($fila4 = mysqli_fetch_array($resultado4)) {
                if (buscar($vecMat, $count, $fila4['sigla'])) {
                } else {
                    $vecMat[$count] = $fila4['sigla'];
                    $count++;
                }
                $vecNotas[$punteroN][0]=$fila4['ci'];
                $vecNotas[$punteroN][1]=$fila4['sigla'];
                $vecNotas[$punteroN][2]=$fila4['notaF'];
                $punteroN++;
            }
            $punteroP=0;
            while ($fila5 = mysqli_fetch_array($resultado5)) {
                $vecPersonas[$punteroP][0]=$fila5['ci'];
                $vecPersonas[$punteroP][1]=$fila5['depto'];
                $punteroP++;
            }
            
           for($i=0;$i<$count;$i++){
               echo "<tr>";
               echo "<td>".$vecMat[$i]."</td>";
               echo "<td>".promedio($vecMat[$i],'01',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'02',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'03',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'04',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'05',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'06',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'07',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'08',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "<td>".promedio($vecMat[$i],'09',$vecNotas,$vecPersonas,$punteroN,$punteroP)."</td>";
               echo "</tr>";
           }

            ?>
            </table>
            
        <?php
        }
        echo "<form action='cambiarColor.php' method='POST'>";
        echo "<input type='hidden' name='usuario' value=" . $usuario . "><br>";
        ?>
        <label>Seleciona color de personalización de tu pantalla de acceso</label><br>
        <select name="color">
            <option selected>Selecciona color</option>
            <option value="white">Blanco</option>
            <option value="red">Rojo</option>
            <option value="blue">Azul</option>
            <option value="yellow">Amarillo</option>
            <option value="tan">Cafe</option>
            <option value="green">Verde</option>
            <option value="aqua">Celeste</option>
        </select>
        <button type="submit">CAMBIAR</button>
        <center>
            <h4><a href="index.html">Salir</a></h4>
        </center>
        </form>
<?php
        echo "</body>";
    } else {
        echo "Contraseña incorrecta";
        echo " <a href='index.html'>Volver a intenetarlo</a> ";
    }
}

function buscar($vec, $lon, $cosa)
{
    $contador = 0;
    for ($i = 0; $i < $lon; $i++) {
        if ($vec[$i] == $cosa) {
            $contador++;
        }
    }
    if ($contador > 0) {
        return true;
    }
    return false;
}

function promedio($mat, $dep, $vecN, $vecP,$pn,$pp)
{
    $prom = 0;
    $sum = 0;
    $cant = 1;
    
    for($i=0;$i<$pn;$i++){
        $comporbar=false;
        $ci=$vecN[$i][0];
        $sig=$vecN[$i][1];
        $notaf=$vecN[$i][2];
        for ($j = 0; $j < $pp; $j++) {
            if ($vecP[$j][0] == $ci and $vecP[$j][1]==$dep) {
                $comporbar=true;
            }
        }
        
        if($sig==$mat and $comporbar and $notaf>0){
            $sum=$sum+$notaf;
            $prom=$sum/$cant;
            $cant++;
        }

    }
    return $prom;
}

?>