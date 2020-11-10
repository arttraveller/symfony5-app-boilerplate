<?php

namespace App\Web\Frontend\Controllers;

use App\Core\Commands\Posts\CreatePostCommand;
use App\Core\Commands\Posts\CreatePostHandler;
use App\Web\Frontend\Forms\Posts\CreatePostForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends FrontendController
{
    /**
     * @Route("/posts/create", name="posts_create")
     * @return Response
     */
    public function create(Request $request, CreatePostHandler $handler): Response
    {
        $command = new CreatePostCommand();
        $form = $this->createForm(CreatePostForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $handler->handle($command);

            return $this->redirectToRoute('posts_index');
        }

        return $this->render('frontend/posts/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/posts", name="posts_index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('frontend/posts/index.html.twig', []);
    }
}