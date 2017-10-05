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
        $this->signIn();
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());
        $this->get($response->headers->get('Location'))
                ->assertSee($thread->title)
                ->assertSee($thread->body);
    }
}
