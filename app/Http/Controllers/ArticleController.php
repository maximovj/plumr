<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request, User $user)
    {
        //
        if($user->id == auth()->user()->id) {
            $articles = $user->articles()->latest('updated_at')->take(30)->get();
        }else {
            $articles = $user->articles()->where('is_publish', true)->latest('published_at')->take(30)->get();
        }

        return view('plumr.account.articles.index', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request, User $user)
    {
        //
        return view('plumr.account.articles.create', [
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        // Crear un nuevo artículo
        $new_article = new Article();
        $new_article->fill($request->validated());
        $new_article->slug = Str::slug($new_article->title.'-'.now()->format('H:m:s:m:Y'));
        $new_article->og_title = $new_article->title;
        $new_article->seo_title = $new_article->title;
        $new_article->seo_description = $new_article->summary;
        $new_article->og_description = $new_article->summary;
        $new_article->tags = explode(",", $new_article->tags);
        $new_article->seo_keywords = implode(',', $new_article->tags);
        $new_article->is_publish = $request->get('is_publish') == 'true' ? true : false;

        if($request->hasFile('cover')) {
            $path = $request->file('cover')->store('articles/cover', 'public');
            $new_article->cover = $path;
            $new_article->og_image = $path;
        }

        // Guardar un artículo
        $new_article->save();

        // Almacenar articulo en la tabla pivote
        $id = DB::table('articles_users')->insert([
            'article_id' => $new_article->id,
            'user_id' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('articles.index', ['user' => auth()->user()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Article $article)
    {
        //
        return view('plumr.account.articles.show', compact('user', 'article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Article $article)
    {
        //
        return view("plumr.account.articles.edit", [
            'user' => $user,
            'article' => $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, User $user, Article $article)
    {
        $old_article = (object) $article->toArray(); // Guardar una copia del artículo antiguo

        // Actualizar los datos del artículo
        $article->fill($request->validated());
        $article->slug = $old_article->slug;
        $article->og_title = $article->title;
        $article->seo_title = $article->title;
        $article->seo_description = $article->summary;
        $article->og_description = $article->summary;
        $article->tags = explode(",", $article->tags);
        $article->seo_keywords = implode(',', $article->tags);

        if($request->hasFile('cover')) {

            // Eliminar portada del artículo antiguo
            if($old_article->cover && Storage::disk('public')->exists($old_article->cover)) {
                Storage::disk('public')->delete($old_article->cover);
                toastr()->addInfo('Portada anterior eliminado correctamente');
            }

            $path = $request->file('cover')->store('articles/cover', 'public');
            $article->cover = $path;
            $article->og_image = $path;
            toastr()->addSuccess('Portada modificado correctamente');
        }

        $article->save();
        toastr()->addSuccess('Artículo modificado correctamente');

        sweetalert()
        ->showConfirmButton(
           true,
            "Enterado",
            "btn btn-success",
            "Enterado"
        )
        ->addSuccess('Artículo modificado correctamente');
        return redirect()->route('articles.index', [$user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        //
    }
}
