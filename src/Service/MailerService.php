<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }
    public function sendEmail(
        $to ='mahdizamni83@gmail.com',
        $content='<p>See Twig integration for better HTML integration!</p>',
        $subject='Confirmation '
    ): void
    {
        $email = (new Email())
            ->from('zamnirihab42@gmail.com')
            ->to($to)
           
            ->subject($subject)
            ->text('Sending emails is fun again!')
            ->html($content);

        try {
            $this->mailer->send($email);
        } catch ( \Exception $e) {
            
            dump('Erreur lors de l\'envoi du mail : ' . $e->getMessage());
           
        }
    }



}