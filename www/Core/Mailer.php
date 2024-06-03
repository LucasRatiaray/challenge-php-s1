<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class Mailer
{
    private $mail;

    public function __construct($config)
    {
        $this->mail = new PHPMailer(true);

        // Configure PHPMailer avec les paramètres SMTP
        $this->mail->isSMTP();
        $this->mail->Host = $config['smtp']['host'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $config['smtp']['username'];
        $this->mail->Password = $config['smtp']['password'];
        $this->mail->SMTPSecure = $config['smtp']['encryption'];
        $this->mail->Port = $config['smtp']['port'];
    }

    public function sendMail($from, $to, $subject, $body, $altBody = '')
    {
        try {
            $this->mail->setFrom($from['email'], $from['name']);
            $this->mail->addAddress($to['email'], $to['name']);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = $altBody;

            $this->mail->send();
            echo 'Mail envoyé avec succès<br>';
        } catch (Exception $e) {
            echo "Impossible d'envoyer le mail. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function sendPasswordReset($to, $resetLink)
    {
        $from = ['email' => 'no-reply@rebellab.tech', 'name' => 'Rebellab'];
        $subject = 'Réinitialisation de votre mot de passe';
        $body = "Cliquer sur le lien suivant pour réinitialiser votre mot de passe: <a href='$resetLink'>$resetLink</a>";
        $altBody = "Cliquer sur le lien suivant pour réinitialiser votre mot de passe: $resetLink";

        $this->sendMail($from, $to, $subject, $body, $altBody);
    }
}
