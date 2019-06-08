<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function student()
    {
    	return $this->belongsTo(Student::class);
    }

    /**
    * Get Total students of a specific section of spcefic class
    */
    public function getSectionTotalStudent()
    {
        $total_students = Student::whereClass($this->class)
                    ->whereSection($this->section)->count();

        return $total_students;
    }
}
