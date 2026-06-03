<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class EmailHelper
{
    /**
     * Send email using PHPMailer
     * 
     * @param string|array $to - Email address(es)
     * @param string $subject - Email subject
     * @param string $body - Email body (HTML or plain text)
     * @param array $options - Optional parameters
     * @return array
     */
    public static function sendSmtpEmail($to, $subject, $body, $options = [])
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug  = $options['debug'] ?? 0; // 0 = off, 2 = verbose
            $mail->isSMTP();
            $mail->Host       = "smtp.gmail.com";
            $mail->SMTPAuth   = true;
            $mail->Username   = "sales@aeritx.com";
            $mail->Password   = "nfdeiywmojmkghkc";
            $mail->SMTPSecure = "tls";
            $mail->Port       = 587;
            
            // Timeout settings
            $mail->Timeout    = $options['timeout'] ?? 30;

            // Recipients - From
            $fromAddress = $options['from_address'] ?? config('mail.from.address');
            $fromName    = $options['from_name'] ?? config('mail.from.name');
            $mail->setFrom($fromAddress, $fromName);

            // Recipients - To
            if (is_array($to)) {
                foreach ($to as $email => $name) {
                    if (is_numeric($email)) {
                        $mail->addAddress($name);
                    } else {
                        $mail->addAddress($email, $name);
                    }
                }
            } else {
                $mail->addAddress($to);
            }

            // Recipients - CC
            if (!empty($options['cc'])) {
                if (is_array($options['cc'])) {
                    foreach ($options['cc'] as $email => $name) {
                        if (is_numeric($email)) {
                            $mail->addCC($name);
                        } else {
                            $mail->addCC($email, $name);
                        }
                    }
                } else {
                    $mail->addCC($options['cc']);
                }
            }

            // Recipients - BCC
            if (!empty($options['bcc'])) {
                if (is_array($options['bcc'])) {
                    foreach ($options['bcc'] as $email => $name) {
                        if (is_numeric($email)) {
                            $mail->addBCC($name);
                        } else {
                            $mail->addBCC($email, $name);
                        }
                    }
                } else {
                    $mail->addBCC($options['bcc']);
                }
            }

            // Reply To
            if (!empty($options['reply_to'])) {
                $replyEmail = is_array($options['reply_to']) 
                    ? $options['reply_to']['email'] 
                    : $options['reply_to'];
                $replyName = is_array($options['reply_to']) 
                    ? ($options['reply_to']['name'] ?? '') 
                    : '';
                $mail->addReplyTo($replyEmail, $replyName);
            }

            // Attachments
            if (!empty($options['attachments'])) {
                foreach ($options['attachments'] as $attachment) {
                    if (is_array($attachment)) {
                        $mail->addAttachment(
                            $attachment['path'], 
                            $attachment['name'] ?? ''
                        );
                    } else {
                        $mail->addAttachment($attachment);
                    }
                }
            }

            // Content
            $isHtml = $options['is_html'] ?? true;
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            
            if ($isHtml) {
                $mail->AltBody = $options['alt_body'] ?? strip_tags($body);
            }

            // Character set
            $mail->CharSet = $options['charset'] ?? 'UTF-8';

            // Priority
            if (!empty($options['priority'])) {
                $mail->Priority = $options['priority']; // 1 = High, 3 = Normal, 5 = Low
            }

            // Send email
            $mail->send();
            
            Log::info('Email sent successfully', [
                'to' => $to,
                'subject' => $subject
            ]);

            return [
                'success' => true,
                'message' => 'Email sent successfully'
            ];

        } catch (Exception $e) {
            Log::error('PHPMailer Error', [
                'error' => $e->getMessage(),
                'to' => $to,
                'subject' => $subject
            ]);

            return [
                'success' => false,
                'message' => "Email could not be sent. Error: {$mail->ErrorInfo}",
                'error' => $e->getMessage()
            ];
        }
    }
}