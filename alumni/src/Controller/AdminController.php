<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\MailSender;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Invitation;
use App\Form\InvitationFormType;
use App\Entity\VisibilityGroup;
use App\Form\GroupFormType;


class AdminController extends Controller
{

    public function admin()
    {
        return $this->render('Admin/admin.html.twig');
    }


    public function listGroups(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $group = new VisibilityGroup();
        $form = $this->createForm(GroupFormType::class, $group, ['standalone'=>true]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($group);
            $manager->flush();
            
            return $this->redirectToRoute('group_list');
        }

        $groups = $manager->getRepository(VisibilityGroup::class)->findAll();

        return $this->render(
            'admin/groupCreation.html.twig',
            [
                'groups' =>  $groups,
                'form' => $form->createView()
            ]
        );
    }

    
    public function invitation(Request $request, MailSender $sender)
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