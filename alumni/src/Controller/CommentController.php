<?php
namespace App\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Entity\Post;
use Doctrine\ORM\Mapping\Id;
use App\Form\CommentEditFormType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CommentController extends Controller
{    
    public function Comment(Request $request, Post $post)
    {   
        $flag = false;
        $manager = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setFlag($flag);
        $comment->setPost($post);
        $form = $this->createForm(CommentFormType::class, $comment, ['standalone' => true]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {

            $manager->persist($comment);
            $manager->flush();
            
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render(
            'Default/comment.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    public function deleteComment(Request $request, Comment $comment)
    {
        $user = $this->getUser();
        $deletionError = false;

        $flag = $comment->getFlag();
        
        if($user == $comment->getAuthor() || true === $authChecker->isGranted('ROLE_ADMIN'))
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($comment);
            $manager->flush();

            if (true === $authChecker->isGranted('ROLE_ADMIN') && $flag == 1){
                return $this->redirectToRoute('flags');
            } else {
                return $this->redirectToRoute('homepage');
            }
        } 
        else
        {
            $deletionError = true;  
        }
    }

    public function editComment(Comment $comment, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $editForm = $this->createForm(CommentEditFormType::class, $comment, ['standalone'=>true]);
        $editForm->handleRequest($request);

        $editError = false;
        $user = $this->getUser();

        $flag = $comment->getFlag();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            if($user == $comment->getAuthor() || true === $authChecker->isGranted('ROLE_ADMIN'))
            {
                if (true === $authChecker->isGranted('ROLE_ADMIN')){
                    $comment->setFlag(0);
                }

                $this->getDoctrine()->getManager()->flush();
            
                if (true === $authChecker->isGranted('ROLE_ADMIN') && $flag == 1){
                    return $this->redirectToRoute('flags');
                } else {
                    return $this->redirectToRoute('homepage');
                }
            }
            else
            {
                $editError = true;
            }
        }
        
        return $this->render(
            'Default/editComment.html.twig',
            [
                'comment'=>$comment,
                'editError'=>$editError,
                'edit_form'=>$editForm->createView()
            ]
        );
    }
    
}