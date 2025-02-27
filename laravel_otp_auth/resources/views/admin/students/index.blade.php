@extends('layouts.app')

@section('content')
<div class="container">
    
    <h1>Students</h1>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary mb-3">Add Student</a>

<div style="display: inline">

    {{-- <a href="{{ route('admin.students.send-email-all') }}" class="btn btn-sm btn-success">Send Email</a> --}}


</div>
{{-- <form action="{{ route('admin.students.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-secondary">Search</button>
    </div>
</form> --}}
<form action="{{ route('admin.students.index') }}" method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="search" style="border: 2px solid black" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select" style="border: 2px solid black" >
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        {{-- <div class="col-md-2">
            <input type="date" name="created_from" class="form-control" placeholder="Created From" value="{{ request('created_from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="created_to" class="form-control" placeholder="Created To" value="{{ request('created_to') }}">
        </div> --}}

        <!-- Search Button -->
        <div class="col-md-1">
            <button style="border: 2px solid black"  type="submit" class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-md-2">
            
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary" style="border: 2px solid black" >Clear Filters</a>
            
        </div>
    </div>
    
</form>
    <table class="table table-bordered">
        <thead>
            <tr class="table-dark">
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr id="student-{{ $student->id }}" >
                    <td >{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td id="status-{{ $student->id }}" class="status-column {{ $student->status }}">
                        {{ $student->status }}
                    </td>                    <td>
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-info toggle-status" data-student-id="{{ $student->id }}" data-status="{{ $student->status }}">
                            Toggle Status
                        </button>
                        <a href="{{ route('admin.students.send-email', $student) }}" class="btn btn-sm btn-success">Send Email</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
    {{ $students->appends(request()->query())->links() }}
    {{-- <p id="change">hello i am</p>
    <button onclick="nyajax()">change</button>
    <div id="loading" style="display:none;">Loading...</div> --}}

    
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).on('click', '.toggle-status', function (e) {
        e.preventDefault();
        var studentId = $(this).data('student-id');
        var currentStatus = $(this).data('status');  // Get current status
        var button = $(this);

        // Send AJAX request to toggle the status
        $.ajax({
            url: '/admin/students/' + studentId + '/status', // Your route URL
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // CSRF token
                status: currentStatus // Send the current status
            },
            success: function (response) {
                // If the status was successfully updated on the server
                if (response.success) {
                    
                    
                    // Update the status in the table
                    var statusCell = $('#status-' + studentId);
                    statusCell.text(response.newStatus);

                    // Change background color based on status
                    if (response.newStatus === 'active') {
                        statusCell.removeClass('inactive').addClass('active'); // Remove inactive class, add active class
                    } else {
                        statusCell.removeClass('active').addClass('inactive'); // Remove active class, add inactive class
                    }
                    // alert('Student status updated successfully!');
                } else {
                    alert('Failed to update status.');
                }
            },
            error: function () {
                alert('Something went wrong!');
            }
        });
    });

    
</script>


<script>
function nyajax(){
    $('#loading').show();

    $.ajax({
        type: 'POST',
        url: '/ajax',  // Ensure this matches your Laravel route
        data: {
            _token: '{{ csrf_token() }}',  // This is the correct way to include CSRF token in a Blade view
        },
        success: function(data) {
            $('#loading').hide();

            // Update the text of the paragraph with the response message
            $("#change").html(data.msg);
        },
        error: function(xhr, status, error) {
            $('#loading').hide();

            console.log("AJAX Error: " + error);
        }
    });
}
</script>
@endsection