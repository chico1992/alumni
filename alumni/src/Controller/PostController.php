<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\DTO\PostSearch;
use App\Service\MessageSender;
use App\Form\PostEditFormType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


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
            return $this->redirectToRoute('homepage');
        }
        return $this->render(
            'Default\post.html.twig',
            [
                'post_form' => $form->createView(), 
                'user' => $user      
            ]
        );
    }
  
    public function getPosts($creationDate)
    {
        $time = new \DateTime();
        $time->setTimestamp(intval($creationDate)); // change string to int
        $posts = $this->getDoctrine()
            ->getManager()
            ->getRepository(Post::class);
        
        $postSearch = new PostSearch();

        $postSearch->creationDate=$time;
        $postSearch->user=null;
        $postSearch->groups=$this->getUser()->getVisibilityGroups();
        $postSearch->status=true;
        
        $postList = $posts->findByDate($postSearch);
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $postList,
            'json', 
            array(
                'groups' => array('posts')
            )
        );

        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }

    public function getUserPosts(User $user, $creationDate)
    {
        $time = new \DateTime();
        $time->setTimestamp(intval($creationDate)); // change string to int
        $posts = $this->getDoctrine()
            ->getManager()
            ->getRepository(Post::class);
        
        $postSearch = new PostSearch();

        $postSearch->creationDate=$time;
        $postSearch->user=$user->getId();
        $postSearch->groups=$this->getUser()->getVisibilityGroups();
        $postSearch->status=true;
        
        $postList = $posts->findByDate($postSearch);
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $postList,
            'json', 
            array(
                'groups' => array('posts')
            )
        );

        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }


    public function listPosts(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $posts = $manager->getRepository(Post::class)->findAll();
    
        return $this->render(
            'Postlist/test.html.twig',
            [
                'posts' =>  $posts,
            ]
        );
    }

    public function flagPost(Request $request, Post $post)
    {
        $manager = $this->getDoctrine()->getManager();
        $post->setFlag(1);
        
        $manager->persist($post);
        $manager->flush();

        $serializer = $this->getSerializer();

        return new JsonResponse(
            $serializer->serialize("The post was successfully flagged", 'json'),
            200,
            [],
            true
        );  
    }

    public function getSerializer() : SerializerInterface
    {
        return $this->get('serializer');
    }

    public function deletePost(Request $request, Post $post, AuthorizationCheckerInterface $authChecker)
    {
        $user = $this->getUser();
        $deletionError = false;

        $flag = $post->getFlag();

        
        if($user == $post->getAuthor() || true === $authChecker->isGranted('ROLE_ADMIN'))
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($post);
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

    public function editPost(Post $post, Request $request, AuthorizationCheckerInterface $authChecker)
    {
        $editForm = $this->createForm(PostEditFormType::class, $post, ['standalone'=>true]);
        $editForm->handleRequest($request);

        $editError = false;
        $user = $this->getUser();

        $flag = $post->getFlag();
        
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
            if($user == $post->getAuthor() || true === $authChecker->isGranted('ROLE_ADMIN'))
            {
                if (true === $authChecker->isGranted('ROLE_ADMIN')){
                    $post->setFlag(0);
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
            'Default/editPost.html.twig',
            [
                'post'=>$post,
                'editError'=>$editError,
                'edit_form'=>$editForm->createView()
            ]
        );
    }

    public function showUnpublishedPosts()
    {   
        $user = $this->getUser();
        $userid = $user->getId();
        $manager = $this->getDoctrine()->getManager();
        
        $criteria = ['author' => $userid, 'status' => 0];
        $orderBy = ['creationDate' => 'DESC'];

        $posts = $manager->getRepository(Post::class)->findBy($criteria, $orderBy);
        
        return $this->render(
            'Default/unpublished.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}