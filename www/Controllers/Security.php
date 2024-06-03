<?php

namespace App\Controller;

use Mailer;
use App\Core\Form;
use App\Core\View;
use App\Models\User;
use App\Core\Security as Auth;

class Security
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(): void
    {
        $user = new User();
        $security = new Auth();

        if ($security->isLogged()) {
            echo "Vous êtes déjà connecté";
            return;
        }

        $form = new Form("Login");

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Debugging statement to check login data
            error_log("Login attempt with email: $email");

            if ($user->login($email, $password)) {
                // Debugging statement to confirm successful login
                error_log("Login successful for email: $email");
                header("Location: /dashboard");
                exit();
            } else {
                echo "Informations d'identification incorrectes";
                // Debugging statement to confirm failed login
                error_log("Login failed for email: $email");
            }
        }

        $view = new View("Security/login");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function register(): void
    {
        $form = new Form("Register");

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setFirstname($_POST["firstname"]);
            $user->setLastname($_POST["lastname"]);
            $user->setEmail($_POST["email"]);
            $user->setPassword($_POST["password"]); // Password is hashed inside the setPassword method
            $user->save();

            // Charger la configuration
            $config = require __DIR__ . '/../config/config.php';

            // Créer une instance de Mailer avec la configuration
            $mailer = new Mailer($config);

            // Définir les informations de l'e-mail
            $from = ['email' => 'admin@rebellab.tech', 'Rebellab' => 'Mailer'];
            $to = ['email' => $_POST["email"], 'name' => $_POST["firstname"] . ' ' . $_POST["lastname"]];
            $subject = 'Confirmation de votre inscription';
            $body = 'Merci de vous être inscrit sur notre site !';
            $altBody = 'Merci de vous être inscrit sur notre site !';

            // Envoyer l'e-mail
            $mailer->sendMail($from, $to, $subject, $body, $altBody);

            header("Location: /login");
            exit();
        }

        $view = new View("Security/register");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function logout(): void
    {
        unset($_SESSION['user_id']);
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function dashboard(): void
    {
        $security = new Auth();
        if (!$security->isLogged()) {
            header("Location: /login");
            exit();
        }

        $view = new View("Security/dashboard");
        $view->render();
    }

    public function requestPasswordReset(): void
    {
        $form = new Form("PasswordResetRequest");

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $email = $_POST['email'];

            if ($user->exists($email)) {
                // Generate a unique reset token
                $resetToken = bin2hex(random_bytes(16));
                $user->setResetToken($email, $resetToken);

                // Charger la configuration
                $config = require __DIR__ . '/../config/config.php';

                // Créer une instance de Mailer avec la configuration
                $mailer = new Mailer($config);

                // Définir les informations de l'e-mail
                $from = ['email' => 'no-reply@rebellab.tech', 'name' => 'Rebellab'];
                $to = ['email' => $email, 'name' => $user->getFullName($email)];
                $subject = 'Password reset request';
                $resetLink = 'http://localhost/reset-password?token=' . $resetToken;
                $body = "Cliquez sur ce lien pour réinitialiser votre mot de passe: <a href='$resetLink'>Réinitialiser le mot de passe</a>";
                $altBody = "Cliquez sur ce lien pour réinitialiser votre mot de passe: $resetLink";

                // Envoyer l'e-mail
                $mailer->sendMail($from, $to, $subject, $body, $altBody);

                echo "Demande de réinitialisation de mot de passe envoyée à votre adresse e-mail : $email <br>";
            } else {
                echo "Aucun utilisateur n'a été trouvé avec cette adresse e-mail.<br>";
            }
        }

        $view = new View("Security/requestPasswordReset");
        $view->assign("form", $form->build());
        $view->render();
    }

    public function resetPassword(): void
    {
        $form = new Form("PasswordReset");

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $token = $_GET['token'];
            $newPassword = $_POST['password'];

            if ($user->resetPassword($token, $newPassword)) {
                echo "Password has been reset successfully.";
                header("Location: /login");
                exit();
            } else {
                echo "Invalid token or the token has expired.";
            }
        }

        $view = new View("Security/passwordReset");
        $view->assign("form", $form->build());
        $view->render();
    }
}
