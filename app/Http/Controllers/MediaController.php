<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //
        $medias = $this->getMedias($user);
        return view("plumr.account.medias.index", [
            'user' => $user,
            'medias' => $medias,
        ]);
    }

    protected function getMedias(User $user)
    {
         if(isfollower($user)) { // Verificar si usuario autenticado es seguidor
            return $user->medias()
            ->whereIn('visibility', ['public', 'followers_only'])
            ->latest()->take(10)->get();
        }elseif(auth()->user()->id !== $user->id) { // Verificar si usuario no es el mismo autenticado
            return $user->medias()
            ->whereIn('visibility', ['public'])
            ->latest()->take(10)->get();
        } else {
            return $user->medias()
            ->latest('updated_at')->take(10)->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
