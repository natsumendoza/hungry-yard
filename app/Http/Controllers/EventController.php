<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use DateTime;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->isAdmin()) {
            $eventList = Event::all()->toArray();
//        $eventList = Event::orderBy('date', 'desc')->take(5)->get();

            return view('admin/event/eventList', compact('eventList', $eventList));
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
        return view('admin/event/addEvent');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedEvent = $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $cleanName = preg_replace('/\s+/', '_', $validatedEvent['name']);
        $imageName =   $cleanName . (Auth::user()->id * 2) . time() . '.'.$request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/event'), $imageName);


        $date = new DateTime($validatedEvent['date']);
        $formattedDate = $date->format('Y-m-d H:i:s');

        $event = array();
        $event['name'] = $validatedEvent['name'];
        $event['description'] = $validatedEvent['description'];
        $event['date'] = $formattedDate;
        $event['image_path'] = $imageName;

        Event::create($event);

        return redirect('event')->with('success', 'Event has been added');
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
        $event = Event::find($id);

        return view('admin/event/editEvent', compact('event', $event));
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
        $event = Event::find($id);

        $validatedEvent = $this->validate($request, [
            'name' => '|required',
            'description' => '|required',
            'date' => '|required',
        ]);

        $event['name']               = $validatedEvent['name'];
        $event['description']               = $validatedEvent['description'];
        $event['date']               = $validatedEvent['date'];

        if($request['image'] != NULL) {
            File::delete(public_path('images/event/'.$event['image_path']));

            $cleanName = preg_replace('/\s+/', '_', $validatedEvent['name']);
            $imageName =   $cleanName . (Auth::user()->id * 2) . time() . '.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/event'), $imageName);
            $event['image_path']              = $imageName;
        }





        $event->save();

        return redirect('event')->with('success', 'Event has been updated');
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
        $event = Event::find($id);

        File::delete(public_path('images/event/'.$event['image_path']));

        $event->delete();


        return redirect('event')->with('success', 'Event has been deleted');
    }
}
