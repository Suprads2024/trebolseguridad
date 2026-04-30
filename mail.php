<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

// Email donde querés recibir los mensajes
$destinatario = "tuemail@tudominio.com";

// Limpiar y validar datos
$nombre = trim($_POST["nombre"] ?? "");
$email = trim($_POST["email"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$mensaje = trim($_POST["mensaje"] ?? "");
$intereses = $_POST["interes"] ?? [];

if ($nombre === "" || $email === "" || $mensaje === "") {
    die("Por favor completá los campos obligatorios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("El email ingresado no es válido.");
}

// Sanitizar intereses
$interesesLimpios = [];

if (is_array($intereses)) {
    foreach ($intereses as $interes) {
        $interesesLimpios[] = htmlspecialchars($interes, ENT_QUOTES, "UTF-8");
    }
}

$interesesTexto = empty($interesesLimpios)
    ? "No especificado"
    : implode(", ", $interesesLimpios);

// Asunto del email
$asunto = "Nuevo mensaje desde el formulario web";

// Cuerpo del email
$cuerpo = "
Nuevo mensaje recibido desde el sitio web:

Nombre / Empresa: $nombre
Email: $email
Teléfono: $telefono

Interés en:
$interesesTexto

Mensaje:
$mensaje
";

// Cabeceras
$headers = "From: Sitio Web <no-reply@tudominio.com>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Enviar email
$enviado = mail($destinatario, $asunto, $cuerpo, $headers);

if ($enviado) {
    echo "Mensaje enviado correctamente.";
} else {
    echo "Hubo un error al enviar el mensaje.";
}