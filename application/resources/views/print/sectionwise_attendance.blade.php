@extends('print.master')

@section('title') Sectionwise Attendance Report @endsection 

@section('content')

@if(!empty($from_date) && !empty($to_date))
<h3><strong>Sectionwise Attendance Report from {{ $from_date }} to {{ $to_date }}</strong></h3>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th width="7%">Class</th>
            <th width="7%">Section</th>
            <th width="10%">Total Students</th> 
            <th width="10%">Present Students</th>
            <th width="10%">Absent Students</th>
            <th width="10%">Attendance Date</th>
        </tr>
    </thead>
    <tbody>
    @forelse($attendances as $attendance)
        
        <tr>
            <td>{{ config('constants.classes.'.$attendance->class) }}</td>
            <td>{{ config('constants.sections.'.$attendance->section) }}</td>
            <td>{{ $section_total_student = $attendance->getSectionTotalStudent() }}</td>
            <td>{{ $attendance->total_student }}</td> 
            <td>{{ $section_total_student - $attendance->total_student }}</td> 
            <td>{{ Carbon::parse($attendance->date)->format('d M, Y') }}</td>                         
        </tr>

    @empty
        <tr>
            <td colspan="5" align="center">No Record Found!</td>
        </tr>
    @endforelse
    </tbody>
</table>

@endsection

@section('custom-style')

@endsection

