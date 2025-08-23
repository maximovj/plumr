<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RahulHaque\Filepond\Facades\Filepond;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        $profile = $user->profile;
        return view('plumr.account.edit', compact('user', 'profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        //dd($user);
        return redirect()->route('main_account', ['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function edit_photo(User $user)
    {
        return view("plumr.account.edit_photo", compact('user'));
    }

    public function update_photo(Request $request, User $user)
    {
        // Single and multiple file validation
        $this->validate($request, [
            'avatar' => Rule::filepond([
                'required',
                'image',
                'max:2000'
            ]),
            'gallery.*' => Rule::filepond([
                'required',
                'image',
                'max:2000'
            ])
        ]);

        // Set filename
        $avatarName = 'avatar-' . auth()->id();

        // Move the file to permanent storage
        // Automatic file extension set
        // $fileInfo = Filepond::field($request->avatar)
        //     ->moveTo('avatars/' . $avatarName);

        // dd($fileInfo);
        // [
        //     "id" => 1,
        //     "dirname" => "avatars",
        //     "basename" => "avatar-1.png",
        //     "extension" => "png",
        //     "mimetype" => "image/png",
        //     "filename" => "avatar-1",
        //     "location" => "avatars/avatar-1.png",
        //     "url" => "http://localhost/storage/avatars/avatar-1.png",
        // ];

        $galleryName = 'gallery-' . auth()->id();

        // $fileInfos = Filepond::field($request->gallery)
        //     ->moveTo('galleries/' . $galleryName);

        return back()->with('success', 'Foto de perfil modificado exitosamente');
    }


}
