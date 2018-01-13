<?php

namespace App\Http\Controllers\Admin;

use App\Models\Thingspeak;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SaveThingspeakDeviceRequest;
use App\Models\Group;
use Illuminate\Support\Collection;

class ThingspeakController extends Controller
{
    protected function redirectLists(string $type = null)
    {
        $isProbecube = is_null($type) || $type == 'Probecube';
        $route = $isProbecube ? 'admin.probecube' : 'admin.independent';

        return redirect()->route($route);
    }

    protected function fieldsTransfer(Collection $fields, bool $toDB = true)
    {
        return $fields->map(function ($number) use ($toDB) {
            if ($toDB) {
                return 'field' . $number;
            }

            return str_replace('field', '', $number);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->redirectLists();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $thingspeak = new Thingspeak;
        $fields = collect();

        return view('admin.thingspeak.form', compact('thingspeak', 'fields'));
    }

    protected function saveInstance(SaveThingspeakDeviceRequest $request, Thingspeak $thingspeak)
    {
        $group = Group::where('name', $request->input('group'))->firstOrFail();
        $isProbecube = $group->name == 'Probecube';

        $thingspeak->group_id = $group->id;
        $thingspeak->channel = $request->input('channel');
        $thingspeak->party = $isProbecube ? 'Probecube' : $request->input('party');
        $thingspeak->maker = $request->input('maker');
        $thingspeak->fields_map = $this->fieldsTransfer(collect($request->input('fields')));
        $thingspeak->active = (bool) $request->input('active');
        $thingspeak->save();

        return $thingspeak;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveThingspeakDeviceRequest $request)
    {
        $thingspeak = new Thingspeak;

        $thingspeak = $this->saveInstance($request, $thingspeak);

        return $this->redirectLists($thingspeak->party);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function show(Thingspeak $thingspeak)
    {
        return $this->redirectLists($thingspeak->party);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function edit(Thingspeak $thingspeak)
    {
        $fields = $this->fieldsTransfer($thingspeak->fields_map, false);

        return view('admin.thingspeak.form', compact('thingspeak', 'fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function update(SaveThingspeakDeviceRequest $request, Thingspeak $thingspeak)
    {
        $thingspeak = $this->saveInstance($request, $thingspeak);

        return $this->redirectLists($thingspeak->party);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thingspeak  $thingspeak
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thingspeak $thingspeak)
    {
        $party = $thingspeak->party;

        $thingspeak->delete();

        return $this->redirectLists($party);
    }
}
