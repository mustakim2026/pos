<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getMonthlyReport(Request $request)
    {
        try {
            $month = $request->query('month'); 

            $reportData = DB::table('daily_productions as dp')
                ->join('efficiencies as ef', 'dp.efficiency_id', '=', 'ef.id')
                ->leftJoin('daily_attendances as da', function($join) {
                    $join->on('dp.efficiency_id', '=', 'da.efficiency_id')
                         ->on('dp.line_no', '=', 'da.line_no');
                })
                ->select(
                    'ef.unit_name',
                    'dp.line_no',
                    DB::raw('SUM(dp.target) as target'),
                    DB::raw('SUM(dp.output) as actual'),
                    // Attendance টেবিল থেকে OP1 এবং HP1 এর গড় নেয়া হয়েছে
                    DB::raw('ROUND(SUM(da.OP1), 0) as op'), 
                    DB::raw('ROUND(SUM(da.HP1), 0) as hp'),
                    DB::raw('COUNT(DISTINCT ef.report_date) as working_days')
                )
                ->where('ef.report_date', 'like', $month . '%')
                ->groupBy('ef.unit_name', 'dp.line_no')
                ->orderBy('ef.unit_name')
                ->get();

            return response()->json($reportData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
