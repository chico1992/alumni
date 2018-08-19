<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\MailSender;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Invitation;
use App\Form\InvitationFormType;
use App\Entity\VisibilityGroup;
use App\Form\GroupFormType;
use App\Entity\Post;
use App\Entity\Comment;
use App\Form\PostEditFormType;
use App\Form\CommentEditFormType;


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
            'Admin\invitation.html.twig',
            [
                'invitation_form' => $form->createView(),       
            ]
        );
    }

    public function listFlags()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $posts = $manager->getRepository(Post::class)->findBy(['flag' => 1]);
        $comments = $manager->getRepository(Comment::class)->findBy(['flag' => 1]);

        return $this->render(
            'Admin/flags.html.twig',
            [
                'posts' =>  $posts,
                'comments' =>  $comments,
            ]
        );
    }

    public function editPost(Post $post, Request $request)
    {
        $editForm = $this->createForm(PostEditFormType::class, $post, ['standalone'=>true]);
        $editForm->handleRequest($request);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {

           //add if user = admin
            $post->setFlag(0);
            
            
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('flags');
        }
        
        return $this->render(
            'admin/editPost.html.twig',
            [
                'post'=>$post,
                'edit_form'=>$editForm->createView()
            ]
        );
    }

    public function editComment(Comment $comment, Request $request)
    {
        $editForm = $this->createForm(CommentEditFormType::class, $comment, ['standalone'=>true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            //add if user = admin
            $comment->setFlag(0);

            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('flags');
        }
        
        return $this->render(
            'admin/editComment.html.twig',
            [
                'comment'=>$comment,
                'edit_form'=>$editForm->createView()
            ]
        );
    }

}