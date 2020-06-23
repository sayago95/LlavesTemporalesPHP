<?php

$usuario = "root";
$passwd = "";
$servidor = "localhost";
$bd = "esw";

$clave = $_POST['clave'];
$tipo = $_POST['tipo']; 
$mensaje = array("status"=>"");

$conexion = mysqli_connect( $servidor, $usuario, $passwd,$bd );

if(!$conexion)
{
    $mensaje["status"]="nok";
}
else
{
    date_default_timezone_set('America/Mexico_city');
    $hoy = getdate();
    $hora=$hoy['hours'];
    $min=$hoy['minutes'];
    
    if($tipo=='agregar')
    {
        
        $del = "select * from acceso";
        $dep = mysqli_query( $conexion, $del ); 

        while($fila_dep = mysqli_fetch_array($dep))
        { 
            $llave=$fila_dep['llave'];
            $h=$fila_dep['hora'];
            $m=$fila_dep['min'];
            if($hora-$h==0 and $min-$m<10)
            {

            }
            else
            {
                $minT = 60;
                $dif = $minT-$m+$min;
                if($hora-$h==1 and $dif<10){
                
                }
                else
                {
                    $depp="DELETE FROM acceso WHERE llave='$llave'";
                    $depp_clv= mysqli_query( $conexion, $depp);                 
                }
            }
        } 
         
        $insert_clv="INSERT INTO acceso(llave,hora,min) VALUES ('$clave','$hora','$min')";
        $clv_insertada = mysqli_query( $conexion, $insert_clv );

        if(!$clv_insertada)
        {
            $mensaje["status"]="nok";
        }
        else
        {
        $mensaje["status"]="ok";
           
        } 
    }
        
    if($tipo=='validar')
    {
        date_default_timezone_set('America/Mexico_city');        
        $con_clave="SELECT * FROM acceso WHERE llave='$clave'";
        $clv= mysqli_query( $conexion, $con_clave ); 
        $fila_clv =mysqli_fetch_array($clv);

        $c=$fila_clv["llave"];
        $h=$fila_clv["hora"];
        $m=$fila_clv["min"];
        $hoy = getdate();
        $hora=$hoy['hours'];
        $min=$hoy['minutes'];

        if($hora-$h==0 and $min-$m<10)
        {
            $actualiza_clv="UPDATE acceso SET hora='$hora', min='$min' WHERE llave='$clave'";
            $clv_u= mysqli_query( $conexion, $actualiza_clv); 
            if(!$clv_u)
            {
                $mensaje["status"]="nok"; 
            }
            else
            {
                $mensaje["status"]="ok"; 
            }  
        }
        else
        {
            $minT = 60;
            $dif = $minT-$m+$min;
            if($hora-$h==1 and $dif<10){
                $actualiza_clv="UPDATE acceso SET hora='$hora', min='$min' WHERE llave='$clave'";
                $clv_u= mysqli_query( $conexion, $actualiza_clv); 
                if(!$clv_u)
                {
                    $mensaje["status"]="nok"; 
                }
                else
                {
                    $mensaje["status"]="ok"; 
                }                  
            }
            else
            {
                $elimina_clv="DELETE FROM acceso WHERE llave='$clave'";
                $el_clv= mysqli_query( $conexion, $elimina_clv); 
                $mensaje["status"]="nok";                
            }
        }
    }
        
}
echo json_encode($mensaje);
    
?>
