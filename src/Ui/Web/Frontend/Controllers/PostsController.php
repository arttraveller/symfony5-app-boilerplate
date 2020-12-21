<?php

namespace App\Ui\Web\Frontend\Controllers;

use App\Domain\Commands\Posts\CreatePostCommand;
use App\Domain\Commands\Posts\CreatePostHandler;
use App\Domain\Entities\Post\Post;
use App\Domain\Repositories\Interfaces\PostsRepositoryInterface;
use App\Ui\Web\Frontend\Forms\Posts\CreatePostForm;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, PostsRepositoryInterface $postsRepo, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $postsRepo->findAllWithUser(),
            $request->query->getInt('page', 1),
            self::PER_PAGE_DEFAULT
        );

        return $this->render('frontend/posts/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    /**
     * @Route("/posts/{id}", name="posts_show")
     * @return Response
     */
    public function show(Post $post): Response
    {
        return $this->render('frontend/posts/show.html.twig', [
            'post' => $post,
        ]);
    }
}