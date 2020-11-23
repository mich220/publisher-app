<?php

namespace App\UI\Command;

use App\Application\Service\PostCommandService;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostTitle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Application\Command\Post\CreatePostCommand as CreatePost;

class CreatePostCommand extends Command
{
    protected static $defaultName = "app:posts:create";
    private PostCommandService $postCommandService;

    public function __construct(PostCommandService $postCommandService)
    {
        $this->postCommandService = $postCommandService;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setDescription('Create post')
            ->setDefinition([
                new InputArgument('title', InputArgument::REQUIRED, 'Post title'),
                new InputArgument('content', InputArgument::REQUIRED, 'Post content'),
                new InputArgument('count', InputArgument::OPTIONAL, 'Number of posts to create', 1),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postTitle = $input->getArgument('title');
        $postContent = $input->getArgument('content');
        $count = $input->getArgument('count');

        for ($i = 0; $i < $count; $i++) {
            $this->postCommandService->sendCreatePostCommand(
                new PostTitle($postTitle),
                new PostContent($postContent)
            );
        }

       return self::SUCCESS;
    }
}