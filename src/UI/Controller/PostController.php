<?php

namespace App\UI\Controller;

use App\Application\Proxy\FetchPostByIdQueryProxy;
use App\Application\Service\PostCommandService;
use App\Application\Service\Validator;
use App\Domain\Query\FetchPostsQuery;
use App\Domain\Specification\AndSpecification;
use App\Domain\Specification\FieldsNotEmpty;
use App\Domain\Specification\HasFields;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\PostTitle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class PostController extends AbstractController
{
    private PostCommandService $commandService;
    private Validator $validator;

    use AcceptsRequest;

    public function __construct(PostCommandService $postCommandService, Validator $validator)
    {
        $this->commandService = $postCommandService;
        $this->validator = $validator;
    }

    /**
     * @Route("/posts", name="posts.index", methods={"GET"})
     */
    public function index(FetchPostsQuery $query, Request $request): JsonResponse
    {
        $page = $request->query->filter('page', 0, FILTER_VALIDATE_INT);
        $limit = $request->query->filter('limit', 10, FILTER_VALIDATE_INT);

        // @todo add proxy for query
        return $this->json($query
            ->getPaginated($page, $limit)
            ->getData()
        );
    }

    /**
     * @Route("/posts/{id}", name="posts.find", methods={"GET"})
     */
    public function find(FetchPostByIdQueryProxy $query, string $id)
    {

        return $this->json($query
            ->get(new PostId($id))
            ->first()
        );
    }

    /**
     * @Route("/posts", name="posts.create", methods={"POST"})
     */
    public function storePost(Request $request): JsonResponse
    {
        $this->validator->addSpecification(new AndSpecification(
            new HasFields(['postTitle', 'postContent']),
            new FieldsNotEmpty(['postTitle', 'postContent']),
        ));
        $this->validator->validate($request);

        $bag = $this->commandService->sendCreatePostCommand(
            new PostTitle($request->get('postTitle')),
            new PostContent($request->get('postContent')),
        );

        return $this->acceptRequest($bag);
    }

    /**
     * @Route("/posts/{postId}", name="posts.update", methods={"POST"})
     */
    public function updatePost(Request $request, string $postId): JsonResponse
    {
        $this->validator->addSpecification(new AndSpecification(
            new HasFields(['postTitle', 'postContent', 'accessKey']),
            new FieldsNotEmpty(['postTitle', 'postContent', 'accessKey']),
        ));
        $this->validator->validate($request);

        $bag = $this->commandService->sendUpdatePostCommand(
             new PostId($postId),
             new PostTitle($request->get('postTitle')),
             new PostContent($request->get('postContent')),
             $request->get('accessKey') ?? ''
         );

        return $this->acceptRequest($bag);
    }

    /**
     * @Route("/posts/{postId}", name="posts.delete", methods={"DELETE"})
     */
    public function deletePost(string $postId, Request $request): JsonResponse
    {
        $this->validator->addSpecification(new AndSpecification(
            new HasFields(['accessKey']),
            new FieldsNotEmpty(['accessKey']),
        ));
        $this->validator->validate($request);

        $bag = $this->commandService->sendDeletePostCommand(
            new PostId($postId),
            $request->get('accessKey')
        );

        return $this->acceptRequest($bag);
    }
}
