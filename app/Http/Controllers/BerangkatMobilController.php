<?php

namespace App\Http\Controllers;

use App\Models\BerangkatMobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BerangkatMobilController extends Controller
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
     * @param  \App\Models\BerangkatMobil  $berangkatMobil
     * @return \Illuminate\Http\Response
     */
    public function show(BerangkatMobil $berangkatMobil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BerangkatMobil  $berangkatMobil
     * @return \Illuminate\Http\Response
     */
    public function edit(BerangkatMobil $berangkatMobil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BerangkatMobil  $berangkatMobil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BerangkatMobil $berangkatMobil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BerangkatMobil  $berangkatMobil
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $berangkatMobil = BerangkatMobil::where('id', $id)->first();
            $berangkatMobil->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
