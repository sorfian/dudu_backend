<?php

namespace App\Http\Controllers;

use App\Http\Requests\TalentRequest;
use App\Models\Talent;
use App\Models\User;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::where('role', 'TALENT')->with('talent')->get();

        // return view('talents.index', [
        //     'users' => $users
        // ]);

        $talents = Talent::with('user')->get();
        $users = User::all();
        $newUser = User::where('is_active', 0)->get();


        return view('talents.index', [
            'talents' => $talents,
            'users' => $users,
            'newuser' => $newUser,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('talents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TalentRequest $request)
    {
        $data = $request->all();

        $data['picture_path'] = $request->file('picture_path')->store('assets/talents', 'public');

        Talent::create($data);

        return redirect()->route('talents.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Talent $talent)
    {
        $newUser = User::where('is_active', 0)->get();

        return view('talents.edit',[
            'item' => $talent,
            'newuser' => $newUser,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Talent $talent)
    {
        $newUser = User::where('is_active', 0)->get();

        return view('talents.edit',[
            'item' => $talent,
            'newuser' => $newUser,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Talent $talent)
    {
        $data = $request->all();

        if($request->file('profile_photo_path'))
        {
            $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');
        }

        $talent->update($data);

        return redirect()->route('talents.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Talent $talent)
    {
        $talent->delete();

        return redirect()->route('talents.index');
    }
}
