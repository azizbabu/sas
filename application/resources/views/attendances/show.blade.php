@extends('layouts.master')

@section('title') Student Details @endsection 

@section('content')
<div id="panel-1" class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-title">Student Details</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
		        <div class="details-info">
		        	<h4><strong>Name: </strong>{{ $student->name }}</h4>
		        	<strong>Father's Name:</strong> {{ $student->father_name ? $student->father_name : 'N/A' }} <br>
		        	<strong>Mother's Name:</strong> {{ $student->mother_name ? $student->father_name : 'N/A' }} <br>
		        	<strong>Class:</strong> {{ config('constants.classes.'.$student->class) }} <br>
		        	<strong>Section:</strong> {{ config('constants.sections.'.$student->section) }} <br>
		        	<strong>Roll:</strong> {{ $student->roll }} <br>
		        	<strong>Gender:</strong> {{ ucfirst($student->gender)  }} <br>
		        	<strong>Admission Date:</strong> {{ Carbon::parse($student->admission_date)->format('d M, Y') }} <br>
		        	
		        	@if(@getimagesize(url($student->photo)))
		        		<strong>Photo:</strong> <img src="{{ url($student->photo) }}" alt="Student Photo">
		        	@endif
		        </div>
			</div>
		</div>	
	</div>
	<div class="panel-footer">
		<a class="btn btn-default btn-sm" href="{{ URL::to('tasks/' . $student->id . '/edit') }}" title="Edit Employee"><i class="fa fa-pencil"></i> Edit</a>
		<a class="btn btn-info btn-sm" href="{{ URL::to('tasks') }}" title="Go Back"><i class="fa fa-backward"></i> Go Back</a>
	</div>
</div>

@endsection


