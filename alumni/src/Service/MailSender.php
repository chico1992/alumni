<?php

namespace App\Service;

use App\Entity\Invitation;


class MailSender
{
    private $twig;
    private $mailer;

    public function __construct(
        \Twig_Environment $twig,
        \Swift_Mailer $mailer
    )
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendInvitation(Invitation $invitation)
    {
        $message = (new \Swift_Message('Registration Mail'))
            ->setFrom('cedric.correia.alves@gmail.com')
            ->setTo($invitation->getEmail())
            ->setBody(
                $this->twig->render(
                    'Email/invitation.html.twig',
                    array('invitation' => $invitation)
                ),
                'text/html'
            )
            ->addPart(
                $this->twig->render(
                    'Email/invitation.txt.twig',
                    array('invitation' => $invitation)
                ),
                'text/plain'
            )
        ;

        $this->mailer->send($message);
    }
}
