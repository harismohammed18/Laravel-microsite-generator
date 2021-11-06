<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTournamentRequest;
use App\Http\Requests\UpdateTournamentRequest;
use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use ZipArchive;

class TournamentController extends Controller
{

    private $invalidTournamentError = "Invalid tournament selected";
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
        throw ValidationException::withMessages(["organizationName" => [$this->invalidTournamentError]]);
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
                throw ValidationException::withMessages(["organizationName" => [$this->invalidTournamentError]]);
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
        throw ValidationException::withMessages(["organizationName" => [$this->invalidTournamentError]]);
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
            if (!isset($tournament)) {
                throw new \Exception($this->invalidTournamentError);
            }
            $cssFile = file_get_contents(storage_path("template/index.css"));
            $templateFile = file_get_contents(storage_path("template/index.html"));


            // replace css prefix
            $cssPrefix = ["/{{primary_color}}/", "/{{secondary_color}}/"];
            $cssValues = [$tournament->primary_color,  $tournament->secondary_color];
            $updatedCssFile = tmpfile();
            fwrite($updatedCssFile, preg_replace($cssPrefix, $cssValues, $cssFile));

            // replace html file
            $cssPrefix = ["/{{title}}/", "/{{image_path}}/", "/{{tournament_name}}/", "/{{org_name}}/"];
            $cssValues = [$tournament->name, $tournament->org_logo, $tournament->name, $tournament->org_name];
            $updatedTemplateFile = tmpfile();
            fwrite($updatedTemplateFile, preg_replace($cssPrefix, $cssValues, $templateFile));


            // create zip file
            $zip = new ZipArchive();
            $tempFileUri = public_path() . "/" . $tournament->id . "-" . $tournament->name . ".zip";
            if ($zip->open($tempFileUri, ZipArchive::CREATE) === TRUE) {
                $zip->addFile(stream_get_meta_data($updatedTemplateFile)['uri'], "index.html");
                $zip->addFile(stream_get_meta_data($updatedCssFile)['uri'], "index.css");
                $zip->addFile(public_path("uploads/" . $tournament->org_logo), $tournament->org_logo);
            }
            $zip->close();

            fclose($updatedTemplateFile);
            fclose($updatedCssFile);
            return response()->download($tempFileUri);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(["organizationName" => [$th->getMessage()]]);
        }
    }
}
