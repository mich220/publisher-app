<?php

namespace App\UI\Command;

use App\Application\Service\PostCommandService;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\PostTitle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Command\Post\CreatePostCommand as CreatePost;

class UpdatePostCommand extends Command
{
    protected static $defaultName = "app:posts:update";
    private PostCommandService $postCommandService;

    public function __construct(PostCommandService $postCommandService)
    {
        $this->postCommandService = $postCommandService;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setDescription('Create comment')
            ->setDefinition([
                new InputArgument('postId', InputArgument::REQUIRED, 'Post ID'),
                new InputArgument('postTitle', InputArgument::REQUIRED, 'Post title'),
                new InputArgument('postContent', InputArgument::REQUIRED, 'Post content'),
                new InputArgument('accessKey', InputArgument::REQUIRED, 'Access key'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postId = $input->getArgument('postId');
        $postTitle = $input->getArgument('postTitle');
        $postContent = $input->getArgument('postContent');
        $accessKey = $input->getArgument('accessKey');

        $this->postCommandService->sendUpdatePostCommand(
            new PostId($postId),
            new PostTitle($postTitle),
            new PostContent($postContent),
            $accessKey
        );

       return self::SUCCESS;
    }
}