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

            error_log("Login attempt with email: $email");

            $result = $user->login($email, $password);

            if ($result['success']) {
                error_log($result['message']);
                $_SESSION['user_id'] = $user->getId();
                error_log("User ID stored in session: " . $_SESSION['user_id']);
                header("Location: /dashboard");
                exit();
            } else if ($result['message'] == 'Votre compte n\'est pas activé.') {
                error_log($result['message']);
                echo $result['message'] . "<br>";
                $escapedEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
                echo "Recevoir un mail d'activation <a href='/request-activate-account?email=$escapedEmail'>Activer le compte</a>";
            } else {
                error_log($result['message']);
                echo $result['message'];
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
            $email = $_POST["email"];

            if ($user->exists($email)) {
                echo "Cette adresse e-mail est déjà utilisée.";
                return;
            }

            $user->setFirstname($_POST["firstname"]);
            $user->setLastname($_POST["lastname"]);
            $user->setPassword($_POST["password"]);
            $user->setEmail($email);
            $user->setRole(User::ROLE_ADMIN);
            $token = bin2hex(random_bytes(32));
            $user->setToken($email, $token);
            $user->save();

            // Charger la configuration
            $config = require __DIR__ . '/../Config/config.php';
            // Créer une instance de Mailer avec la configuration
            $mailer = new Mailer($config);
            // Définir les informations de l'e-mail
            $from = ['email' => 'admin@rebellab.tech', 'Rebellab' => 'Mailer'];
            $to = ['email' => $email, 'name' => $_POST["firstname"] . ' ' . $_POST["lastname"]];
            $subject = 'Confirmation de votre inscription';
            $confirmLink = 'http://localhost/confirm-email?token=' . $token;
            $body = "Cliquez sur ce lien pour confirmer votre inscription: <a href='$confirmLink'>Confirmer l'inscription</a>";
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


    public function requestPasswordReset(): void
    {
        $form = new Form("PasswordResetRequest");

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $email = $_POST['email'];

            if ($user->exists($email)) {
                // Generate a unique reset token
                $resetToken = bin2hex(random_bytes(32));
                $user->setToken($email, $resetToken);

                // Charger la configuration
                $config = require __DIR__ . '/../Config/config.php';
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

    public function confirmAccount(): void
    {
        $user = new User();
        $token = $_GET['token'];

        if ($user->confirmAccount($token)) {
            echo "Account has been confirmed successfully. <a href='/login'>Login</a>";
        } else {
            echo "Invalid token or the token has expired.";
        }
    }

    public function requestActivateAccount(): void
    {
        $user = new User();
        $email = $_GET['email'];

        if ($user->exists($email)) {
            // Charger la configuration
            $config = require __DIR__ . '/../Config/config.php';
            // Créer une instance de Mailer avec la configuration
            $mailer = new Mailer($config);
            // Définir les informations de l'e-mail
            $from = ['email' => 'no-reply@rebellab.tech', 'name' => 'Rebellab'];
            $to = ['email' => $email, 'name' => $user->getFullName($email)];
            $subject = 'Confirmation de votre inscription';
            $activateLink = 'http://localhost/activate-account?email=' . $email;
            $body = "Cliquez sur ce lien pour activer votre compte: <a href='$activateLink'>Activer mon compte</a>";
            $altBody = "Cliquez sur ce lien pour activer votre compte: $activateLink";

            // Envoyer l'e-mail
            $mailer->sendMail($from, $to, $subject, $body, $altBody);

            echo "Un e-mail d'activation a été envoyé à votre adresse e-mail : $email <br>";
        } else {
            echo "Aucun utilisateur n'a été trouvé avec cette adresse e-mail.<br>";
        }
    }

    public function activateAccount(): void
    {
        $user = new User();
        $email = $_GET['email'];

        if ($user->activateAccount($email)) {
            echo "Account has been activated successfully. <a href='/login'>Login</a>";
        } else {
            echo "Invalid token or the token has expired.";
        }
    }


}
