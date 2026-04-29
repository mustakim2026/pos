<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $units = Unit::all(); // Fetches all data from the observers table
         return response()->json($units);
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
    $request->validate([
        'report_date' => 'required|date',
        'unit_name'   => 'required|string',
        'outputRows'  => 'required|array', // Must match Vue's formData.outputRows
    ]);

    try {
        return DB::transaction(function () use ($request) {
            // 1. Create the Unit
            $unit = Unit::create([
                'report_date' => $request->report_date,
                'unit_name'   => $request->unit_name,
            ]);

            // 2. Loop through rows (matching Vue property name)
            foreach ($request->outputRows as $row) {
                if (!empty($row['line_no'])) {
                    Line::create([
                        'unit_id' => $unit->id, // Corrected variable
                        'line_no' => $row['line_no'],
                        'remarks' => $row['remarks'] ?? null,
                    ]);
                }
            }
            
            return response()->json(['message' => 'Success'], 201);
        });
    } catch (\Exception $e) {
        // Return the actual error message to help us debug
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        //
    }
}
