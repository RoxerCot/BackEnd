<?php
// SELECT itemstest.Id, 
// itemstest.Nombre, 
// itemstest.Descripcion, 
// itemstest.Foto, 
// itemstest.Precio, 
// itemstest.Stock,
// itemstest.Marca, 
// categorias.Categoria As Cat,
// itemstest.Modelo, itemstest.Peso, 
// itemstest.Disponibilidad FROM itemstest 
// JOIN categorias 
// ON itemstest.Categoria = categorias.Id; 


header('Access-Control-Allow-Origin: *');


function conectar()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $bd = "testing";
    try {

        $conexion = mysqli_connect($host, $user, $password, $bd);
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }
    return $conexion;
}

function desconectar($conexion)
{


    try {
        $close = mysqli_close($conexion);
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }

    return $close;
}

if ($_POST['METHOD'] == 'INICIOSESION') {
    $conexion = conectar();
    $usr = $_POST['usuario'];
    $pswd = $_POST['password'];
    $sql = "SELECT * from usuarios where Nombre='$usr'";
    $resultado = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($resultado);
    if ($row == null) {
        echo json_encode(['Mensaje' => "Usuario No Existe"]);
    } elseif ($row['Password'] == $pswd) {
        echo json_encode($row);
    } else {
        echo json_encode(['Mensaje' => "Password Incorrecta"]);
    }
    desconectar($conexion);
    exit();
}

if ($_POST['METHOD'] == 'SESION') {
    $conexion = conectar();
    $usr = $_POST['usuario'];
    $sql = "SELECT * from usuarios where Nombre='$usr'";
    $resultado = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($resultado);
    if ($row) {
        if ($row['Nombre'] != null) {
            echo json_encode($row);
        }
    } else {

        echo json_encode(['Mensaje' => "Usuario No Existe"]);
    }
    desconectar($conexion);
    exit();
}

if ($_POST['METHOD'] == 'GETMESAS') {
    $conexion = conectar();
    $sql = "SELECT * from mesas";
    $resultado = mysqli_query($conexion, $sql);
    $arreglo = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($resultado)) {
        $arreglo[$i] = $row;
        $i++;
    }
    desconectar($conexion);
    echo json_encode($arreglo);
    exit();
}

if ($_POST['METHOD'] == 'GETCLIENTES') {
    $conexion = conectar();
    $sql = "SELECT * from clientes";
    $resultado = mysqli_query($conexion, $sql);
    $arreglo = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($resultado)) {
        $arreglo[$i] = $row;
        $i++;
    }
    desconectar($conexion);
    echo json_encode($arreglo);
    exit();
}

if ($_POST['METHOD'] == 'GETPRODUCTOS') {
    $conexion = conectar();
    $sql = " SELECT 
    pr.Id, 
    pr.Nombre, 
    pr.Descripcion, 
    pr.Precio, 
    cat.Name AS Cat
    FROM 
    productos pr
    JOIN 
    categoriasproductos cat 
    ON 
    pr.Categoria = cat.Id;";
    $resultado = mysqli_query($conexion, $sql);
    $arreglo = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($resultado)) {
        $arreglo[$i] = $row;
        $i++;
    }
    desconectar($conexion);
    echo json_encode($arreglo);
    exit();
}

if ($_POST['METHOD'] == 'DELETEPRODUCTO') {
    $id = $_POST['Id'];
    $conexion = conectar();
    $sql = "DELETE FROM productos WHERE Id = $id;";
    mysqli_query($conexion, $sql);
    echo json_encode(['Mensaje' => "Producto Borrado"]);
    desconectar($conexion);
    exit();
}

if ($_POST['METHOD'] == 'DELETECLIENTE') {
    $id = $_POST['Id'];
    $conexion = conectar();
    $sql = "DELETE FROM clientes WHERE Id = $id;";
    mysqli_query($conexion, $sql);
    echo json_encode(['Mensaje' => "Cliente Borrado"]);
    desconectar($conexion);
}

if ($_POST['METHOD'] == 'GETCATEGORIAS') {

    $conexion = conectar();
    $sql = "SELECT * from categoriasproductos;";
    $resultado = mysqli_query($conexion, $sql);
    $arreglo = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($resultado)) {
        $arreglo[$i] = $row;
        $i++;
    }
    desconectar($conexion);
    echo json_encode($arreglo);
    exit();
}

if ($_POST['METHOD'] == 'GETMESAS') {

    $conexion = conectar();
    $sql = "SELECT * from mesas;";
    $resultado = mysqli_query($conexion, $sql);
    $arreglo = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($resultado)) {
        $arreglo[$i] = $row;
        $i++;
    }
    desconectar($conexion);
    echo json_encode($arreglo);
    exit();
}

if ($_POST['METHOD'] == 'GETORDERS') {

    $conexion = conectar();
    $sql = "SELECT Id,Orden,Fecha FROM mesas;";
    $resultado = mysqli_query($conexion, $sql);
    $arreglo = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($resultado)) {
        $ordrs = preg_split('/-/', $row["Orden"]);
        if ($row["Orden"] != 0 && $row["Orden"] != null) {
            print_r($row["Id"]);
            foreach ($ordrs as $itemId) {
                $sql = "SELECT Nombre FROM productos WHERE Id=$itemId;";
                $resultado2 = mysqli_query($conexion, $sql);
                $item = mysqli_fetch_assoc($resultado2);
                if ($item != null) {
                    $arreglo[$i][0] = $row["Id"];
                    $arreglo[$i][1] = $row["Fecha"];
                    $arreglo[$i][2] = $item["Nombre"];
                    $i++;
                }
            }
        }
    }
    desconectar($conexion);
    // echo json_encode($arreglo);
    exit();
}

if ($_POST['METHOD'] == 'EDITITEM') {

    $conexion = conectar();
    $itemId = $_POST['itemId'];
    $newvalue = $_POST['newvalue'];
    $cat = $_POST['cat'];
    if ($cat == "Categoria" || $cat == "Precio") {
        $sql = "UPDATE productos SET $cat = $newvalue WHERE Id=$itemId;";
    } else {
        $sql = "UPDATE productos SET $cat = '$newvalue' WHERE Id=$itemId;";
    }
    print_r($sql);
    mysqli_query($conexion, $sql);
    desconectar($conexion);
    echo json_encode(['Mensaje' => "Producto Editado"]);
    exit();
}

if ($_POST['METHOD'] == 'ADDITEM') {

    $conexion = conectar();
    $nmbr = $_POST['nombre'];
    $descr = $_POST['descripcion'];
    $prc = $_POST['precio'];
    $cat = $_POST['categoria'];
    $sql = "INSERT INTO productos (Categoria,Nombre,Precio,Descripcion)
    VALUES ($cat,'$nmbr',$prc,'$descr');";
    mysqli_query($conexion, $sql);
    desconectar($conexion);
    echo json_encode(['Mensaje' => "Producto Añadido"]);
    exit();
}


if ($_POST['METHOD'] == 'ADDCLIENTE') {

    $conexion = conectar();
    $nmbr = $_POST['nombre'];
    $direc = $_POST['direccion'];
    $tel = $_POST['telefono'];
    $cdd = $_POST['ciudad'];
    $rfc = $_POST['rfc'];
    $sql = "INSERT INTO clientes (Nombre,Domicilio,Telefono,Ciudad,RFC)
    VALUES ('$nmbr','$direc',$tel,'$cdd','$rfc');";
    mysqli_query($conexion, $sql);
    desconectar($conexion);
    echo json_encode(['Mensaje' => "Producto Añadido"]);
    exit();
}

if ($_POST['METHOD'] == 'ADDORDER') {

    $conexion = conectar();
    date_default_timezone_set('America/Mexico_City');
    print_r(date_default_timezone_get());
    $date = date('Y/m/d h:i:s', time());
    $itemId = $_POST['itemId'];
    $mesaId = $_POST['mesaId'];
    $sql = "SELECT Orden FROM mesas WHERE Id = $mesaId";
    $resultado = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($resultado);
    if ($row["Orden"] == 0 || $row["Orden"] == null) {
        $newOrdr = $itemId;
    } else {
        $newOrdr = $row["Orden"] . '-' . $itemId;
    }
    $sql = "UPDATE mesas SET Orden='$newOrdr', Fecha='$date' WHERE Id=$mesaId;";
    mysqli_query($conexion, $sql);
    desconectar($conexion);
    echo json_encode(['Mensaje' => "Producto Añadido"]);
    exit();
}
