<?php
$nombre=$_POST['nombre'];
$carrera=$_POST['carrera'];
$seccion=$_POST['seccion'];
$fecha=$_POST['fecha'];
$clave=$_POST['clave'];
$alumno=$_POST['alumno'];

$usuario = "root";
$passwd = "";
$servidor = "localhost";
$bd = "esw";

$mensaje = [
    "status" => "",
    "busqueda" => ""
];
$conexion = mysqli_connect( $servidor, $usuario, $passwd, $bd);

if(!$conexion)
{
    $error = "Error al conectar con MySQL";
    $mensaje["status"]=$error;
}
else
{    
    if($nombre!= null and $carrera!= null and $seccion!= null and $clave!= null and $fecha!= null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://localhost/claves/acceso.php");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "clave=$clave&&tipo=validar");
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($curl);
        curl_close($curl);
        $ins = json_decode($resp,true);

        if($ins["status"]=="ok")
        {   
            $query = "INSERT INTO alumnos(nombre,carrera,seccion,fecha_ingreso) VALUES ('$nombre','$carrera','$seccion','$fecha')";
            $res = mysqli_query($conexion,$query);

            if(!$res)
            {
                $error = "error al insertar alumno en BD";
                $mensaje["status"]=$error;
            }
            else
            {
                $userData = array();
                $add = "SELECT * FROM alumnos WHERE id_alumno LIKE '%".$nombre."%' OR nombre LIKE '%".$nombre."%' OR carrera LIKE '%".$nombre."%'";
                $resl = mysqli_query($conexion,$add);
                while($fila = mysqli_fetch_array($resl))
                { 
                    $id=$fila['id_alumno'];
                    $nombre=$fila['nombre'];
                    $carrera=$fila['carrera'];
                    $seccion=$fila['seccion'];
                    $fecha=$fila['fecha_ingreso'];

                    $userData[] = array('id_alumno'=> $id, 'nombre'=> $nombre, 'carrera'=> $carrera, 'seccion'=> $seccion,'fecha_ingreso'=> $fecha);
                }   
                $error = "ok";
                $mensaje["status"]=$error;
                $mensaje["busqueda"]=$userData;

            }                  
        }
        else
        {
            $error = "El tiempo de inactividad ha superado los 10 minutos";
            $mensaje["status"]=$error;
        }             
    }
    else
    {
        $error = "Faltan campos por llenar";
        $mensaje["status"]=$error;
    }
    
}
mysqli_close( $conexion );
echo json_encode($mensaje);

?>
