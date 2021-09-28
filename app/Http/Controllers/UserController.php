<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();

        return view('users.index', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        // $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone_number' => $request->phone_number ?? "",
            'social_media' => $request->social_media ?? "",
            'socmed_detail' => $request->socmed_detail ?? "",
            'total_followers' => $request->total_followers ?? 0,
            'company' => $request->company ?? "",
            'web_linkedin' => $request->web_linkedin ?? "",
            'partner_role' => $request->partner_role ?? "",
            'industry' => $request->industry ?? "",
            'npwp' => $request->npwp ?? "",
            'city'=> $request->city ?? "",
            'description' => $request->description ?? "",
            'is_joined' => $request->is_joined ?? 0,
            'is_active' => $request->is_active ?? 0,
        ]);

        $role = $request->role;

        if ($role == "ADMIN") {
            return redirect()->route('users.index');
        } elseif ($role == "TALENT") {
            return redirect()->route('users.talents');
        } {
            return redirect()->route('users.partners');
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.edit',[
            'item' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit',[
            'item' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();

        if($request->file('profile_photo_path'))
        {
            $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');
        }
        $data['password'] = Hash::make($request->password);

        $user->update($data);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }

    public function indexOfTalents()
    {
        $user = User::where('role', 'TALENT')->get();

        return view('users.index-talents', [
            'user' => $user
        ]);
    }
    public function indexOfPartners()
    {
        $user = User::where('role', 'PARTNER')->get();

        return view('users.index-partners', [
            'user' => $user
        ]);
    }

    public function newAccountRequest()
    {
        $user = User::where('is_active', 0)->get();

        return view('users.index-new-request', [
            'user' => $user
        ]);
    }
}
