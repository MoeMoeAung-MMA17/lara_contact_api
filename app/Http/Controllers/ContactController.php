<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactDetailResource;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{

    // response properties
    // success [ true, false ]
    // message
    // errors
    // data

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    //     $contacts = Contact::latest("id")->paginate(5)->withQueryString();
    //     return ContactResource::collection($contacts);

    $contacts = Contact::when(request()->has("keyword"), function ($query) {
        $query->where(function (Builder $builder) {
            $keyword = request()->keyword;

            $builder->where("name", "like", "%" . $keyword . "%");
            
        });
    })
        ->when(request()->has('show') == "trash",fn($query) => $query->withTrashed() )

        
        ->when(request()->has('name'), function ($query) {
            $sortType = request()->title ?? 'asc';
            $query->orderBy("name", $sortType);
        })
        
        // ->dd()
        ->latest("id")
        ->paginate(7)->withQueryString();

        return ContactResource::collection($contacts);

    }
        
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "country_code" => "required|min:1|max:265",
            "phone_number" => "required"
        ]);

        
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->country_code = $request->country_code;
        $contact->user_id = Auth::id();
        $contact->save();

        return new ContactDetailResource($contact);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        

        $contact = Contact::find($id);
        if(is_null($contact)){
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ],404);
        }

        return response()->json([
            "data" => $contact
        ]);
        return new ContactDetailResource($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "nullable|min:3|max:20",
            "country_code" => "nullable|integer|min:1|max:265",
            "phone_number" => "nullable|min:7|max:15"
        ]);

        $contact = Contact::find($id);
        if(is_null($contact)){
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ],404);
        }

        // $contact->update([
        //     "name" => $request->name,
        //     "country_code" => $request->country_code,
        //     "phone_number" => $request->phone_number
        // ]);

        // $contact->update($request->all());

        if($request->has('name')){
            $contact->name = $request->name;
        }

        if($request->has('country_code')){
            $contact->country_code = $request->country_code;
        }

        if($request->has('phone_number')){
            $contact->phone_number = $request->phone_number;
        }

        $contact->update();



        return new ContactDetailResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id);
        if(is_null($contact)){
            return response()->json([
                // "success" => false,
                "message" => "Contact not found",

            ],404);
        }
        $contact->delete();

        // return response()->json([],204);
        return response()->json([
            "message" => "Contact is deleted",
        ]);
    }
}
