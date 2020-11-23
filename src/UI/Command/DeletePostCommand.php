<?php

namespace App\UI\Command;

use App\Application\Service\PostCommandService;
use App\Domain\ValueObject\PostId;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Application\Command\Post\DeletePostCommand as DeletePost;

class DeletePostCommand extends Command
{
    protected static $defaultName = "app:posts:delete";

    private PostCommandService $commandService;

    public function __construct(PostCommandService $postCommandService)
    {
        $this->commandService = $postCommandService;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setDescription('Create comment')
            ->setDefinition([
                new InputArgument('postId', InputArgument::REQUIRED, 'Post ID'),
                new InputArgument('accessKey', InputArgument::REQUIRED, 'Access key'),
            ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postId = $input->getArgument('postId');
        $accessKey = $input->getArgument('accessKey');
        $this->commandService->sendDeletePostCommand(new PostId($postId), $accessKey);

        return self::SUCCESS;
    }
}