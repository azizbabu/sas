<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
    * Get Student photo
    */
    public function getPhoto()
    {
    	if(@getimagesize(url($this->photo))) {
    		$photo = url($this->photo);
    	}else {
    		$photo = url('assets/images/avatar/dummy_profpic.jpg');
    	}

    	return $photo;
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
