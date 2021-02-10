<?php

namespace Tests\Feature\Relations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class PostTest extends TestCase
{

    use RefreshDatabase;

    public function testPostRelations()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $user->id]);
        $comment = Comment::factory()->create(['from_user' => $user->id, 'on_post' => $post->id]);
        $comment1 = Comment::factory()->create(['from_user' => $user->id, 'on_post' => $post->id]);

        // Tests for user relation one to many
        $this->assertInstanceOf(User::class,$post->author);
        $this->assertEquals(1,$post->author->count());

        // Tests for comment relation one to many
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $post->comments);
        $this->assertEquals(2,$post->comments->count());
        $this->assertTrue($post->comments->contains($comment));
        $this->assertTrue($post->comments->contains($comment1));
    }
}
