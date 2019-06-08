@extends('layouts.master')

@section('title') Create Student @endsection 

@section('content')

{!! Form::open(array('url' => 'students', 'role' => 'form', 'id'=>'student-form', 'files' => true)) !!}
	<div class="panel panel-default margin-top-20">
		<div class="panel-heading">
			<div class="panel-title">Create Student</div>
		</div>
		<div class="panel-body">
			@include('students.form')
		</div>
		<div class="panel-footer">
			<button class="btn btn-info"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
		</div>
	</div>		
{!! Form::close() !!}

@endsection


