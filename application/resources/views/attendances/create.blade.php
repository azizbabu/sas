@extends('layouts.master')

@section('title') Entry Attendance @endsection 

@section('content')

<div class="panel panel-default margin-top-20">
	<div class="panel-heading">
		<div class="panel-title">Entry Attendance for Today</div>
	</div>
	<div class="panel-body">
		<div class="text-danger">* Select class and section to get students and check checkbox to keep record of student's attendance</div>
		{!! Form::open(array('url' => url()->current(), 'role' => 'form', 'id'=>'student-form', 'class' => 'margin-top-20')) !!}
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::select('class', config('constants.classes'), null, ['class'=>'form-control chosen-select', 'placeholder' => 'Select a Class']) !!}
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::select('section', config('constants.sections'), null, ['class'=>'form-control chosen-select', 'placeholder' => 'Select a section']) !!}
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<button class="btn btn-default pull-right"> Get Students</button>
					</div>
				</div>
			</div>
		{!! Form::close() !!}

		@if(!empty($students) && $students->isNotEmpty())
			
			<div class="text-danger">Please check the checkboxes if students are present in the class today i.e <strong>{{ date('d M, Y') }}</strong></div>
			
			{!! Form::open(['url' => 'attendances', 'role'=>'form', 'id' => 'attendance-form', 'class' => 'margin-top-20']) !!}
				
				@php 
					$i = 0; 
					$total_students = $students->count()
				@endphp
				
				@foreach($students as $student)

					@if($i == 0 || $i%2 == 0)
						<div class="row margin-top-20">
					@endif

					<div class="col-md-6">
						<div class="media">
						  <div class="media-left">
						    <img src="{{ $student->getPhoto() }}" class="media-object" style="width:60px">
						  </div>
						  <div class="media-body">
						    <h4 class="media-heading">{{ $student->name }}</h4>
						    <div class="row">
						    	<div class="col-sm-8">
						    		<p>
						    			<strong>Class:</strong>{{ config('constants.classes.'.$student->class) }}
						    			<strong>Section:</strong>{{ config('constants.sections.'.$student->section) }}  <br>
						    			<strong>Roll:</strong>{{ $student->roll }} <strong>Gender:</strong>{{ ucfirst($student->gender) }} 
						    		</p>
						    		{!! Form::hidden('student_id['.$i.']', $student->id) !!}
						    	</div>
						    	<div class="col-sm-4">
						    		<div class="checkbox">
									  <label>{!! Form::checkbox('attendance_status['.$i.']', 'present') !!} Present</label>
									</div>
						    	</div>
						    </div>
						  </div>
						</div>
					</div>

					@php $i++ @endphp

					@if($i == $total_students || $i%2 == 0)
						</div>
					@endif
					
				@endforeach
				
				{!! Form::hidden('class', request()->class) !!}
				{!! Form::hidden('section', request()->section) !!}
			{!! Form::close() !!}

		@endif
	</div>

	@if(!empty($students) && $students->isNotEmpty())
	<div class="panel-footer">
		<button class="btn btn-info" onclick="$('#attendance-form').submit();"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
	</div>
	@endif

</div>		

@endsection

@section('custom-style')
{{-- Bootstrap Datepicker --}}
{!! Html::style($assets.'/plugins/datepicker/datepicker3.css') !!}
@endsection

@section('custom-script')
{{-- Bootstrap Datepicker --}}
{!! Html::script($assets. '/plugins/datepicker/bootstrap-datepicker.js') !!}
<script>
(function() {
    
})();
</script>
@endsection


