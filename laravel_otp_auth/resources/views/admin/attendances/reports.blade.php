@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Attendance Reports</h1>

    <!-- Report Filters -->
    <form action="{{ route('admin.attendances.reports') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="report_type" class="form-select">
                    <option value="daily" {{ $reportType == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $reportType == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $reportType == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Attendance Report Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Date</th>
                <th>Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->student->name }}</td>
                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($attendance->status) }}</td>
                    <td>{{ $attendance->reason ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection