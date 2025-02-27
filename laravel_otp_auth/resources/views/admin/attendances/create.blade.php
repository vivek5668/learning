@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mark Attendance</h1>
    <form action="{{ route('admin.attendances.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Reason for Absence</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>
                            <select name="attendances[{{ $student->id }}][status]" class="form-select">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="attendances[{{ $student->id }}][reason]" class="form-control" placeholder="Reason (if absent)">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection