@extends('layouts.master')

@section('title') Edit Student @endsection 

@section('content')

{!! Form::model($student, array('url' => 'students', 'role' => 'form', 'id'=>'student-form', 'files' => true)) !!}
	<div class="panel panel-default margin-top-20">
		<div class="panel-heading">
			<div class="panel-title">Edit Student</div>
		</div>
		<div class="panel-body">
			@include('students.form')
		</div>
		<div class="panel-footer">
			{!! Form::hidden('student_id', $student->id) !!}
			{!! Form::hidden('file_remove', 'false') !!}
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Update</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection



