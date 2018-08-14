<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\MailSender;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Invitation;
use App\Form\InvitationFormType;


class AdminController extends Controller
{
    public function invitation(Request $request, MailSender $mailSender)
    {
        $invitation = new Invitation();
        $form = $this->createForm(
            InvitationFormType::class,
            $invitation,
            ['standalone' => true]
        );

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // insert data in database
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($invitation);
            $manager->flush();

            $sender->sendInvitation($invitation);

            // redirect to user list GET
            return $this->redirectToRoute('invitation');
        }

        return $this->render(
            'admin\invitation.html.twig',
            [
                'invitation_form' => $form->createView(),       
            ]
        );
    }
}