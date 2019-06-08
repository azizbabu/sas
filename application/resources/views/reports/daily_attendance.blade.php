@extends('layouts.master')

@section('title') Report of Daily Attendance @endsection 

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default margin-top-20">
			<div class="panel-heading">
				Report of Daily Attendance
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
						<div class="col-md-3">
							<div class="form-group">
								<div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
        					    	<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        					    	<span></span> <b class="caret"></b>
	        					</div>
	        					{!! Form::hidden('date_range', Request::input('date_range')) !!}
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<button class="btn btn-info" data-toggle="tooltip" title="Generate Report"> <i class="fa fa-search" aria-hidden="true"></i></button>
								<a href="{{ url()->current() }}" class="btn btn-default pull-right" data-toggle="tooltip" title="Refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a>
							</div>
						</div>
					</div>
				{!! Form::close() !!}

				@if(!empty($from_date) && !empty($to_date))
        		<h3><strong>Daily Attendance Report from {{ $from_date }} to {{ $to_date }}</strong></h3>
        		@endif
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

			@if($attendances->isNotEmpty())
			<div class="panel-footer">
				<div class="btn-group margin-top-20">
		        	<a href="{{ url('reports/daily-attendance/print').(count(request()->all()) ? '?search_item='.request()->search_item.'&class='.request()->class.'&section='.request()->section.'&date_range='.request()->date_range : '') }}" class="btn btn-primary" target="_blank"><i class="fa fa-print" aria-hdden="true"></i> Print</a>
		            <a href="{{ url('reports/daily-attendance/pdf').(count(request()->all()) ? '?search_item='.request()->search_item.'&class='.request()->class.'&section='.request()->section.'&date_range='.request()->date_range : '') }}" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
		        </div>
			</div>
			@endif
		</div><!-- end panel panel-default -->
	</div>
</div>

@endsection

@section('custom-style')
{{-- Daterange Picker --}}
{!! Html::style($assets . '/plugins/daterangepicker/daterangepicker-bs3.css') !!}
@endsection

@section('custom-script')
{{-- Date rangepicker --}}
{!! Html::script($assets . '/plugins/daterangepicker/moment.min.js') !!}
{!! Html::script($assets . '/plugins/daterangepicker/daterangepicker.js') !!}
<script>
(function() {
    @if(!empty($from_date) && !empty($to_date))
	var start = moment('{{ $from_date }}');
	var end = moment('{{ $to_date }}');
	@else
	var start = moment().subtract(2, 'days');
    var end = moment();
    @endif

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('input[name=date_range]').val(start.format('YYYY-MM-DD')+ ' - ' + end.format('YYYY-MM-DD'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
})();
</script>
@endsection