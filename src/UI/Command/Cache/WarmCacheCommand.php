<?php


namespace App\UI\Command\Cache;

use App\Application\Mapper\PostAggregateMapper;
use App\Domain\Query\CountPostsQuery;
use App\Domain\Query\FetchPostsQuery;
use App\Domain\Repository\PostAggregateRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WarmCacheCommand extends Command
{
    protected static $defaultName = "app:posts:cache:warm";

    private const PAGE_LIMIT = 5;

    private FetchPostsQuery $fetchPostsQuery;
    private CountPostsQuery $countPostsQuery;
    private PostAggregateRepository $postCacheRepository;

    public function __construct(
        FetchPostsQuery $fetchPostsQuery,
        CountPostsQuery $countPostsQuery,
        PostAggregateRepository $postCacheRepository
    )
    {
        $this->fetchPostsQuery = $fetchPostsQuery;
        $this->countPostsQuery = $countPostsQuery;
        $this->postCacheRepository = $postCacheRepository;
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $this->countPostsQuery->get();

        $lastPage = $this->getLastPage($count);

        for ($page = 0; $page < $lastPage; $page++) {
            $results = $this->fetchPostsQuery->getPaginated($page, self::PAGE_LIMIT);

            foreach ($results->getData() as $postDbResult) {
                $post = PostAggregateMapper::mapDbResultToAggregate($postDbResult);
                $this->postCacheRepository->save($post);
            }
//            $this->postCacheRepository->set();
        }

       return self::SUCCESS;
    }

    protected function getLastPage(int $count): int
    {
        if ($count < self::PAGE_LIMIT) {
            $lastPage = 0;
        } else {
            $lastPage = $count / self::PAGE_LIMIT;
        }

        return $lastPage;
    }
}
