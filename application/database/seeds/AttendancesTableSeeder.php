<?php

use Illuminate\Database\Seeder;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = \App\Student::select('id', 'class', 'section')->get();

        if($students->isNotEmpty()) {

            \App\Attendance::truncate();
        	foreach($students as $student) {
        		$day_no = 30;
        		while($day_no) {
        			$attendance = new \App\Attendance;
	        		$attendance->student_id = $student->id;
	                $attendance->class = $student->class;
	                $attendance->section = $student->section;
	                $attendance->attendance_status = array_rand(config('constants.attendance_status'));
	                $attendance->date = \Carbon::now()->subDays($day_no)->format('Y-m-d');
	                $attendance->save();
        			$day_no--;
        		}
        	}
        }
    }
}
