<?php

namespace App\UI\Command;

use App\Application\Service\CommentCommandService;
use App\Domain\ValueObject\CommentContent;
use App\Domain\ValueObject\PostId;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommentCommand extends Command
{
    protected static $defaultName = "app:comments:create";
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
                new InputArgument('content', InputArgument::REQUIRED, 'Post content'),
                new InputArgument('count', InputArgument::OPTIONAL, 'Number of comments to create', 1),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postId = $input->getArgument('postId');
        $commentContent = $input->getArgument('postId') ?? 'not specified';
        $count = $input->getArgument('count');

        for ($i = 0; $i < $count; $i++) {
            $this->commandService->sendCreateCommentCommand(
                new PostId($postId),
                new CommentContent($commentContent),
            );
        }

        return self::SUCCESS;
    }
}