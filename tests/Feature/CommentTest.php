<?php

namespace Tests\Feature;

use Session;

use App\Comment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddComment()
    {
        Session::start();

        // test comment posting/saving
        $page_id = rand(pow(10, 4 - 1), pow(10, 4) - 1);
        $response = $this->json('POST', '/comments', [
            'page_id'   => $page_id,
            'comment'   => 'Layer 1 Comment',
            'reply'     => 0,
            'user_name' => 'Me',
            '_token'    => csrf_token()
        ]);

        $response->assertStatus(200);
        $comments = Comment::where('page_id', $page_id)->get();
        $this->assertEquals($comments->count(), 1);

        // test reply posing/saving
        $comment = $comments->first();
        $response = $this->json('POST', '/comments', [
            'reply_id'  => $comment->id,
            'comment'   => 'Layer 2 Comment',
            'reply'     => 1,
            'user_name' => 'Me',
            '_token'    => csrf_token()
        ]);

        $response->assertStatus(200);
        $comments = Comment::where('reply_id', $comment->id)->get();
        $this->assertEquals($comments->count(), 1);
    }
}
