<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactDetailResource;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::all();
        return ContactResource::collection($contacts);

        // return response()->json($contacts);
        // return response()->json([
        //     "message"=>"min ga lar par"
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $request->validate([
            "name"=>"required",
            "country_code" => "required|min:1|max:256",
            "phone_number"=>"required"
        ]);

        $contact = Contact::create([
            "name" => $request->name,
            "country_code" => $request->country_code,
            "phone_number" => $request->phone_number,
            "user_id" => Auth::id()

        ]);
        return new ContactDetailResource($contact);

        // return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        $contact = Contact::find($contact);

        return new ContactDetailResource($contact);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $request->validate([
            "name" => "required",
            "country_code" => "required|min:1|max:265",
            "phone_number" => "required"
        ]);

        $contact = Contact::find($contact);

        $contact->update([
            "name" => $request->name,
            "country_code" => $request->country_code,
            "phone_number" => $request->phone_number
        ]);

        return new ContactDetailResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact = Contact::find($contact);
        $contact->delete();

        return response()->json([],204);
    }
}
