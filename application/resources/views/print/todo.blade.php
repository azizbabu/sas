@extends('prints.master')

@section('title') Todo Report @endsection 

@section('content')
@if(!empty($from_date) && !empty($to_date))
<h3><strong>Todo Report from {{ $from_date }} to {{ $to_date }}</strong></h3>
@endif

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center" width="7%">Number</th>
                <th>Task</th>
                @if(Auth::user()->employee->servants->isNotEmpty())
                    <th width="15%">Department</th>
                    <th width="15%">Assigned To</th>
                @endif
                <th width="9%">Achievement</th>
                <th class="text-center" width="7%">Status</th>
                <th width="16%">Assigned at</th>
                <th width="16%">Completed at</th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($todo_lists))
        @php $i=1 @endphp
        @forelse($todo_lists as $todo_list)
            <tr>
                <td class="text-center">@php echo '#' . $i++ @endphp</td>
                <td>{{ $todo_list->title }}</td>
                @if(Auth::user()->employee->servants->isNotEmpty())
                <td>{{ $todo_list->department_title }}</td>
                <td>{{ $todo_list->full_name }}</td>
                @endif
                <td class="text-center">{{ $todo_list->achievement }}</td>
                <td class="text-center">{{ ucfirst($todo_list->status) }}</td>
                <td>{{ $todo_list->assigned_at }}</td>
                <td>{{ $todo_list->finished_at ? $todo_list->finished_at->format('d M, Y H:i A') : 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" align="center">No Data Found!</td>
            </tr>
        @endforelse
        @else
			<tr>
                <td colspan="8" align="center">No Data Found!</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection

@section('custom-style')

@endsection

@section('custom-script')

<script>
(function() {
	window.print();
})();
</script>
@endsection
