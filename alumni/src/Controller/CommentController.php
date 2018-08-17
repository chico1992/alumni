<?php
namespace App\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Entity\Post;

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
            
            return $this->redirectToRoute('post_list');
        }
        
        
        
        return $this->render(
            'Default/comment.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
    
}