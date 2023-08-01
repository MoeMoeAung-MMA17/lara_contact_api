<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\SearchRecord;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    //     $contacts = Contact::latest("id")->paginate(5)->withQueryString();
        // return ContactResource::collection($contacts);
    // }
    

    $contacts = Contact::when(request()->has("keyword"), function ($query) {
        $query->where(function (Builder $builder) {
            $keyword = request()->keyword;

            $builder->where("name", "like", "%" . $keyword . "%");
            
        });
    })
        ->when(request()->has('show') == "trash",fn($query) => $query->withTrashed() )

        
        // ->dd()
        ->latest("id")
        ->paginate(7)->withQueryString();

        return ContactResource::collection($contacts);

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SearchRecord $searchRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SearchRecord $searchRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SearchRecord $searchRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SearchRecord $searchRecord)
    {
        //
    }
}
