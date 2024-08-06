<?php
require 'vendor/phpmailer/class.phpmailer.php';
require 'template.php';
//get data
$data = file_get_contents('php://input');
$data = json_decode($data);
//Armo el pdf

$template = makeTemplate($data);
if (!empty($template)) {
    //Mail
    //get cuerpo del mail
    $cuerpo = file_get_contents('docs/mailTemplate.html');
    $cuerpo = str_replace('{cliente}', $data->cliente, $cuerpo);
    $cuerpo = str_replace('{campo}', $data->campo_descarga, $cuerpo);
    $asunto = 'Bauhaus EE SA - Pedido N° ' . $data->numero_pedido;
    //instancia de phpmailer
    $mail = new phpmailer();
    $mail->setLanguage('es');
    $mail->charSet = 'UTF-8';
    $mail->SMTPAuth = true;
    $mail->Host = "c2400679.ferozo.com";
    $mail->Port = 465;
    $mail->Username = "entregas-noreply@somosbauhaus.com.ar";
    $mail->Password = "PFp@N@F7tR";
    $mail->From = "entregas-noreply@somosbauhaus.com.ar";
    $mail->AddEmbeddedImage('assets/img/bauhaus.jpg', 'logo');
    $mail->FromName = "Entregas Bauhaus";
    $mail->Subject = utf8_decode($asunto);
    $mail->Body = utf8_decode($cuerpo);
    $mail->IsHTML(true);
    //$mail->AddAddress('desarrollo@pep.com.ar');
    $mail->AddAddress($data->mail);
    $mail->addBCC('entregas-noreply@somosbauhaus.com.ar');
    $mail->AddStringAttachment($template, "Detalle descarga.pdf");
    if ($mail->Send()) echo 'si';
    else echo 'no';
}
