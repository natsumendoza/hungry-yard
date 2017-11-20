<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Helpers;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // GET NOTIFICATIONS INFO
        Helpers::getNotifications();

        if(Auth::user()->isOwner()) {
            $menuList = Menu::where('stall_id', Auth::user()->id)
                ->get()->toArray();

            return view('menus.menuList', compact('menuList'));
        } else {
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('menus.menu');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedMenu = $this->validate($request, [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'numeric|required',
            'preparationTime' => 'numeric|required|max:30'
        ]);

        $cleanName = preg_replace('/\s+/', '_', $validatedMenu['name']);
        $imageName =   $cleanName . (Auth::user()->id * 2) . time() . '.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/menu'), $imageName);

        $menu                       = array();
        $menu['stall_id']           = Auth::user()->id;
        $menu['name']               = $validatedMenu['name'];
        $menu['image']              = $imageName;
        $menu['price']              = $validatedMenu['price'];
        $menu['preparation_time']   = $validatedMenu['preparationTime'];

        Menu::create($menu);



        return redirect('menu')->with('success', 'Menu item has been added');
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
        $id = base64_decode($id);

        $menu = Menu::find($id);

        return view('menus.menu', compact('menu','id'));
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

        $menu = Menu::find($id);

        $validatedMenu = $this->validate($request, [
            'name' => 'required',
            'price' => 'numeric|required',
            'preparationTime' => 'numeric|required|max:30'
        ]);


        if($request->file('image') != NULL)
        {
            $destinationPath = public_path('images/menu');
            File::delete([$destinationPath.'/'.$menu['image']]);

            $cleanName = preg_replace('/\s+/', '_', $validatedMenu['name']);
            $imageName = $cleanName . (Auth::user()->id * 2) . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($destinationPath, $imageName);
            $menu['image'] = $imageName;
        }

        $menu['stall_id']           = Auth::user()->id;
        $menu['name']               = $validatedMenu['name'];
        $menu['price']              = $validatedMenu['price'];
        $menu['preparation_time']   = $validatedMenu['preparationTime'];
        $menu->save();

        return redirect('menu')->with('success', 'Menu item has been updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = base64_decode($id);
        $menu = Menu::find($id);

        File::delete(public_path('images/menu/'.$menu['image']));

        $menu->delete();


        return redirect('menu')->with('success', 'Menu item has been deleted');
    }

}
