<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
* @phpunitG CreateThreadTest
*/
class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function guest_cannot_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());
    }
    
    /** @test */
    public function guest_cannot_see_create_threads_page()
    {
        $response = $this->withExceptionHandling()->get('/threads/create');
        $response->assertRedirect('/login');
    }
    
    /** @test */
    public function an_authenticated_user_can_create_new_threads()
    {
        $this->actingAs(factory('App\User')->create());
        $thread = factory('App\Thread')->create();
        $this->post('/threads', $thread->toArray());
        $response = $this->get($thread->path());
        $response->assertSee($thread->title)
                ->assertSee($thread->body);
    }
}
