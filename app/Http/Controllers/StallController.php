<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\StallImage;
use Illuminate\Support\Facades\DB;

use Auth;

class StallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        $users = User::all()->where('role_id', '2')->toArray();
        $usersWithImage = DB::table('users')
            ->join('stall_images', 'users.id', 'stall_images.user_id')
            ->select('users.id', 'users.name', 'users.name', 'users.email', 'stall_images.image_path')
            ->where('users.role_id', '2')
            ->get();

        $users = array();
        foreach ($usersWithImage as $userWithImage)
        {
            $userWithImage                   = (array) $userWithImage;
            $users[$userWithImage['id']] = $userWithImage;
        }


        return view('admin/stalls/stalls', compact('users', $users));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/stalls/addstall');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedStall = $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $cleanName = preg_replace('/\s+/', '_', $validatedStall['name']);
        $imageName =   $cleanName . (Auth::user()->id * 2) . time() . '.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/stall'), $imageName);

        $stall = array();
        $stall['role_id'] = 2;
        $stall['name'] = $validatedStall['name'];
        $stall['email'] = $validatedStall['email'];
        $stall['password'] = bcrypt($validatedStall['password']);
        $insertedUser = User::create($stall);

        $stallImage = array();
        $stallImage['user_id'] = $insertedUser->id;
        $stallImage['image_path'] = $imageName;

        StallImage::create($stallImage);

        return redirect('stall')->with('success', 'Stall has been added');
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
