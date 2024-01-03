<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request)
    {
        // return back the user and the associated driver model
        $user = $request->user();
        $user->load('driver');
        return $user;
    }

    public function update(Request $request)
    {
        $request->validate([
            'make' => 'required',
            'model' => 'required',
            'year' => 'required|numeric|between:2010,2024',
            'color' => 'required',
            'license_plate' => 'required',
            'name' => 'required',
        ]);

        $user = $request->user();

        $user->update(
            $request->only(
                'name'
            )
        );

        $user->driver()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(
                'make',
                'model',
                'year',
                'color',
                'license_plate'
            )
        );

        $user->load('driver');

        return $user;
    }
}
