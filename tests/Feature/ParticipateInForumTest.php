<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function an_unauthenticated_user_may_not_participated_in_forum_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray());
    }
    
    /** @test */
    function an_authenticated_user_may_participated_in_forum_threads()
    {
        $user = factory('App\User')->create();
        $this->be($user);
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray());
        $this->get('/threads/' . $thread->id)->assertSee($reply->body);
    }
}
