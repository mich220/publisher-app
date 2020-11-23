<?php

namespace App\UI\Command;

use App\Application\Service\CommentCommandService;
use App\Domain\ValueObject\CommentContent;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Command\Comment\UpdateCommentCommand as UpdateComment;

class UpdateCommentCommand extends Command
{
    protected static $defaultName = "app:comments:update";
    private CommentCommandService $commandService;

    public function __construct(CommentCommandService $commentCommandService)
    {
        $this->commandService = $commentCommandService;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setDescription('Create comment')
            ->setDefinition([
                new InputArgument('postId', InputArgument::REQUIRED, 'Post ID'),
                new InputArgument('commentId', InputArgument::REQUIRED, 'Comment ID'),
                new InputArgument('commentContent', InputArgument::REQUIRED, 'Comment content'),
                new InputArgument('accessKey', InputArgument::REQUIRED, 'Access key'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postId = $input->getArgument('postId');
        $commentId = $input->getArgument('commentId');
        $commentContent = $input->getArgument('commentContent');
        $accessKey = $input->getArgument('accessKey');

        $this->commandService->sendUpdateCommentCommand(
            new PostId($postId),
            new CommentId($commentId),
            new CommentContent($commentContent),
            $accessKey
        );

        return self::SUCCESS;
    }
}