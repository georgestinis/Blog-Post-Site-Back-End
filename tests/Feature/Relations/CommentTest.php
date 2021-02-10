<?php

namespace Tests\Feature\Relations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentTest extends TestCase
{
    
    use RefreshDatabase;

    public function testCommentRelations()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $user->id]);
        $comment = Comment::factory()->create(['from_user' => $user->id, 'on_post' => $post->id]);

        // Tests for user relation one to many
        $this->assertInstanceOf(User::class,$comment->user);
        $this->assertEquals(1,$comment->user->count());

        // Tests for post relation one to many
        $this->assertInstanceOf(Post::class,$comment->post);
        $this->assertEquals(1,$comment->post->count());
    }
}
