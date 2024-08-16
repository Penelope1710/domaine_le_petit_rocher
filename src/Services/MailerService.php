<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService {
    public function __construct(
        private string $from,
        private readonly MailerInterface $mailer,
        ){}
    public function send (
        string $to,
        string $subject,
        string $templateTwig,
        array $context): void
    {
        $email = (new TemplatedEmail())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($templateTwig)
            ->context($context);

        $this->mailer->send($email);
    }


}
