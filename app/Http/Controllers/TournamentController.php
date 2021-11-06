<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTournamentRequest;
use App\Http\Requests\UpdateTournamentRequest;
use App\Models\Tournament;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tournaments = Tournament::where("created_by", Auth::user()->id)->latest()->get();
        return view("tournament.list", compact("tournaments"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("tournament.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTournamentRequest $request)
    {
        try {
            // Retrieve the validated input data...
            $validated = $request->validated();

            $uploadImageNames = "";
            if ($request->hasfile('organizationLogo')) {

                $name = time() . "_" . $request->file('organizationLogo')->getClientOriginalName();
                $request->file('organizationLogo')->move(public_path() . '/uploads/', $name);
                $uploadImageNames = $name;
            }
            DB::beginTransaction();
            $tournament  = new Tournament();
            $tournament->org_name = $request->organizationName;
            $tournament->name = $request->tournamentName;
            $tournament->no_of_players = $request->numberOfPlayers;
            $tournament->primary_color = $request->primaryColor;
            $tournament->secondary_color = $request->secondaryColor;
            $tournament->org_logo = $uploadImageNames;
            $tournament->created_by = Auth::user()->id;
            $tournament->save();
            DB::commit();
            return redirect()->route("dashboard");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages(["organizationName" => [$th->getMessage()]]);
        }
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

        $tournament = Tournament::find($id);
        if (isset($tournament)) {
            return view("tournament.edit", compact("tournament"));
        }
        throw ValidationException::withMessages(["organizationName" => ["Invalid tournament selected"]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTournamentRequest $request, $id)
    {
        try {
            // Retrieve the validated input data...
            $validated = $request->validated();
            $tournament =  Tournament::find($id);
            if (!isset($tournament)) {
                throw ValidationException::withMessages(["organizationName" => ["Invalid tournament selected"]]);
            }
            $uploadImageNames = null;
            if ($request->hasfile('organizationLogo')) {

                $name = time() . "_" . $request->file('organizationLogo')->getClientOriginalName();
                $request->file('organizationLogo')->move(public_path() . '/uploads/', $name);
                $uploadImageNames = $name;
            }
            DB::beginTransaction();
            $tournament->org_name = $request->organizationName;
            $tournament->name = $request->tournamentName;
            $tournament->no_of_players = $request->numberOfPlayers;
            $tournament->primary_color = $request->primaryColor;
            $tournament->secondary_color = $request->secondaryColor;
            if ($uploadImageNames != null) {
                $tournament->org_logo = $uploadImageNames;
            }
            $tournament->save();
            DB::commit();
            return redirect()->route("dashboard");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages(["organizationName" => [$th->getMessage()]]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tournament = Tournament::find($id);
        if (isset($tournament)) {
            $tournament->delete();
            return redirect()->route("dashboard");
        }
        throw ValidationException::withMessages(["organizationName" => ["Invalid tournament selected"]]);
    }
    /**
     * Download the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function download($id)
    {
        try {
            $tournament = Tournament::find($id);
            if (isset($tournament)) {
                Storage::disk('local')->put('example.txt', 'Contents');
            }
            throw ValidationException::withMessages(["organizationName" => ["Invalid tournament selected"]]);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(["organizationName" => [$th->getMessage()]]);
        }
    }
}
