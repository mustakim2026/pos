<?php

namespace App\Http\Controllers;

use App\Models\Smv;
use Illuminate\Http\Request;

class SmvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    try {
        foreach ($request->smvRows as $row) {
            // Skip the row if po_no is empty
            if (empty($row['po_no'])) continue; 

            Smv::create($row); 
        }
        return response()->json(['message' => 'Success'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Smv  $smv)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Smv $smv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Smv  $smv)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Smv  $smv)
    {
        //
    }
}
