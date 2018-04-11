<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
/**
* @phpunitG ReadThreadsTest
*/
class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    private $thread;
    
    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }


    /** @test */
    public function a_user_can_view_all_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }
    
    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }
    
    /** @test */
    public function a_user_can_read_the_replies_of_a_thread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $response = $this->get($this->thread->path());
        $response->assertSee($reply->body);
    }
    
    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadAssociated = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotAssociated = create('App\Thread');
        $this->get('/threads/' . $channel->slug)
             ->assertSee($threadAssociated->title)
             ->assertDontSee($threadNotAssociated->title);
    }
    
    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $user = create('App\User', ['name' => 'Camilo']);
        $this->signIn($user);
        $threadByCamilo = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByCamilo = create('App\Thread');
        $this->get('threads?by=Camilo')
                ->assertSee($threadByCamilo->title)
                ->assertDontSee($threadNotByCamilo->title);
    }
}
