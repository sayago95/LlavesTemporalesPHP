<!DOCTYPE html>
<html lang="es" class="no-js" > 
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>Practica 1 Servicios Web</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/style3.css">
        <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" integrity="sha384-kW+oWsYx3YpxvjtZjFXqazFpA7UP/MbiY4jvs+RWZo2+N94PFZ36T6TFkc9O3qoB" crossorigin="anonymous"></script>
        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    </head>
    <body >
        <div class="container">
            <section>				
                <div id="container_demo">
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form  id="formi" method="POST" autocomplete="on"> 
                                <h1>Login </h1>
                                <p> 
                                   <label for="username" class="uname" data-icon="u" > User </label>
                                   <input id="username" name="username" required="required" type="text" placeholder="username"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Password </label>
                                    <input id="password" name="password" required="required" type="password" placeholder="ej. X8df!90EO" /> 
                                </p>
                                <p class="login button"> 
                                <input type="submit" value="Login" id="ingresar"/>
                                </p>
                            </form>
                        </div>
                    </div>
                </div> 
            </section> 
        </div>
        <div class="box"> <!-- busqueda -->
            <div class="container-4" id="buscar" style="display: none">
                <form  id="formil" method="POST" autocomplete="on" > 
                    <h1 style="color: #E2F1E9; font-size:50px;" id="caja"> BUSQUEDA  </h1> 
                    <input type="search" id="search" placeholder="buscar..." />
                    <p class="buscar"> 
                        <input type="submit" value="Buscar" id="busqueda"/> 
                    </p>
                </form>
            </div>
        </div>
        <div>
            <br>
            <br>
            <br>
            <br>
        </div>
       <div id="container">
       <div id="registrado"></div>
       </div>    

    </body>
</html>


<script>
    var mensaje;
    var clave;  //esta variable es la que contiene la clave 
    function muestra_oculta(id)
    {
        if (document.getElementById)
        { 
            var el = document.getElementById(id); 
            el.style.display = (el.style.display == 'none') ? 'block' : 'none';
        }
    }
    
     //Aqui se envia el usuario y la password para que se verifique y se cree la clave
    $(document).ready(function()
    {
        $('#ingresar').click(function()
        {
            var datos=$('#formi').serialize();
            $.ajax({
                type: "POST",
                url: "proceso.php",
                data: datos,
                success: function(response)
                {
                    mensaje = JSON.parse(response);
                    if(mensaje.status == "ok")
                    {
                        clave = mensaje.key;
                        muestra_oculta('buscar');
                        document.getElementById("container_demo").style.display = "none";
                        $('body').css("background-color", "#6B977F");
                        document.getElementById("buscar").style.visibility  = "visible";
                    }
                    else
                    {
                        if(mensaje.status== "El tiempo de inactividad ha superado los 10 minutos")
                        {
                            var ale=mensaje.status;
                            alert(ale);
                            location.reload();    
                        }
                        else
                        {
                            var ale=mensaje.status;
                            alert(ale);
                        }
                        
                        
                    }
                }
            });
           return false;
        });
    });
    
    //METODO DE ELIMINAR
    $(document).ready(function()
    {
        $(document).on("click","#eliminar",function(){
            if(confirm("esta seguro de que desea eliminar este registro."))
            {
                var id= $(this).data("id");
                alumno=document.getElementById("search").value;
                var parametros ={
                    "id" : id,
                    "clave" : clave,
                    "alumno" : alumno
                };
                $.ajax({
                    url: "eliminar.php",
                    method: "POST",
                    data: parametros,
                    success: function(answer)
                    {
                        var r = JSON.parse(answer);
                        if(r.status == "ok")
                        {
                            document.getElementById("container_demo").style.display = "none";
                            document.getElementById("buscar").style.visibility  = "visible";
                        
                            var out="<table border='1px' align='center' cellpadding='10' width='50%' height='50%'>";
                            out+="<tr>";
                                out+="<th>ID</th>";
                                out+="<th>Nombre</th>";
                                out+="<th>Carrera</th>";
                                out+="<th>Seccion</th>";
                                out+="<th>Fecha de ingreso</th>";
                                out+="<th>Eliminar/Modificar</th>";
                            out+="</tr>";
                        
                            var id;
                            for (var i in r.busqueda) 
                            {
                                out+="<tr align='center'>";
                                id = r.busqueda[i].id_alumno;
                                out+="<td>" + id + "</td>" + 
                                "<td id='nombre_usuario' data-id_nombre='" + id + "' contenteditable>" + r.busqueda[i].nombre + "</td>" +
                                "<td id='carrera_usuario' data-id_carrera='" + id + "' contenteditable>" + r.busqueda[i].carrera + "</td>" + 
                                "<td id='seccion_usuario' data-id_seccion='" + id + "' contenteditable>" + r.busqueda[i].seccion +  "</td>" + 
                                "<td id='fecha_usuario' data-id_fecha='" + id + "' contenteditable>" + r.busqueda[i].fecha_ingreso +"</td>" +
                                "<td><button id='eliminar' data-id='" + id + "'>Eliminar</button></td>";          
                            out+="</tr>";
                            }
                            out+="<tr align='center'>" +
                            "<td></td>" +
                            "<td id='nombre_add' contenteditable></td>" +
                            "<td id='carrera_add' contenteditable></td>" +
                            "<td id='seccion_add' contenteditable></td>" +
                            "<td id='fecha_add' contenteditable></td>" +
                            "<td><button id='add'>Agregar</button></td>" +
                            "</tr>";
                            out+="</table>";
                        
                            $("#registrado").html(out);
                        }
                        else
                        {
                            if(r.status== "El tiempo de inactividad ha superado los 10 minutos")
                            {
                                var ale=r.status;
                                alert(ale);
                                location.reload();    
                            }
                            else
                            {
                                var ale=r.status;
                                alert(ale);
                            }
                        }
                    }
                })
            }
        })
    });
    
    // Metodo  de busqueda 
    $(document).ready(function()
    {
        $('#busqueda').click(function()
        {
           alumno=document.getElementById("search").value;
            var parametros = {
                "alumno" : alumno,
                "clave" : clave
            };
            $.ajax({
                type: "POST",
                url: "proceso_buscar.php",
                data:parametros,
                success: function(result)
                {
                    var respuesta = JSON.parse(result);
                    if(respuesta.status == "ok")
                    {
                        document.getElementById("container_demo").style.display = "none";
                        document.getElementById("buscar").style.visibility  = "visible";
                        
                        //var output="<br>"+"<br>";
                        var output="<table border='1px' align='center' cellpadding='10' width='50%' height='50%'>";
                        output+="<tr>";
                            output+="<th>ID</th>";
                            output+="<th>Nombre</th>";
                            output+="<th>Carrera</th>";
                            output+="<th>Seccion</th>";
                            output+="<th>Fecha de ingreso</th>";
                            output+="<th>Eliminar/Modificar</th>";
                        output+="</tr>";
                        
                        var id;
                        for (var i in respuesta.busqueda) 
                        {
                            output+="<tr align='center'>";
                            id = respuesta.busqueda[i].id_alumno;
                            output+="<td >" + id + "</td>" + 
                                "<td id='nombre_usuario' data-id_nombre='" + id + "' contenteditable>" + respuesta.busqueda[i].nombre + "</td>" +
                                "<td id='carrera_usuario' data-id_carrera='" + id + "' contenteditable>" + respuesta.busqueda[i].carrera + "</td>" + 
                                "<td id='seccion_usuario' data-id_seccion='" + id + "' contenteditable>" + respuesta.busqueda[i].seccion +  "</td>" + 
                                "<td id='fecha_usuario' data-id_fecha='" + id + "' contenteditable>" + respuesta.busqueda[i].fecha_ingreso +"</td>" +
                                "<td><button id='eliminar' data-id='" + id + "'>Eliminar</button></td>";                           
                            output+="</tr>";
                        }
                        output+="<tr align='center'>" +
                        "<td></td>" +
                        "<td id='nombre_add' contenteditable></td>" +
                        "<td id='carrera_add' contenteditable></td>" +
                        "<td id='seccion_add' contenteditable></td>" +
                        "<td id='fecha_add' contenteditable></td>" +
                        "<td><button id='add'>Agregar</button></td>" +
                        "</tr>";
                        output+="</table>";
                        
                        $("#registrado").html(output);
                    }
                    else
                    {
                        if(respuesta.status== "El tiempo de inactividad ha superado los 10 minutos")
                        {
                            var ale=respuesta.status;
                            alert(ale);
                            location.reload();    
                        }
                        else
                        {
                            var ale=respuesta.status;
                            alert(ale);
                        }
                    }
                }
                
            });
            return false;
        });

    });
    
    //METODO DE AGREGAR
    $(document).ready(function()
                      {
        $(document).on("click","#add",function(){
               
            var nombre = $("#nombre_add").text();
            var carrera = $("#carrera_add").text();
            var seccion = $("#seccion_add").text();
            var fecha = $("#fecha_add").text();
            alumno = document.getElementById("search").value;
            
            var parametros = {
                "nombre": nombre,
                "carrera": carrera,
                "seccion": seccion,
                "fecha": fecha,
                "clave" : clave,
                "alumno" : alumno
            };
                
            $.ajax({
                url: "agregar.php",
                method: "POST",
                data: parametros,
                success: function(ans)
                {
                    var res = JSON.parse(ans);
                    if(res.status == "ok")
                    {
                        document.getElementById("container_demo").style.display = "none";
                        document.getElementById("buscar").style.visibility  = "visible";

                        var out="<table border='1px' align='center' cellpadding='10' width='50%' height='50%'>";
                        out+="<tr>";
                        out+="<th>ID</th>";
                        out+="<th>Nombre</th>";
                        out+="<th>Carrera</th>";
                        out+="<th>Seccion</th>";
                        out+="<th>Fecha de ingreso</th>";
                        out+="<th>Eliminar/Modificar</th>";
                        out+="</tr>";

                        var id;
                        for (var i in res.busqueda) 
                        {
                            out+="<tr align='center'>";
                            id = res.busqueda[i].id_alumno;
                            out+="<td>" + id + "</td>" + 
                            "<td id='nombre_usuario' data-id_nombre='" + id + "' contenteditable>" + res.busqueda[i].nombre + "</td>" +
                            "<td id='carrera_usuario' data-id_carrera='" + id + "' contenteditable>" + res.busqueda[i].carrera + "</td>" + 
                            "<td id='seccion_usuario' data-id_seccion='" + id + "' contenteditable>" + res.busqueda[i].seccion +  "</td>" + 
                            "<td id='fecha_usuario' data-id_fecha='" + id + "' contenteditable>" + res.busqueda[i].fecha_ingreso +"</td>" +
                            "<td><button id='eliminar' data-id='" + id + "'>Eliminar</button></td>";      
                            out+="</tr>";
                        }
                        out+="<tr align='center'>" +
                            "<td></td>" +
                            "<td id='nombre_add' contenteditable></td>" +
                            "<td id='carrera_add' contenteditable></td>" +
                            "<td id='seccion_add' contenteditable></td>" +
                            "<td id='fecha_add' contenteditable></td>" +
                            "<td><button id='add'>Agregar</button></td>" +
                            "</tr>";
                        out+="</table>";

                        $("#registrado").html(out);
                    }
                    else
                    {
                        if(res.status== "El tiempo de inactividad ha superado los 10 minutos")
                        {
                            var ale=res.status;
                            alert(ale);
                            location.reload();    
                        }
                        else
                        {
                            var ale=res.status;
                            alert(ale);
                        }
                    }
                }
            })
            
        })
    });
    
    //METODO DE ACTUALIZAR
    $(document).ready(function(){
        alumno = document.getElementById("search").value;   
        function actualizar_datos(id,texto,columna)
        {
            $.ajax({
                url: "actualizar.php",
                method: "POST",
                data: {id: id,texto: texto, columna: columna, clave: clave, alumno: alumno},
                success: function(an)
                {
                    var resu = JSON.parse(an);
                    if(resu.status == "ok")
                    {
                        document.getElementById("container_demo").style.display = "none";
                        document.getElementById("buscar").style.visibility  = "visible";

                        var out="<table border='1px' aling='center' align='center' cellpadding='10' width='50%' height='50%'>";
                        out+="<tr>";
                        out+="<th>ID</th>";
                        out+="<th>Nombre</th>";
                        out+="<th>Carrera</th>";
                        out+="<th>Seccion</th>";
                        out+="<th>Fecha de ingreso</th>";
                        out+="<th>Eliminar/Modificar</th>";
                        out+="</tr>";

                        var id;
                        for (var i in resu.busqueda) 
                        {
                            out+="<tr align='center'>";
                            id = resu.busqueda[i].id_alumno;
                            out+="<td>" + id + "</td>" + 
                            "<td id='nombre_usuario' data-id_nombre='" + id + "' contenteditable>" + resu.busqueda[i].nombre + "</td>" +
                            "<td id='carrera_usuario' data-id_carrera='" + id + "' contenteditable>" + resu.busqueda[i].carrera + "</td>" + 
                            "<td id='seccion_usuario' data-id_seccion='" + id + "' contenteditable>" + resu.busqueda[i].seccion +  "</td>" + 
                            "<td id='fecha_usuario' data-id_fecha='" + id + "' contenteditable>" + resu.busqueda[i].fecha_ingreso +"</td>" +
                            "<td><button id='eliminar' data-id='" + id + "'>Eliminar</button></td>";         
                            out+="</tr>";
                        }
                        out+="<tr align='center'>" +
                                "<td></td>" +
                                "<td id='nombre_add' contenteditable></td>" +
                                "<td id='carrera_add' contenteditable></td>" +
                                "<td id='seccion_add' contenteditable></td>" +
                                "<td id='fecha_add' contenteditable></td>" +
                                "<td><button id='add'>Agregar</button></td>" +
                                "</tr>";
                            out+="</table>";

                            $("#registrado").html(out);
                    }
                    else
                    {
                        if(resu.status== "El tiempo de inactividad ha superado los 10 minutos")
                        {
                            var ale=resu.status;
                            alert(ale);
                            location.reload();    
                        }
                        else
                        {
                            var ale=resu.status;
                            alert(ale);
                        }
                    }
                }
            })
        }
        
        $(document).on("blur","#nombre_usuario",function(){
            var id = $(this).data("id_nombre");
            var nombre = $(this).text();
            actualizar_datos(id,nombre,"nombre");
        })

        $(document).on("blur","#carrera_usuario",function(){
            var id = $(this).data("id_carrera");
            var carrera = $(this).text();

            actualizar_datos(id,carrera,"carrera");

        })

        $(document).on("blur","#seccion_usuario",function(){
            var id = $(this).data("id_seccion");
            var seccion = $(this).text();

            actualizar_datos(id,seccion,"seccion");

        })

        $(document).on("blur","#fecha_usuario",function(){
            var id = $(this).data("id_fecha");
            var fecha = $(this).text();

            actualizar_datos(id,fecha,"fecha_ingreso");

        })
    });   
</script>