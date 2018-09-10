<?php
namespace vendor;

use config\Config;

class Mail
{
    private $_subject;
    private $_from;
    private $_headers;

    public function __construct() {
        $this->_subject = Config::getName();
        $this->_from = Config::getEmail();
        $this->_headers = "MIME-Version: 1.0" . "\r\n";
        $this->_headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
        $this->_headers .= "From: " . $this->_from . "\r\n" . "Reply-To: " . $this->_from . "\r\n" . "X-Mailer: PHP/" . phpversion();
    }

    public function sendRegistrationMail($to, $username, $token) {
        if(Config::getLang() == 'en') {
            $message = '<html><body>';
            $message .= 'Dear ' . $username . ',';
            $message .= '<p>thank you for your registration on our platform. You are one step ahead from opening your account.</p>';
            $message .= '<p><a href="' . Config::getURL() . '' . Config::getPath() . '/members/activate/' . $token . '">Click here to finalize your registration</a></p>';
            $message .= '<p>This email was generated automatically, please do not reply to it.</p>';
            $message .= '<p>Best regards !</p>';
            $message .= '</body></html>';

            $this->_subject .= ' - Email verification';

            mail($to, $this->_subject, $message, $this->_headers);
        } elseif(Config::getLang() == 'fr') {
            $message = '<html><body>';
            $message .= 'Cher ' . $username . ',';
            $message .= '<p>Merci de vous être inscrit sur notre plateforme. Il ne manque qu\'une dernière étape avant de pouvoir vous connecter à votre compte.</p>';
            $message .= '<p><a href="' . Config::getURL() . '' . Config::getPath() . '/members/activate/' . $token . '">Cliquez ici pour finaliser votre inscription</a></p>';
            $message .= '<p>Cet email a été généré automatiquement, merci de ne pas y répondre.</p>';
            $message .= '<p>Cordialement.</p>';
            $message .= '</body></html>';

            $this->_subject .= ' - Vérification email';

            mail($to, $this->_subject, $message, $this->_headers);
        }
    }

    public function sendPasswordResetMail($to, $username, $token) {
        if(Config::getLang() == 'en') {
            $message = '<html><body>';
            $message .= 'Dear ' . $username . ',';
            $message .= '<p>you are receiving this mail because you requested a password reset for your ' . Config::getName() . ' account.</p>';
            $message .= '<p><a href="' . Config::getURL() . '' . Config::getPath() . '/members/reset-password/' . $token . '">Click here to reset your password</a></p>';
            $message .= '<p>If you did not make any request, please just ignore this mail.</p>';
            $message .= '<p>This email was generated automatically, please do not reply to it.</p>';
            $message .= '<p>Best regards !</p>';
            $message .= '</body></html>';

            $this->_subject .= ' - Password reset';

            mail($to, $this->_subject, $message, $this->_headers);
        } elseif(Config::getLang() == 'fr') {
            $message = '<html><body>';
            $message .= 'Cher ' . $username . ',';
            $message .= '<p>vous recevez ce courriel parce que vous avez demandé à ce que votre mot de passe soit mis à jour pour votre compte ' . Config::getName() . '.</p>';
            $message .= '<p><a href="' . Config::getURL() . '' . Config::getPath() . '/members/reset-password/' . $token . '">Cliquez ici pour changer votre mot de passe</a></p>';
            $message .= '<p>Si vous n\'êtes pas à l\'origine de cette demande, veuillez ignorer ce courriel.</p>';
            $message .= '<p>Ce courriel a été généré automatiquement, merci de ne pas y répondre.</p>';
            $message .= '<p>Cordialement.</p>';
            $message .= '</body></html>';

            $this->_subject .= ' - Changement de mot de passe';

            mail($to, $this->_subject, $message, $this->_headers);
        }
    }
}