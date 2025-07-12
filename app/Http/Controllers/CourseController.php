<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'nullable|string',
            'subject' => 'nullable|string',
            'location' => 'nullable|string',
            'mode' => 'required|in:online,offline',
            'price' => 'nullable|integer',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'level' => $request->level,
            'subject' => $request->subject,
            'location' => $request->location,
            'mode' => $request->mode,
            'price' => $request->price,
            'tutor_id' => auth()->id(),
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('archiving', 'public');
                $type = in_array($file->extension(), ['jpg', 'jpeg', 'png']) ? 'image' : 'document';

                CourseDocument::create([
                    'course_id' => $course->id,
                    'file_path' => $path,
                    'type' => $type,
                    'title' => $file->getClientOriginalName(),
                ]);
            }
        }

        return response()->json(['message' => 'Cours créé avec succès', 'course' => $course->load('documents')], 201);
    }

    public function index(Request $request)
    {
        $courses = Course::query()
            ->with('documents')
            ->when($request->subject, fn($q, $v) => $q->where('subject', $v))
            ->when($request->level, fn($q, $v) => $q->where('level', $v))
            ->when($request->mode, fn($q, $v) => $q->where('mode', $v))
            ->get();

        return response()->json($courses);
    }
}
