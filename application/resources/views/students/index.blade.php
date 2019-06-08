@extends('layouts.master')

@section('title') List of Students @endsection 

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default margin-top-20">
			<div class="panel-heading clearfix">
				List of Students
				<a class="btn btn-danger btn-xs pull-right" href="{!!url('students/create')!!}"><i class="fa fa-plus-circle"></i> Add New</a>
			</div>

			<div class="panel-body">

				{!! Form::open(array('url' => url()->current(), 'role' => 'form', 'id'=>'attendance-search-form')) !!}
					<div class="row">
						<div class="col-md-5">
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
					            <th width="6%">Gender</th>
					            <th width="12%">Admission Date</th>
					            <th width="10%">Actions</th>
					        </tr>
					    </thead>
					    <tbody>
					    @forelse($students as $student)
					        <tr>
					            <td>{{ $student->name }}</td>
					            <td>{{ config('constants.classes.'.$student->class) }}</td>
					            <td>{{ config('constants.sections.'.$student->section) }}</td>
					            <td>{{ $student->roll }}</td>
					            <td>{{ ucfirst($student->gender) }}</td> 
					            <td>{{ Carbon::parse($student->admission_date)->format('d M, Y') }}</td>

					            <td class="action-column">
					                {{-- Show --}}
					                <a class="btn btn-xs btn-success" href="{{ URL::to('students/' . $student->id) }}" title="View student"><i class="fa fa-eye"></i></a>

					                {{-- Edit --}}
					                <a class="btn btn-xs btn-default" href="{{ URL::to('students/' . $student->id . '/edit') }}" title="Edit student"><i class="fa fa-pencil"></i></a>
					                
					                {{-- Delete --}}
									<a href="#" data-id="{{$student->id}}" data-action="{{ url('students/delete') }}" data-message="Are you sure, You want to delete this student?" class="btn btn-danger btn-xs alert-dialog" title="Delete student"><i class="fa fa-trash white"></i></a>
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

			@if($students->total() > 15)
			<div class="panel-footer">
				<div class="row">
					<div class="col-sm-4">
						{{ $students->paginationSummery }}
					</div>
					<div class="col-sm-8 text-right">
						{!! $students->links() !!}
					</div>
				</div>
			</div>
			@endif
		</div><!-- end panel panel-default -->
	</div>
</div>
@endsection