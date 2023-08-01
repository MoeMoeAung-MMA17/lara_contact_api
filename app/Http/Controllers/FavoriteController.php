<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavoriteDetailResource;
use App\Models\Contact;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
       $favorites = Favorite::where('user_id',Auth::id())->get();
       return new FavoriteDetailResource($favorites);


       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $favorite = Favorite::create([
            "user_id" => Auth::id(),
            "contact_id"=> $request->contact_id

        ]);
        // return response()->json(['message'=>'Favorite added successfully'],201);
        return new FavoriteDetailResource($favorite);

    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        $favorite = Auth::user()->favorites->find($favorite);
        if(is_null($favorite)){
            return response()->json([
                // "success" => false,
                "message" => "Favorite not found",

            ],404);
        }
        $favorite->delete();

        // return response()->json([],204);
        return response()->json([
            "message" => "Favorite is deleted",
        ]);

    }
}
