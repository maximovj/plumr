<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use Illuminate\Database\QueryException;
use App\Http\Requests\UpdatePostRequest;
use App\Models\UserPost;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        if($user->id == auth()->user()->id) {
            $posts = $user->posts()->with('author.profile')->latest('updated_at')->take(30)->get();
        }else {
            $posts = $user->posts()->with('author.profile')->take(30)->get();
        }

        return view("plumr.account.posts.index", [
            'user' => $user,
            'profile' => $user->profile,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        dd('crear una nueva ruta');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, User $user)
    {
        //
        try {
            $fields = $request->validated();

            $new_post = new Post();
            $new_post->title = $fields['title'];
            $new_post->descripcion = 'Sin descripción';
            $new_post->url_access = Str::slug($fields['title'].'-'.now());
            $new_post->content = $fields['content'];
            $new_post->status = $fields['status'] ?? [];
            $new_post->tags = $fields['tags'] ?? [];
            $new_post->links = $fields['links'] ?? [];
            $new_post->save();

            UserPost::create([
                'user_id' => auth()->user()->id,
                'post_id' => $new_post->id,
            ]);

            return redirect()->route('posts.index', [
                'user' => $user,
            ])->with('success', 'Publicación creada con éxito.');

        } catch (QueryException $e) {
            // 23000 → violación de restricción (clave duplicada, etc.)
            if ($e->getCode() === '23000') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'El valor para "url_access" ya está en uso. Por favor, elige otro.');
            }

            // Si es otro error de SQL, lo lanzamos de nuevo
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Post $post)
    {
        //
        return view("plumr.account.posts.show", [
            'user' => $user,
            'post' => $post,
            'discussions' => collect([]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
