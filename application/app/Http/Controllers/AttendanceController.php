<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;

use App\Student;
use DB, Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = DB::table('attendances as a')->leftJoin('students as s', 'a.student_id', '=', 's.id')
            ->select('a.id', 'a.attendance_status', 'a.date', 's.name', 's.class', 's.section','s.roll', 's.gender');

        $data = [];

        if($request->filled('search_item')) {
            $query->where(function($query) use($request) {
                $query->where('name', 'LIKE', '%'.trim($request->search_item) .'%')
                ->orWhere('roll', trim($request->search_item));
            });

            $data['search_item'] = trim($request->search_item);
        }
                
        if($request->filled('class')) {
            $query->where('s.class', trim($request->class));
            $data['class'] = trim($request->class);
        }

        if($request->filled('section')) {
            $query->where('s.section', trim($request->section));
            $data['section'] = trim($request->section);
        }

        if($request->filled('date')) {
            $query->where('a.date', trim($request->date));
            $data['date'] = trim($request->date);
        }

        $attendances = $query->latest('a.date')
                        ->orderBy('s.class')
                        ->orderBy('s.section')
                        ->orderBy('s.roll')->paginate();

        $attendances->paginationSummery = getPaginationSummery($attendances->total(), $attendances->perPage(), $attendances->currentPage());

        if($data) {
            $attendances->appends($data);
        }

        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->isMethod('POST')) {
            
            $students = Student::where(function($query) use($request) {

                if($request->filled('class')) {
                    $query->whereClass(trim($request->class));
                }

                if($request->filled('section')) {
                    $query->whereSection(trim($request->section));
                }

            })->orderBy('name')->get();

            return view('attendances.create', compact('students'));

        }

        return view('attendances.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->has('student_id')) {
            session()->flash('toast', toastMessage('The student id not found!'));

            return back();
        }

        foreach($request->student_id as $key=>$value) {
            $rules['student_id.'.$key] = 'required|integer|min:1';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            $messages = $validator->messages()->all();
            $validation_error = '';
            
            foreach($messages as $value) {
                $validation_error .= $value .'<br/>';
            }

            $message = toastMessage($validation_error, 'error');
            session()->flash('toast', $message);

            return back();

        }else {
            // dd($request->all());
            // if already file exists for todays, delete these data at first
            Attendance::where('date', date('Y-m-d'))
            ->where(function($query) use($request) {
                
                if($request->filled('class')) {
                    $query->whereClass($request->class);
                }
                
                if($request->filled('section')) {
                    $query->whereSection($request->section);
                }

            })->delete();

            // insert 
            $i = 0;
            foreach($request->student_id as $key=>$value) {
                $attendance = new Attendance;
                $student = Student::find($value);

                if(!$student) {
                    session()->flash('toast', toastMessage('Student not found for id '. $value , 'error'));

                    return back();
                }
                
                $attendance->student_id = $student->id;
                $attendance->class = $student->class;
                $attendance->section = $student->section;
                $attendance->attendance_status = !empty($request->attendance_status[$key]) ? 'present':'absent';
                $attendance->date = date('Y-m-d');

                if($attendance->save()) {
                    $i++;
                }
            }

            if($i) {
                $message = toastMessage('attendance information has been successfully added.');
            }else {
                $message = toastMessage('attendance information has not been added.', 'error');
            }

            session()->flash('toast', $message);

            return redirect('attendances/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = ['attendance_id' => 'required|integer'];
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'type'  => 'error',
                'message'   => $validator->errors()->first('attendance_id'),
            ]);
        }

        // update
        $attendance = Attendance::find(trim($request->attendance_id));

        if(!$attendance) {
            return resonponse()->json([
                'type'  => 'error',
                'message'=> 'Attendance not found!'
            ]);
        }

        $attendance->attendance_status = $request->has('attendance_status') ? 'present' : 'absent';

        if($attendance->save()) {
            return response()->json([
                'type'  => 'success',
                'message'=> 'Attendance has been successfully updated!',
                'attendance_id' => $attendance->id,
                'attendance_status' => ucfirst($attendance->attendance_status),
            ]);
        }else {
            return response()->json([
                'type'  => 'error',
                'message'=> 'Attendance has not been updated!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Attendance::destroy($request->hdnResource)){
            $message = toastMessage('Attendance has been successfully removed.');
        }else{
            $message = toastMessage('Attendance has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);

        return back();
    }
}
