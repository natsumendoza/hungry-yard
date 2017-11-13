<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleryList = Gallery::all()->toArray();

        return view('admin/gallery/galleryList', compact('galleryList', $galleryList));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/gallery/addGallery');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedImage = $this->validate($request, [
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $cleanName = preg_replace('/\s+/', '_', $validatedImage['name']);
        $imageName =   $cleanName . (Auth::user()->id * 2) . time() . '.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/gallery'), $imageName);

        $image = array();
        $image['name'] = $validatedImage['name'];
        $image['image_path'] = $imageName;

        Gallery::create($image);

        return redirect('gallery')->with('success', 'Image has been added');

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
        $gallery = Gallery::find($id);

        return view('admin/gallery/editGallery', compact('gallery', $gallery));
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
        $gallery = Gallery::find($id);

        $validatedGallery = $this->validate($request, [
            'name' => '|required',
            'image' => 'required',
        ]);

        File::delete(public_path('images/gallery/'.$gallery['image_path']));

        $cleanName = preg_replace('/\s+/', '_', $validatedGallery['name']);
        $imageName =   $cleanName . (Auth::user()->id * 2) . time() . '.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/gallery'), $imageName);

        $gallery['name']               = $validatedGallery['name'];
        $gallery['image_path']              = $imageName;
        $gallery->save();

        return redirect('gallery')->with('success', 'Gallery has been updated');
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
        $gallery = Gallery::find($id);

        File::delete(public_path('images/gallery/'.$gallery['image_path']));

        $gallery->delete();


        return redirect('gallery')->with('success', 'Image has been deleted');
    }
}
