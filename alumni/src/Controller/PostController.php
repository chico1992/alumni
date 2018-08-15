<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostFormType;


class PostController extends Controller
{
    public function createPost(Request $request){
        $post = new Post();
        $post->setAuthor($this->getUser());
        $form = $this->createForm(
            PostFormType::class,
            $post,
            [
                'standalone' => true,
                'user' => $this->getUser()
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
            ]
        );
    }
}