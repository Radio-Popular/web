<?php

$mail_to_address = 'info@radiojcb.com.ar';
$mail_subject = 'Formulario de contacto sitio 1290 AM';

$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;
$smtp_security = 'tls';
$smtp_username = 'cadenajcb@gmail.com';
$smtp_password = 'ahXaef8o';



date_default_timezone_set('Etc/UTC');
require_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $mail = new PHPMailer;

  $mail->Body = '';
  if (!empty($_POST['form1_email'])) {
    $mail->addReplyTo(
      $_POST['form1_email'],
      empty($_POST['form1_nombre']) ? '' : $_POST['form1_nombre']
    );
  } else {
    if (!empty($_POST['form1_email']))
      $mail->Body .= 'Email: ' . $_POST['form1_email'] . PHP_EOL;
    if (!empty($_POST['form1_nombre']))
      $mail->Body .= 'Nombre: ' . $_POST['form1_nombre'] . PHP_EOL;
  }


  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->Host = $smtp_host;
  $mail->Port = $smtp_port;
  $mail->SMTPSecure = $smtp_security;
  $mail->Username = $smtp_username;
  $mail->Password = $smtp_password;

  $mail->addAddress($mail_to_address);
  $mail->Subject = $mail_subject;
  $mail->Body .= wordwrap($_POST['form1_comentario'], 70);

  $ok = $mail->send();

  if (!$ok) {
    header('HTTP/1.1 500 Internal Server Error');
    echo $mail->ErrorInfo;
  }

  if ($ok && !array_key_exists('ajax', $_POST)) {
?>
<html>
  <head><link rel="stylesheet" type="text/css" href="css/one-page-wonder.css"></head>
  <body><br><p class="container alert alert-success">Mensaje enviado correctamente.</p></body>
</html>
<?php
  }
}

?>
