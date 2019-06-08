@extends('layouts.master')

@section('title') List of Attendance @endsection 

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default margin-top-20">
			<div class="panel-heading clearfix">
				List of Attendance
				<a class="btn btn-danger btn-xs pull-right" href="{!!url('attendances/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
			</div>

			<div class="panel-body">
				
				{!! Form::open(array('url' => url()->current(), 'role' => 'form', 'id'=>'attendance-search-form')) !!}
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								{!! Form::text('search_item', request()->search_item, ['class'=>'form-control', 'placeholder' => 'Enter Name or Roll']) !!}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								{!! Form::select('class', config('constants.classes'), request()->class, ['class'=>'form-control chosen-select', 'placeholder' => 'Select a Class']) !!}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								{!! Form::select('section', config('constants.sections'), request()->section, ['class'=>'form-control chosen-select', 'placeholder' => 'Select a section']) !!}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								{!! Form::text('date', request()->date, ['class'=>'form-control datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<button class="btn btn-info"> Search</button>
								<a href="{{ url()->current() }}" class="btn btn-default pull-right">Refresh</a>
							</div>
						</div>
					</div>
				{!! Form::close() !!}

				<div class="table-responsive">
					<table class="table table-striped table-bordered">
					    <thead>
					        <tr>
					        	<th>Name</th>
					            <th width="7%">Class</th>
					            <th width="7%">Section</th>
					            <th width="7%">Roll</th> 
					            <th width="6%">Status</th>
					            <th width="14%">Attendance Date</th>
					            <th width="10%">Actions</th>
					        </tr>
					    </thead>
					    <tbody>
					    @forelse($attendances as $attendance)
					        <tr>
					            <td>{{ $attendance->name }}</td>
					            <td>{{ config('constants.classes.'.$attendance->class) }}</td>
					            <td>{{ config('constants.sections.'.$attendance->section) }}</td>
					            <td>{{ $attendance->roll }}</td>
					            <td><span class="label label-{{ $attendance->attendance_status == 'present' ? 'success' : 'danger' }}">{{ ucfirst($attendance->attendance_status) }}</span></td> 
					            <td>{{ Carbon::parse($attendance->date)->format('d M, Y') }}</td>

					            <td class="action-column">

					                {{-- Edit --}}
					                <a class="btn btn-xs btn-default btn-edit" href="javascript:void(0);" data-id="{{ $attendance->id }}" title="Edit attendance"><i class="fa fa-pencil"></i></a>
					                
					                {{-- Delete --}}
									<a href="#" data-id="{{$attendance->id}}" data-action="{{ url('attendances/delete') }}" data-message="Are you sure, You want to delete this attendance?" class="btn btn-danger btn-xs alert-dialog" title="Delete attendance"><i class="fa fa-trash white"></i></a>
					            </td>
					        </tr>

					    @empty
					    	<tr>
					        	<td colspan="7" align="center">No Record Found!</td>
					        </tr>
					    @endforelse
					    </tbody>
					</table>
				</div>
			</div><!-- end panel-body -->

			@if($attendances->total() > 10)
			<div class="panel-footer">
				<div class="row">
					<div class="col-sm-4">
						{{ $attendances->paginationSummery }}
					</div>
					<div class="col-sm-8 text-right">
						{!! $attendances->links() !!}
					</div>
				</div>
			</div>
			@endif
		</div><!-- end panel panel-default -->
	</div>
</div>

{{-- Attendance Modal --}}
<div id="attendanceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
	
	{!! Form::open(['url' => '', 'role'=>'form','id'=>'attendance-form']) !!}
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Attendance</h4>
      </div>
      <div class="modal-body">
        <h4 id="student-name"></h4>
        <div class="details-info">
        	<span><strong>Class: </strong><span id="class"></span><br></span>
        	<span><strong>Section: </strong><span id="section"></span><br></span>
        	<span><strong>Roll: </strong><span id="roll"></span><br></span>
        	<span><strong>Attendance Date: </strong><span id="attendance-date"></span><br></span>
        </div>
        <!-- <div class="checkbox"> -->
			  <label class="checkbox-inline">{!! Form::checkbox('attendance_status', 'present') !!} Present</label>
			<!-- </div> -->
      </div>
      <div class="modal-footer">
      	{!! Form::hidden('attendance_id', '') !!}
      	<a href="javascript:updateAttendance();" class="btn btn-info">Update</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
	{!! Form::close() !!}
  </div>
</div>
{{-- /Attendance Modal --}}
@endsection

@section('custom-style')
{{-- Bootstrap Datepicker --}}
{!! Html::style($assets.'/plugins/datepicker/datepicker3.css') !!}
@endsection

@section('custom-script')
{{-- Bootstrap Datepicker --}}
{!! Html::script($assets. '/plugins/datepicker/bootstrap-datepicker.js') !!}
<script>
function updateAttendance()
{
	$('#ajaxloader').removeClass('hide');
	// var id = $('#attendanceModal input[name=attendance_id]').val();
	$.ajax({
		url:'{{ url('attendances/update') }}',
		method:'POST',
		data:$('#attendance-form').serialize(),
		success:function(response) {
			toastMsg(response.message, response.type);

			if(response.type == 'success') {
				setTimeout(function() {
					var btnEdit = $('.btn-edit[data-id='+response.attendance_id+']');

					var label = btnEdit.closest('tr').find('td:eq(4) .label');
					console.log(label);
					var attendanceStatus = response.attendance_status;

					if(attendanceStatus == 'Present') {
						if(label.hasClass('label-danger')) {
							label.removeClass('label-danger').addClass('label-success').text(attendanceStatus);
						}
					}else {
						if(label.hasClass('label-success')) {
							label.removeClass('label-success').addClass('label-danger').text(attendanceStatus);
						}
					}
					$('#attendanceModal').modal('hide');
				}, 1000);
			}
		},
		complete:function() {
			$('#ajaxloader').addClass('hide');
		},
		error:function(xhr, ajaxOptions, thrownError) {
			alert(xhr.status + ' ' +thrownError);
		}
	});
}

(function() {
    $('.btn-edit').on('click', function() {
    	var id = $(this).attr('data-id');
    	var tableRow = $(this).closest('tr');
    	$('#class').text(tableRow.find('td:eq(1)').text());
    	$('#section').text(tableRow.find('td:eq(2)').text());
    	$('#roll').text(tableRow.find('td:eq(3)').text());
    	$('#attendance-date').text(tableRow.find('td:eq(5)').text());
    	var attendanceStatus = tableRow.find('td:eq(4) .label').text();

    	if(attendanceStatus == 'Present') {
    		$('#attendanceModal input[name=attendance_status]').prop('checked', true);
    	}else {
    		$('#attendanceModal input[name=attendance_status]').prop('checked', false);
    	}
    	$('#attendanceModal input[name=attendance_id]').val(id);
    	$('#attendanceModal').modal();
    });
})();
</script>
@endsection