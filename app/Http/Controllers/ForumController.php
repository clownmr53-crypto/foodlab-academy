<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumThread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(): View
    {
        $categories = ForumCategory::query()
            ->where('is_published', true)
            ->withCount('threads')
            ->orderBy('order')
            ->get();

        return view('forum.index', compact('categories'));
    }

    public function category(ForumCategory $category): View
    {
        abort_unless($category->is_published || (auth()->user()?->isAdmin()), 404);

        $threads = ForumThread::query()
            ->where('forum_category_id', $category->id)
            ->with('user')
            ->withCount('posts')
            ->latest()
            ->paginate(20);

        return view('forum.category', compact('category', 'threads'));
    }

    public function createThread(ForumCategory $category): View
    {
        abort_unless($category->is_published, 404);

        return view('forum.create-thread', compact('category'));
    }

    public function storeThread(Request $request, ForumCategory $category): RedirectResponse
    {
        abort_unless($category->is_published, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $thread = ForumThread::create([
            'forum_category_id' => $category->id,
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        return redirect()->route('forum.thread', $thread)->with('status', 'Sujet créé.');
    }

    public function showThread(ForumThread $thread): View
    {
        $thread->load(['user', 'category', 'posts.user']);

        abort_unless($thread->category->is_published || (auth()->user()?->isAdmin()), 404);

        return view('forum.thread', compact('thread'));
    }

    public function storePost(Request $request, ForumThread $thread): RedirectResponse
    {
        abort_if($thread->is_locked && ! $request->user()->isAdmin(), 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
        ]);

        ForumPost::create([
            'forum_thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return back()->with('status', 'Réponse publiée.');
    }

    public function destroyThread(Request $request, ForumThread $thread): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);
        $category = $thread->category;
        $thread->delete();

        return redirect()->route('forum.category', $category)->with('status', 'Sujet supprimé.');
    }

    public function destroyPost(Request $request, ForumPost $post): RedirectResponse
    {
        abort_unless($request->user()->isAdmin(), 403);
        $post->delete();

        return back()->with('status', 'Message supprimé.');
    }
}
