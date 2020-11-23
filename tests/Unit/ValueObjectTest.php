<?php

namespace App\Tests;

use App\Domain\ValueObject\CommentContent;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\PostTitle;
use App\Domain\ValueObject\TaskId;
use PHPUnit\Framework\TestCase;

class ValueObjectTest extends TestCase
{
    /**
     * @todo add validation in value objects
     */

    /**
     * @test
     */
    public function comment_content_contains_string()
    {
        $commentContent = new CommentContent('content');
        $this->assertIsString($commentContent->toString());
    }

    /**
     * @test
     */
    public function comment_id_contains_string()
    {
        $commentId = new CommentId('someid');
        $this->assertIsString($commentId->toString());
    }

    /**
     * @test
     */
    public function post_content_contains_string()
    {
        $postContent = new PostContent('Hi');
        $this->assertIsString($postContent->toString());
    }

    /**
     * @test
     */
    public function post_id_contains_string()
    {
        $postId = new PostId('Hi');
        $this->assertIsString($postId->toString());
    }

    /**
     * @test
     */
    public function post_title_contains_string()
    {
        $postTitle = new PostTitle('Hi');
        $this->assertIsString($postTitle->toString());
    }

    /**
     * @test
     */
    public function task_id_contains_string()
    {
        $taskId = new TaskId('Hi');
        $this->assertIsString($taskId->toString());
    }
}