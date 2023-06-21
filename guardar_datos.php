<?php
// Establecer la conexión con la base de datos (actualiza los valores según tu configuración)
session_start();
$host = "localhost";
$dbname = "Acceso";
$username = "root";
$password = "";
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener los datos de la URL
$noCuenta = $_GET['noCuenta'];
$nombres = $_GET['nombres'];
$apellidoPaterno = $_GET['apellidoPaterno'];
$apellidoMaterno = $_GET['apellidoMaterno'];
$genero = $_GET['genero'];
$tipoUsuario = $_GET['tipoUsuario'];
$idTarjeta = $_GET['idTarjeta'];
$idHuella = $_GET['idHuella'];

// Función para validar registros duplicados
function validarRegistroDuplicado($pdo, $noCuenta, $idTarjeta, $idHuella) {
    $sql = "SELECT COUNT(*) AS count FROM Usuarios WHERE noCuenta = ? OR idTarjeta = ? OR idHuella = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$noCuenta, $idTarjeta, $idHuella]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $result['count'];
    return $count > 0;

}

// Validar registros duplicados
if (validarRegistroDuplicado($pdo, $noCuenta, $idTarjeta, $idHuella)) {
    $_SESSION['message_type'] = "error";
    $_SESSION['error_message'] = "Ya existe un registro con el mismo número de cuenta, tarjeta o huella.";
    //header('Location: index.html');
    //exit();

    //$_SESSION['message_type'] = "error";
    //$_SESSION['error_message'] = "Error al insertar el registro: " . $e->getMessage();
    header('Location: index.html?error=1');
    exit();

} else {
    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO Usuarios (noCuenta, nombres, apellidoPaterno, apellidoMaterno, genero, tipoUsuario, idTarjeta, idHuella) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$noCuenta, $nombres, $apellidoPaterno, $apellidoMaterno, $genero, $tipoUsuario, $idTarjeta, $idHuella]);
        
        //$_SESSION['message_type'] = "success";
        //$_SESSION['success_message'] = "Registro insertado correctamente.";
        //header('Location: index.html');
        //exit;

        //header('Location: control.html?success=1');
        //exit;
        //header('Location: control.html');
	//$_SESSION['message'] = "Registro insertado correctamente.";
        header('Location: index.html');
	
	//echo "Registro insertado correctamente.";
    } catch (PDOException $e) {
        header('Location: index.html?error=1');

        exit();
        //echo "Error al insertar el registro: " . $e->getMessage();
        //die("Error al insertar el registro: " . $e->getMessage());

    }
}
?>
