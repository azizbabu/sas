<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;

use App\Helpers\Classes\Uploader;
use App\Http\Resources\Student as StudentResource;
use ReCaptcha\ReCaptcha;
use Carbon, Validator;

class StudentController extends Controller
{
    /**
    * For User Access control
    */
    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store', 'view']);
        $this->middleware('admin')->except(['create', 'store', 'view']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $query = Student::query();

        $data = [];

        if($request->filled('search_item')) {
            
            $query->where(function($query) use($request) {
                $query->where('name', 'LIKE', '%'.trim($request->search_item) .'%')
                ->orWhere('roll', trim($request->search_item));
            });
            
            $data['search_item'] = trim($request->search_item);
        }
                
        if($request->filled('class')) {
            $query->where('class', trim($request->class));
            $data['class'] = trim($request->class);
        }

        if($request->filled('section')) {
            $query->where('section', trim($request->section));
            $data['section'] = trim($request->section);
        }

        $students =  $query->orderBy('class')
                    ->orderBy('section')
                    ->orderBy('roll')->paginate();
        $students->paginationSummery = getPaginationSummery($students->total(), $students->perPage(), $students->currentPage());

        if($data) {
            $students->appends($data);
        }

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [];
        $rules = [
            'name'  => 'required|string|max:255',
            'class'  => 'required|integer',
            'section'  => 'required|string|max:10',
            'roll'  => 'required|integer',
        ];

        if($request->filled('admission_date')) {
            $rules['admission_date'] = 'date|date_format:Y-m-d';
        }

        if($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:jpg,jpeg,png|max:1024';
        }

        // if(!$request->has('student_id')) {
        //     $rules['g-recaptcha-response'] = 'required';
        //     $messages = [
        //         'g-recaptcha-response.required' => 'Please fill up the recaptcha',
        //     ];
        // }

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }else {
            
            $student = Student::whereClass(trim($request->class))->whereSection(trim($request->section))->whereRoll(trim($request->roll))->first(['id']);

            if($student) {
                session()->flash('toast', toastMessage('Student with the roll ('.trim($request->roll).') already exist!', 'error'));

                return back()->withInput();
            }

            // insert or update
            $student = !$request->has('student_id') ? new Student : Student::findOrFail($request->student_id);
            $student->name = trim($request->name);
            $student->father_name = trim($request->father_name);
            $student->mother_name = trim($request->mother_name);
            $student->class = trim($request->class);
            $student->section = trim($request->section);
            $student->roll = trim($request->roll);
            $student->gender = trim($request->gender);
            $student->admission_date = $request->filled('admission_date') ? trim($request->admission_date) : NULL;

            /**
            * Upload Photo
            */
            $upload_folder = 'uploads/students/';
            // check whether folder already exist if not, create folder

            $photo_path = $student->photo;
            
            // Delete photo from upload folder && database if remove button is pressed and do not upload photo
            if(!empty($photo_path) && $request->file_remove == 'true' && !$request->hasFile('photo')){

                if(Uploader::delete($photo_path)) {
                    $photo_path = null;
                }
            }

            if($request->hasFile('photo')) {

                // check if photo already exists in database
                if(!empty($photo_path)) {
                    if(Uploader::delete($photo_path)) {
                        $photo_path = null;
                    }
                }

                if($path = Uploader::upload($request->file('photo'), $upload_folder)) {
                    $photo_path = $path;
                }
            }

            $student->photo = $photo_path;

            if(!$request->has('student_id')) {
                $student->created_by = $request->user()->id;
                $msg = 'added.';
            }else {
                $student->updated_by = $request->user()->id;
                $msg = 'updated.';
            }

            if($student->save()) {
                $message = toastMessage('Student has been successfully '. $msg);
            }else {
                $message = toastMessage('Student has not been '. $msg, 'error');
            }

            session()->flash('toast', $message);

            return redirect('students/list');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if(Student::destroy($request->hdnResource)){
            $message = toastMessage('Student has been successfully removed.');
        }else{
            $message = toastMessage('Student has not been removed.','error');
        }

        // Redirect
        session()->flash('toast',$message);

        return back();
    }

    /**
     * Display student info in json format
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        return new StudentResource(Student::find($id));
    }
}
