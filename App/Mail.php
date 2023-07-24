<?php

namespace App;



# Include the Autoloader (see "Libraries" for install instructions)
// require 'vendor/autoload.php';
use App\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require "/vendor/autoload.php";

/**
 * Mail
 */
class Mail
{

    /**
     * Send a message
     * 
     * @param string $to Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     * 
     * @return mixed
     */
    public static function send($to, $subject, $text, $html)
    {

        $mail = new PHPMailer(true);

        try {

            // Server Settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = Config::smtpAdminClient;
            $mail->SMTPAuth = true;
            $mail->Username = Config::smtpAdminEMail;
            $mail->Password = Config::smtpAdminEMailPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom(Config::smtpAdminEMail);
            $mail->addAddress($to); //Add a recipient
            // $mail->addAddress('ellen@example.com', 'Name'); //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz'); //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //Optional name

            // Content
            // $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject; // 'Here is the subject'
            $mail->Body = $text; //  'This is the HTML message body <b>in bold!</b>'
            // $mail->AltBody = $text; // 'This is the body in plain text for non-HTML mail clients'

            $mail->send();

            echo "Email sent - OK";

        } catch (\Throwable $th) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


    }
}