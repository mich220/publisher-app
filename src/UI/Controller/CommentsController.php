<?php

namespace App\UI\Controller;

use App\Application\Service\CommentCommandService;
use App\Application\Service\Validator;
use App\Domain\Collections\ParameterBag;
use App\Domain\Query\FetchCommentByIdQuery;
use App\Domain\Query\FetchPostCommentsQuery;
use App\Domain\Specification\AndSpecification;
use App\Domain\Specification\FieldsNotEmpty;
use App\Domain\Specification\HasFields;
use App\Domain\ValueObject\CommentContent;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class CommentsController extends AbstractController
{
    private CommentCommandService $commandService;
    private Validator $validator;

    use AcceptsRequest;

    public function __construct(CommentCommandService $commentCommandService, Validator $validator)
    {
        $this->commandService = $commentCommandService;
        $this->validator = $validator;
    }

    /**
     * @Route("/posts/{postId}/comments", name="comments.index", methods={"GET"})
     */
    public function index(string $postId, FetchPostCommentsQuery $query): JsonResponse
    {
        $postId = new PostId($postId);
        return $this->json($query
            ->get($postId)
            ->getData()
        );
    }

    /**
     * @Route("/posts/{postId}/comments/{commentId}", name="comments.find", methods={"GET"})
     */
    public function find(string $postId, string $commentId, FetchCommentByIdQuery $query)
    {
        return $this->json($query
            ->get(new CommentId($commentId), new PostId($postId))
            ->toArray()
        );
    }

    /**
     * @Route("/posts/{postId}/comments", name="comments.create", methods={"POST"})
     */
    public function storeComment(string $postId, Request $request): JsonResponse
    {
        $this->validator->addSpecification(new AndSpecification(
            new HasFields(['commentContent']),
            new FieldsNotEmpty(['commentContent']),
        ));
        $this->validator->validate($request);

        $postId = new PostId($postId);
        $commentContent = new CommentContent($request->get('commentContent'));

        $bag = $this->commandService->sendCreateCommentCommand($postId, $commentContent);


        return $this->acceptRequest($bag);
    }

    /**
     * @Route("/posts/{postId}/comments/{commentId}", name="comments.update", methods={"POST"})
     */
    public function updateComment(string $postId, string $commentId, Request $request): JsonResponse
    {
        $this->validator->addSpecification(new AndSpecification(
            new HasFields(['commentContent', 'accessKey']),
            new FieldsNotEmpty(['commentContent', 'accessKey']),
        ));

        $this->validator->validate($request);

        $bag = $this->commandService->sendUpdateCommentCommand(
            new PostId($postId),
            new CommentId($commentId),
            new CommentContent($request->get('commentContent')),
            $request->get('accessKey') ?? ''
        );

        return $this->acceptRequest($bag);
    }

    /**
     * @Route("/posts/{postId}/comments/{commentId}", name="comments.delete", methods={"DELETE"})
     */
    public function deleteComment(string $postId, string $commentId, Request $request): JsonResponse
    {
        $this->validator->addSpecification(new AndSpecification(
            new HasFields(['accessKey']),
            new FieldsNotEmpty(['accessKey']),
        ));
        $this->validator->validate($request);

        $postId = new PostId($postId);
        $commentId = new CommentId($commentId);
        $accessKey = $request->query->get('accessKey');
        $bag = $this->commandService->sendDeleteCommentCommand($postId, $commentId, $accessKey);


        return $this->acceptRequest($bag);
    }
}
