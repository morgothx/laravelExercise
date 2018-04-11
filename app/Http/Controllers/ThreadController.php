<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Channel;
use App\Thread;
use App\User;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel)
    {
        $threads = $this->getThreads($channel);
        return view('threads.index', compact('threads'));
    }
    
    private function getThreads(Channel $channel)
    {
        if($channel->exists)
            $threads = $channel->threads()->latest();
        else
            $threads = Thread::latest();
        
        if($username = request('by'))
        {
            $user = User::where('name', 'like', $username)->firstOrFail();
            $threads->where('user_id', $user->id);
        }
        
        return $threads->get();
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, 
                [
                    'title' => 'required',
                    'body' => 'required',
                    'channel_id' => 'required|exists:channels,id'
                ]);
        $thread = Thread::create
        ([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);
        return redirect($thread->path());
    }

    public function show($channelId, Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }

    public function edit(Thread $thread)
    {
        //
    }

    public function update(Request $request, Thread $thread)
    {
        //
    }

    public function destroy(Thread $thread)
    {
        //
    }
}
