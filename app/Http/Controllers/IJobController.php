<?php

namespace App\Http\Controllers;

use App\Models\iJob;
use Illuminate\Http\Request;

class IJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.ijob.index', compact('ijob'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $iJob = iJob::get();

        return view('admin.ijob.create', compact('ijob'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        iJob::create([
            'name' => $request->name,
        ]);

        return redirect()->route('ijob.index')->with('success', 'IJob successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(iJob $iJob)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(iJob $iJob)
    {
        $iJob = iJob::get();

        return view('admin.ijob.edit', compact('ijob'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, iJob $iJob, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $iJob = iJob::findOrFail($id);
        $iJob->update($request->all());

        return redirect()->route('ijob.index')->with('success', 'IJob successfully created');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(iJob $iJob, $id)
    {
        $iJob = iJob::findOrFail($id);
        $iJob->delete();
        return redirect()->route('ijob.index')->with('success', 'ijob successfully deleted');
    }
}
