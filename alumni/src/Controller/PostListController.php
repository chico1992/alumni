<?php
namespace App\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Comment;
use App\Entity\Post;

class PostListController extends Controller
{  

    public function postList(Request $request)
        {   
            $manager = $this->getDoctrine()->getManager();

            return $this->render(
                'Default/postList.html.twig',
                [
                    'posts' => $manager->getRepository(Post::class)->findAll(),
                    'comments' => $manager->getRepository(Comment::class)->findAll(),
                ]
            );
        }
            
}