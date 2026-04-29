<?php

namespace App\Http\Controllers;

use App\Models\Observer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObserverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $observers = Observer::all(); // Fetches all data from the observers table
         return response()->json($observers);
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
        'outputRows' => 'required|array',
        'outputRows.*.observer_name' => 'nullable|string', // Validates each row
    ]);

    try {
        // Use a transaction to ensure all or nothing is saved
        \DB::transaction(function () use ($request) {
            foreach ($request->outputRows as $row) {
                // Check for observer_name since line_no wasn't in your Vue code
                if (!empty($row['observer_name'])) {
                    Observer::create([
                        'observer_name' => $row['observer_name'],
                        'remarks'       => $row['remarks'] ?? null,
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Success'], 201);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Observer $observer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Observer $observer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Observer $observer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Observer $observer)
    {
        //
    }
}
