<?php
namespace App\Controller;

use App\Entity\VisibiltyGroup;
use App\Form\GroupFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AdminController extends Controller
{
    public function listGroups(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $group = new VisibiltyGroup();
        $form = $this->createForm(GroupFormType::class, $group, ['standalone'=>true]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();
            
            return $this->redirectToRoute('group_list');
        }

        $groups = $manager->getRepository(VisibiltyGroup::class)->findAll();

        return $this->render(
            'groupCreation.html.twig',
            [
                'groups' =>  $groups,
                'form' => $form->createView()
            ]
        );
    }
}