<?php

namespace Tests\Feature\Relations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function testUserRelations()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $user->id]);
        $post1 = Post::factory()->create(['author_id' => $user->id]);
        $comment = Comment::factory()->create(['from_user' => $user->id, 'on_post' => $post->id]);
        $comment1 = Comment::factory()->create(['from_user' => $user->id, 'on_post' => $post->id]);

        // Tests for comment relation one to many
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->comments);
        $this->assertEquals(2,$user->comments->count());
        $this->assertTrue($user->comments->contains($comment));
        $this->assertTrue($user->comments->contains($comment1));
        
        // Tests for post relation one to many
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $user->comments);
        $this->assertEquals(2,$user->posts->count());
        $this->assertTrue($user->posts->contains($post));
        $this->assertTrue($user->posts->contains($post1));

        $user->role = 'admin';
        $user->save();
        $this->assertEquals(true,$user->is_admin());
        $this->assertEquals(true,$user->can_post());

        $user->role = 'author';
        $user->save();
        $this->assertEquals(false,$user->is_admin());
        $this->assertEquals(true,$user->can_post());

        $user->role = 'subscriber';
        $user->save();
        $this->assertEquals(false,$user->is_admin());
        $this->assertEquals(false,$user->can_post());
    }
}
