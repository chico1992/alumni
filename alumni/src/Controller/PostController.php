<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;


class PostController extends Controller
{
    public function createPost(Request $request){
        $post = new Post();
        $user = $this->getUser();
        $post->setAuthor($user);
        $form = $this->createForm(
            PostFormType::class,
            $post,
            [
                'standalone' => true,
                'user' => $user
            ]
        );
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // insert data in database
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();
            // redirect to user list GET
            return $this->redirectToRoute('post');
        }
        return $this->render(
            'Default\post.html.twig',
            [
                'post_form' => $form->createView(), 
                'user' => $user      
            ]
        );
    }

    public function getPosts(\DateTime $creationDate)
    {
        // $time = new \DateTime();
        // $time->setTimestamp($creationDate);
        $posts = $this->getDoctrine()
            ->getManager()
            ->getRepository(Post::class);
        
        $postList = $posts->findByDate($creationDate);
        $serializer = $this->getSerializer();

        return new JsonResponse(
            $serializer->serialize($postList,'json'),
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