<?php
/**
 * HopeBridge Mail Helper (Version Gmail Pro avec PHPMailer)
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Chemins vers les fichiers PHPMailer (téléchargés automatiquement)
require 'includes/PHPMailer/Exception.php';
require 'includes/PHPMailer/PHPMailer.php';
require 'includes/PHPMailer/SMTP.php';

function sendHopeMail($to, $subject, $message_body) {
    $mail = new PHPMailer(true);

    try {
        // --- CONFIGURATION GMAIL ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // IMPORTANT : Votre adresse Gmail réelle
        $mail->Username   = 'ac7786539@gmail.com'; 
        
        // Le mot de passe d'application que vous avez généré
        $mail->Password   = 'eadr ovsv sasl nmta'; 
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Solution pour l'erreur SSL sur XAMPP
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // --- DESTINATAIRE ---
        $mail->setFrom('no-reply@hopebridge.org', 'HopeBridge Charity');
        $mail->addAddress($to);

        // --- CONTENU ---
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        // Template HTML Professionnel
        $full_html = "
        <html>
        <head>
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; padding: 20px; color: #334155; }
                .container { background: #ffffff; padding: 40px; border-radius: 16px; max-width: 600px; margin: auto; border: 1px solid #e2e8f0; }
                .header { color: #0d9488; font-size: 28px; font-weight: bold; margin-bottom: 24px; text-align: center; }
                .footer { margin-top: 32px; font-size: 12px; color: #94a3b8; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 20px; }
                .content { line-height: 1.6; }
                .qr-section { text-align: center; margin-top: 32px; background: #f8fafc; padding: 20px; border-radius: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>HopeBridge</div>
                <div class='content'>
                    $message_body
                    <div class='qr-section'>
                        <p style='font-size: 14px; color: #64748b; margin-bottom: 12px;'>Scannez pour accéder à notre portail de dons :</p>
                        <img src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://localhost/projet_/frontend/donations.php' alt='QR Code'>
                    </div>
                </div>
                <div class='footer'>
                    &copy; 2026 HopeBridge - Une initiative pour un monde sans sans-abris.
                </div>
            </div>
        </body>
        </html>";

        $mail->Body = $full_html;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log de l'erreur si besoin
        error_log("Email non envoyé. Erreur : {$mail->ErrorInfo}");
        return false;
    }
}
?>
