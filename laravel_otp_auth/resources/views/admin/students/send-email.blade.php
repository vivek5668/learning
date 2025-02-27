@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Send Email to {{ $student->name }}</h1>
    <form action="{{ route('admin.students.send-email', $student) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Email</button>
    </form>
</div>
@endsection