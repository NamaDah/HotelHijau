<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('admin.room.index', compact('room'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $room = Room::get();

        return view('admin.room.create', compact('room'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        Room::create([
            'name' => $request->name,
        ]);

        return redirect()->route('Room.index')->with('success', 'Room successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $room = Room::get();

        return view('admin.Room.edit', compact('Room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('Room.index')->with('success', 'Room successfully created');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room, $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('Room.index')->with('success', 'Room successfully deleted');
    }
}
