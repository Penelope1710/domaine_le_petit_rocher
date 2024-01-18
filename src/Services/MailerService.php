<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService {
    public function __construct(private readonly MailerInterface $mailer){}
    public function send (
        string $to,
        string $subject,
        string $templateTwig,
        array $context): void
    {
        $email = (new TemplatedEmail())
            ->from('fabien@example.com')
            ->to(new Address('ryan@example.com'))
            ->subject('Thanks for signing up!');
    }


}
