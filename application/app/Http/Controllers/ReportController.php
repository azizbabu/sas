<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 300000);

use Illuminate\Http\Request;
use App\Attendance;
use App\Student;
use DB, PDF;

class ReportController extends Controller
{
    /**
     * Display a report on student attendance
     *
     * @param \Illuminate\Http\Request $request.
     * @param string|null $view
     * @return \Illuminate\Http\Response
     */
    public function getDailyAttendance(Request $request, $view = null)
    {
    	$query = DB::table('attendances as a')->leftJoin('students as s', 'a.student_id', '=', 's.id')
            ->select('a.id', 'a.attendance_status', 'a.date', 's.name', 's.class', 's.section','s.roll', 's.gender');

        if($request->filled('search_item')) {
            $query->where(function($query) use($request) {
                $query->where('name', 'LIKE', '%'.trim($request->search_item) .'%')
                ->orWhere('roll', trim($request->search_item));
            });
        }
                
        if($request->filled('class')) {
            $query->where('s.class', trim($request->class));
        }

        if($request->filled('section')) {
            $query->where('s.section', trim($request->section));
        }

        if($request->has('date_range')) {
            $date_range  = explode(' - ',$request->date_range);
            $from_date = \Carbon::parse($date_range[0])->format('Y-m-d');
            $to_date = \Carbon::parse($date_range[1])->format('Y-m-d');
            $query->whereRaw('a.date >= "'.$from_date.'" AND a.date <="'.$to_date.'"');
        }else {
            $from_date = date('Y-m-d', strtotime('-2 days'));
            $to_date = date('Y-m-d');
            $query->whereRaw('a.date >= "'.$from_date.'" AND a.date <="'.$to_date.'"');
        }

        $attendances = $query->latest('a.date')
                        ->orderBy('s.class')
                        ->orderBy('s.section')
                        ->orderBy('s.roll')->get();

        if($view == 'pdf') {
            $file_name = 'daily-attendance-report-'.date('Y-m-d').'.pdf';

            return PDF::loadView('pdf.daily_attendance', compact('attendances', 'from_date', 'to_date'))->stream($file_name);
            
        }elseif($view == 'print') {
            
            return view('print.daily_attendance', compact('attendances', 'from_date', 'to_date'));
        }

        return view('reports.daily_attendance', compact('attendances', 'from_date', 'to_date'));
    }

    /**
     * Display classwise attendace report
     *
     * @param \Illuminate\Http\Request $request.
     * @param string|null $view
     * @return \Illuminate\Http\Response
     */
    public function getSectionWiseAttendance(Request $request, $view = null)
    {
        $query = Attendance::leftJoin('students as s', 'attendances.student_id', '=', 's.id')
            ->select(
                'attendances.id',
                's.class', 
                's.section',
                DB::raw('count(s.id) as total_student'),
                'attendances.date'
            );
                
        if($request->filled('class')) {
            $query->where('s.class', trim($request->class));
        }

        if($request->filled('section')) {
            $query->where('s.section', trim($request->section));
        }

        if($request->has('date_range')) {
            $date_range  = explode(' - ',$request->date_range);
            $from_date = \Carbon::parse($date_range[0])->format('Y-m-d');
            $to_date = \Carbon::parse($date_range[1])->format('Y-m-d');
            $query->whereRaw('attendances.date >= "'.$from_date.'" AND attendances.date <="'.$to_date.'"');
        }else {
            $from_date = date('Y-m-d', strtotime('-7 days'));
            $to_date = date('Y-m-d');
            $query->whereRaw('attendances.date >= "'.$from_date.'" AND attendances.date <="'.$to_date.'"');
        }
        $query->where('attendances.attendance_status', 'present');

        // $query->where('attendances.attendance_status', 'present');
        // $query->where('s.class', 1);
        // $query->where('s.section', 'a');

        $attendances = $query->groupBy('attendances.date', 's.class' ,'s.section')
                        ->latest('attendances.date')
                        ->orderBy('s.class')
                        ->orderBy('s.section')
                        ->orderBy('s.roll')
                        ->get();

        if($view == 'pdf') {
            $file_name = 'sectionwise-attendance-report-'.date('Y-m-d').'.pdf';

            return PDF::loadView('pdf.sectionwise_attendance', compact('attendances', 'from_date', 'to_date'))->stream($file_name);
            
        }elseif($view == 'print') {
            
            return view('print.sectionwise_attendance', compact('attendances', 'from_date', 'to_date'));
        }

        return view('reports.sectionwise_attendance', compact('attendances', 'from_date', 'to_date'));
    }
}
