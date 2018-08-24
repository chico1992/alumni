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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CommentController extends Controller
{    
    public function Comment(Request $request, Post $post)
    {   
        $comment = new Comment();
        $content = $request->request->get('content');
        $serializer = $this->getSerializer();
        if($content)
        {
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $comment->setContent($content);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();
            $data = $serializer->serialize(
                $comment,
                'json', 
                array(
                    'groups' => array('comment')
                )
            );
            
            return new JsonResponse(
                $data,
                200,
                [],
                true
            );
        }
        else
        {
            return new JsonResponse(
                $serializer->serialize("The content of your request was not good", 'json'),
                400,
                [],
                true
            );
        }
    }

    public function getComments(Post $post)
    {
        $comments = $post->getComments();
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $comments,
            'json', 
            array(
                'groups' => array('comment')
            )
        );
        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }


    public function deleteComment(Request $request, Comment $comment, AuthorizationCheckerInterface $authChecker)
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

    public function flagComment(Request $request, Comment $comment)
    {
        $manager = $this->getDoctrine()->getManager();
        $comment->setFlag(1);
        
        $manager->persist($comment);
        $manager->flush();

        $serializer = $this->getSerializer();

        return new JsonResponse(
            $serializer->serialize("The comment was successfully flagged", 'json'),
            200,
            [],
            true
        );  
    }
    public function getSerializer() : SerializerInterface
    {
        return $this->get('serializer');
    }
    
}