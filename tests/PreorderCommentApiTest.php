<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PreorderCommentApiTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function it_can_create_comment()
    {
        $preorder_id = 15;
        $content = '不错';
        $this->json('post', 'subscribe/preorders/comments',
            [
                'score' => 5,
                'content' => $content,
                'commentable_id' => $preorder_id
            ],
            $this->getAuthHeader()
        );

        $this->assertResponseStatus(201);
        $this->seeInDatabase('comments', ['content' => $content]);
        $this->seeInDatabase('commentables', ['commentable_id' => $preorder_id]);
    }
}
