<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class MailService {

    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $from, string $subject, string $htmlTemplate, array $context, string $to = 'admin@cook.fr' ):void
    {
        $email = (new TemplatedEmail())
        ->from($from)
        ->to('admin@cook.fr')
        ->priority(TemplatedEmail::PRIORITY_HIGH)
        ->subject($subject)
        ->htmlTemplate($htmlTemplate)
        // pass variables (name => value) to the template
       ->context($context);

    $this->mailer->send($email);
    }
}