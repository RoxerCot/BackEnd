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

if ($_POST['METHOD'] == 'DELORDEN') {
    $id = $_POST['mesaId'];
    $conexion = conectar();
    $sql = "UPDATE mesas SET Orden='0' WHERE Id = $id;";
    mysqli_query($conexion, $sql);
    echo json_encode(['Mensaje' => "Orden Borrado"]);
    desconectar($conexion);
}


if ($_POST['METHOD'] == 'DELEITEMORDER') {
    $id = $_POST['Id'];
    $mesaId = $_POST['mesaId'];
    $conexion = conectar();
    $sql = "SELECT Orden FROM mesas WHERE Id='$mesaId';";
    $resultado = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($resultado);
    $ordrs = preg_split('/-/', $row["Orden"]);
    $index = array_search($id, $ordrs);
    unset($ordrs[$index]);
    $ordrs = array_values($ordrs);
    $updatedIdItems = implode('-', $ordrs);
    $sql = "UPDATE mesas SET Orden = '$updatedIdItems' WHERE Id = $mesaId;";
    mysqli_query($conexion, $sql);
    echo json_encode(['Mensaje' => "Orden Borrado"]);
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
            foreach ($ordrs as $itemId) {
                $sql = "SELECT Nombre,Id FROM productos WHERE Id=$itemId;";
                $resultado2 = mysqli_query($conexion, $sql);
                $item = mysqli_fetch_assoc($resultado2);
                if ($item != null) {
                    $arreglo[$i][0] = $row["Id"];
                    $arreglo[$i][1] = $row["Fecha"];
                    $arreglo[$i][2] = $item["Nombre"];
                    $arreglo[$i][3] = $item["Id"];
                    $i++;
                }
            }
        }
    }
    desconectar($conexion);
    echo json_encode($arreglo);
    exit();
}

if ($_POST['METHOD'] == 'GETNOTES') {

    $conexion = conectar();
    $sql = "SELECT Id,Notas FROM mesas;";
    $resultado = mysqli_query($conexion, $sql);
    $arreglo = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($resultado)) {
        $notas = preg_split('/-/', $row["Notas"]);
        if ($row["Notas"] != 0 && $row["Notas"] != null) {

            $newnote = "";
            foreach ($notas as $nota) {
                $itm = explode("/", $nota)[1];
                $not = explode("/", $nota)[0];
                $sql = "SELECT Nombre FROM productos WHERE Id= $itm;";
                $res = mysqli_query($conexion, $sql);
                $fila = mysqli_fetch_assoc($res);
                // print_r($newnote);
                $newnote = $newnote . $fila['Nombre'] . "</br>" . $not . "</br>";
            }
            $arreglo[$i][0] = $row["Id"];
            $arreglo[$i][1] = $newnote;
            $i++;
        }
    }
    desconectar($conexion);
    echo json_encode($arreglo);
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
    $date = date('Y/m/d h:i:s', time());
    $itemId = $_POST['itemId'];
    $mesaId = $_POST['mesaId'];
    $nota = $_POST['nota'];
    $sql = "SELECT Orden,Notas FROM mesas WHERE Id = $mesaId";
    $resultado = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($resultado);
    if ($row["Orden"] == 0 || $row["Orden"] == null) {
        $newOrdr = $itemId;
    } else {
        $newOrdr = $row["Orden"] . '-' . $itemId;
    }
    if ($row["Notas"] == 0 || $row["Notas"] == null) {
        $newNote = str_replace('-', '', $nota) . "/" . $itemId;
    } else {
        $newNote = $row["Notas"] . '-' . str_replace('-', '', $nota) . "/" . $itemId;
    }
    $sql = "UPDATE mesas SET Orden='$newOrdr', Fecha='$date', Notas='$newNote' WHERE Id=$mesaId;";
    mysqli_query($conexion, $sql);
    desconectar($conexion);
    echo json_encode(['Mensaje' => "Producto Añadido"]);
    exit();
}
