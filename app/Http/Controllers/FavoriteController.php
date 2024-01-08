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
    //    return $favorites;
       return new FavoriteDetailResource($favorites);


       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // $favorite = Favorite::create([
        //     "user_id" => Auth::id(),
        //     "contact_id"=> $request->contact_id

        // ]);
        // return $favorite;
        // return response()->json(['message'=>'Favorite added successfully'],201);
      
    }
    public function markAsFavorite(Request $request)
    {
        $contact = Contact::find($request->id);
        // return $contact;

        if (is_null($contact)) {
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ], 404);
        }

        $user = $request->user();
        $favorite = Favorite::where([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
        ])->first();

        if ($favorite) {
            return response()->json(['message' => 'Contact already in favorites'], 200);
        }
        $favorite = Favorite::Create([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
        ]);


        return response()->json([
            'message' => 'Contact marked as favorite',
            "favorite" => $favorite
        
        ], 200);
    }

    // public function removeFavorite(Request $request)
    // {
    //     $contact = Contact::find($request->id);
    //     if (is_null($contact)) {
    //         return response()->json([
    //             // "success" => false,
    //             "message" => "Contact not found",

    //         ], 404);
    //     }


    //     $user = $request->user();
    //     $favorite = Favorite::where([
    //         'user_id' => $user->id,
    //         'contact_id' => $contact->id,
    //     ])->first();

    //     $favorite->delete();

    //     return response()->json([
    //         "favorite" => $favorite,
    //         "message" => "Favorite removed from contact",
    //     ]);
    // }

   
    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        $favorite = Favorite::find($favorite);
        
        if (is_null($favorite)) {
            return response()->json(['message' => 'Favorite not found'], 404);
        }
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
    public function destroy(Request $request)
    {
    
        $contact = Contact::find($request->id);

        if (is_null($contact)){
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $user = $request->user();

        // Find the favorite relationship between the user and the contact
        $favorite = Favorite::where([
            'user_id' => $user->id,
            'contact_id' => $contact->id,
        ])->first();

        if (!$favorite) {
            return response()->json(['message' => 'Contact is not in favorites'], 404);
        }

        // Remove the favorite relationship
        $favorite->delete();

        return response()->json(['message' => 'Contact removed from favorites'], 200);
    }
}
