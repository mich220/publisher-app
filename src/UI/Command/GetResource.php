<?php

namespace App\UI\Command;

use App\Application\Service\PostCommandService;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\PostTitle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Command\Post\CreatePostCommand as CreatePost;

class GetResource extends Command
{
    protected static $defaultName = "app:posts:update";
    private PostCommandService $postCommandService;

    public function __construct(PostCommandService $postCommandService)
    {
        $this->postCommandService = $postCommandService;
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @todo
        $postId = $input->getArgument('postId');
        $postTitle = $input->getArgument('title');
        $postContent = $input->getArgument('content');

        $this->postCommandService->sendUpdatePostCommand(
            new PostId($postId),
            new PostTitle($postTitle),
            new PostContent($postContent),
            ''
        );

       return self::SUCCESS;
    }
}