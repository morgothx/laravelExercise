<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
* @phpunitG ParticipateInForumTest
*/
class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function an_unauthenticated_user_may_not_participated_in_forum_threads()
    {
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        $this->withExceptionHandling()
             ->post($thread->path() . '/replies', $reply->toArray())
             ->assertRedirect('/login');
    }
    
    /** @test */
    function an_authenticated_user_may_participated_in_forum_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());
        $this->get($thread->path())
             ->assertSee($reply->body);
    }
}
