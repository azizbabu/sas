@extends('pdf.master')

@section('title') Daily Attendance Report @endsection 

@section('content')

@if(!empty($from_date) && !empty($to_date))
<h3><strong>Daily Attendance Report from {{ $from_date }} to {{ $to_date }}</strong></h3>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th width="9%">Class</th>
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
            <td colspan="6" align="center">No Record Found!</td>
        </tr>
    @endforelse
    </tbody>
</table>

@endsection

@section('custom-style')

@endsection

