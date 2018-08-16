<?php
namespace App\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Comment;
use App\Form\CommentFormType;

class CommentController extends Controller
{    
    public function Comment(Request $request)
    {   
        $flag = false;
        $manager = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setFlag($flag);
        $form = $this->createForm(CommentFormType::class, $comment, ['standalone' => true]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {

            $manager->persist($comment);
            $manager->flush();
            
            return $this->redirectToRoute('comment');
        }
        
        
        return $this->render(
            'Default/list.html.twig',
            [
                'comments' => $manager->getRepository(Comment::class)->findAll(),
                'form' => $form->createView()
            ]
        );
    }
    
}