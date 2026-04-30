<?php

namespace App\Http\Controllers;

use App\Models\Efficiency;
use App\Models\DailyProduction;
use App\Models\DailyAttendance;
use App\Models\Smv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EfficiencyController extends Controller 
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
    $request->validate([
        'report_date' => 'required|date',
        'unit_name' => 'required|string',
        'observer' => 'required|string',
        'outputRows' => 'required|array',
    ]);

    try {
        return DB::transaction(function () use ($request) {
            $efficiency = Efficiency::create([
                'report_date' => $request->report_date,
                'unit_name'   => $request->unit_name,
                'observer'    => $request->observer,
            ]);

            foreach ($request->outputRows as $row) {
                if (!empty($row['line_no'])) {
                    
                    // 1. FETCH SMV from the 'smvs' reference table
                    $smvValue = Smv::where([
                        ['po_no', '=', $row['po_no']],
                        ['style_model_no', '=', $row['style_model_no']],
                        ['item', '=', $row['item']],
                    ])->value('smv') ?? 0;

                    // 2. CALCULATE Produce Minutes (smv * output)
                    $output = $row['output'] ?? 0;
                    $produceMinutes = $smvValue * $output;

                    // 3. Save Production with SMV and Minutes
                    DailyProduction::create([
                        'efficiency_id'   => $efficiency->id,
                        'line_no'         => $row['line_no'],
                        'po_no'           => $row['po_no'],
                        'style_model_no'  => $row['style_model_no'],
                        'item'            => $row['item'] ?? '',
                        'smv'             => $smvValue,        // Saved SMV
                        'target'          => $row['target'] ?? 0,
                        'output'          => $output,
                        'produce_minutes' => $produceMinutes, // Calculated Value
                    ]);

                    //  Attendance Calculation 
                        $OP1      = $row['OP1'] ?? 0; // Use '=' not '=>'
                        $Op_ad_m1 = $OP1 * 5 * 60;
                        $OP2      = $row['OP2'] ?? 0;
                        $Op_ad_m2 = $OP2 * 5 * 60;
                        $OP3      = $row['OP3'] ?? 0;
                        $Op_ad_m3 = $OP3 * 5 * 60;
                        $OP4      = $row['OP4'] ?? 0;
                        $Op_ad_m4 = $OP4 * 5 * 60;
                        $HP1 = $row['HP1'] ?? 0;
                        $hp_ad_m1=$HP1*5*60;

                    DailyAttendance::create([
                        'efficiency_id'  => $efficiency->id,
                        'line_no'        => $row['line_no'],
                        'po_no'          => $row['po_no'],
                        'style_model_no' => $row['style_model_no'],
                        'item'           => $row['item'] ?? '',
                        'allocated_Hrs'  => $row['allocated_Hrs'] ?? 0,
                        'OP1'            => $OP1, 
                        'Op_ad_m1'       =>$Op_ad_m1,
                        'HP1'            => $HP1,
                        'hp_ad_m1'       =>$hp_ad_m1,
                        'OP2' => $row['OP2'] ?? 0, 
                        'HP2' => $row['HP2'] ?? 0,
                        'OP3' => $row['OP3'] ?? 0, 'HP3' => $row['HP3'] ?? 0,
                        'OP4' => $row['OP4'] ?? 0, 'HP4' => $row['HP4'] ?? 0,
                        'OP5' => $row['OP5'] ?? 0, 'HP5' => $row['HP5'] ?? 0,
                        'OP6' => $row['OP6'] ?? 0, 'HP6' => $row['HP6'] ?? 0,
                        'OP7' => $row['OP7'] ?? 0, 'HP7' => $row['HP7'] ?? 0,
                        'OP8' => $row['OP8'] ?? 0, 'HP8' => $row['HP8'] ?? 0,
                        'w_hrs' => $row['w_hrs'] ?? 0,
                    ]);
                }
            }
            return response()->json(['message' => 'Success'], 201);
        });
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




    /**
     * Display the specified resource.
     */ 
    public function show(Efficiency $efficiency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Efficiency $efficiency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Efficiency $efficiency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Efficiency $efficiency)
    {
        //
    }
}
