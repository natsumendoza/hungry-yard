<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\StallImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::find($id);

        if (Auth::user()->isOwner()) {
            $stallImage = StallImage::where('user_id', $id)->get()[0];
            return view('account/account')->with(array('user' => $user, 'stallImage' => $stallImage));
        } else {
            return view('account/account')->with(array('user' => $user));
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
        $id = base64_decode($id);
        $user = User::find($id);


        $validatedUser = $this->validate($request, [
            'name' => 'required|string|max:255',
            'paymayaAccount' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);


        if($request->file('image') != NULL)
        {
            $stallImage = StallImage::where('user_id', $id)->get()[0];
            File::delete(public_path('images/stall/'.$stallImage['image_path']));

            $cleanName = preg_replace('/\s+/', '_', $validatedUser['name']);
            $imageName =   $cleanName . (Auth::user()->id * 2) . time() . '.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/stall'), $imageName);

            $stallImage['image_path'] = $imageName;
            $stallImage->save();
        }

        $user['name'] = $validatedUser['name'];
        $stall['paymaya_account'] = $validatedUser['paymayaAccount'];
        $user['email'] = $validatedUser['email'];

        $user->save();

        return redirect('useraccount')->with('success', 'Account has been updated');
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
