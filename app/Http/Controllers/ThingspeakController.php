<?php

namespace App\Http\Controllers;

use App\Models\Thingspeak;
use Illuminate\Http\Request;

class ThingspeakController extends Controller
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
        $thingspeak = new Thingspeak;
        
        return view('thingspeak.form', compact('thingspeak'));
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
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function show(Thingspeak $thingspeak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function edit(Thingspeak $thingspeak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thingspeak $thingspeak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thingspeak $thingspeak)
    {
        $thingspeak->delete();
    }


    public function fetch(Thingspeak $thingspeak)
    {
        //
    }
}
