<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailToStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // Display all students
    public function index(Request $request)
{
    $search = $request->query('search');
    $status = $request->query('status');
    $createdFrom = $request->query('created_from');
    $createdTo = $request->query('created_to');

    $students = Student::when($search, function ($query, $search) {
        return $query->where('name', 'like', '%' . $search . '%')
                     ->orWhere('email', 'like', '%' . $search . '%');
    })
    ->when($status, function ($query, $status) {
        return $query->where('status', $status);
    })
    ->when($createdFrom, function ($query, $createdFrom) {
        return $query->whereDate('created_at', '>=', $createdFrom);
    })
    ->when($createdTo, function ($query, $createdTo) {
        return $query->whereDate('created_at', '<=', $createdTo);
    })
    ->paginate(10);

    return view('admin.students.index', compact('students'));
}

    // Show the form to create a new student
    public function create()
    {
        return view('admin.students.create');
    }

    // Store a new student
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    // Show the form to edit a student
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    // Update a student
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,'.$student->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $student->password,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    // Delete a student
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    // Update student status (active/inactive)
    // public function updateStatus(Student $student)
    // {
    //     $student->update([
    //         'status' => $student->status === 'active' ? 'inactive' : 'active',
    //     ]);

    //     return redirect()->route('admin.students.index')->with('success', 'Student status updated successfully.');
    // }
    public function updateStatus(Student $student, Request $request)
    {
        // Toggle the status
        $newStatus = $student->status === 'active' ? 'inactive' : 'active';

        // Update the student's status
        $student->update([
            'status' => $newStatus,
        ]);

        return response()->json([
            'success' => true,
            'newStatus' => $newStatus,
        ]);
    }

    // Send email to all students
    public function sendEmailToAll(Request $request)
    {
        // $request->validate([
        //     'subject' => 'required|string|max:255',
        //     'message' => 'required|string',
        // ]);
        if ($request->isMethod('GET')) {
            return view('admin.students.send-email-all');
        }

        $students = Student::all();
        foreach ($students as $student) {
            Mail::to($student->email)->send(new SendEmailToStudent($request->subject, $request->message));
        }

        return response('send', 200);
    }

    // Send email to a specific student
    public function sendEmailToStudent(Request $request, Student $student)
    {

        if ($request->isMethod('GET')) {
            return view('admin.students.send-email', compact('student'));
        }

        $subject = $request->subject;
        $message = $request->message;

        // dd(gettype($message));
        if (! is_string($message)) {
            $message = (string) $message;
        }

        Mail::to($student->email)->send(new SendEmailToStudent($subject, $message));

        return redirect()->route('admin.students.index')->with('success', 'Email sent to '.$student->name);
    }

    public function ajax(Request $request)
    {

        $msg = Student::All();

        return response()->json(['msg' => $msg], 200);

    }
}
