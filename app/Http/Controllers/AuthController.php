<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TutorProfile;
use App\Models\TutorDocument;
use App\Models\Course;
use App\Models\CourseDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentAccountCreated;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{
    public function registerParent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'account_type' => 'parent',
        ]);

        return response()->json(['message' => 'Parent enregistré avec succès!', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $isEmail = filter_var($request->identifier, FILTER_VALIDATE_EMAIL);

        $credentials = [
            $isEmail ? 'email' : 'username' => $request->identifier,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'access_token' => $token,
            'type' => $user->account_type,
            'username' => $user->username,
            'id' => $user->id
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Déconnexion réussie'], 204);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function createStudent(Request $request, int $userId)
    {
        $user = User::findOrFail($userId); 
        if (Auth::id() !== $user->id || $user->account_type !== 'parent') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'student_name' => 'required|string',
            'student_school' => 'required|string',
            'level' => 'required|string',
            'age' => 'required|integer',
            'city' => 'required|string',
            'quarter' => 'required|string',
            'type' => 'required|string',
            'subjects' => 'required|array',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $generatedPassword = Str::random(8);
    
        $studentUser = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'account_type' => 'student',
        ]);
    
        $studentProfile = StudentProfile::create([
            'user_id' => $studentUser->id,
            'parent_id' => $user->id,
            'student_name' => $request->student_name,
            'student_school' => $request->student_school,
            'level' => $request->level,
            'age' => $request->age,
            'city' => $request->city,
            'quarter' => $request->quarter,
            'type' => $request->type,
            'comment' => $request->comment,
            'subjects' => json_encode($request->subjects),
        ]);
    
        Mail::to($studentUser->email)->send(new StudentAccountCreated($studentProfile, $generatedPassword));
    
        return response()->json($studentProfile->load('user'), 201);
    }
    
    public function becomeTutor(Request $request, int $userId)
    {
        $user = User::findOrFail($userId); 
        if (Auth::id() !== $user->id || $user->account_type !== 'parent') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'education_level' => 'required|string',
            'main_degree' => 'required|string',
            'institution' => 'required|string',
            'experience' => 'required|string',
            'subjects' => 'required|array',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $generatedPassword = Str::random(10);
        $user->update([
            'account_type' => 'tutor',
            'password' => Hash::make($generatedPassword)
        ]);
    
        $tutorProfile = TutorProfile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'city' => $request->city,
            'district' => $request->district,
            'education_level' => $request->education_level,
            'main_degree' => $request->main_degree,
            'institution' => $request->institution,
            'experience' => $request->experience,
            'subjects' => json_encode($request->subjects),
        ]);
    
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('archiving', 'public');
                TutorDocument::create([
                    'tutor_profile_id' => $tutorProfile->id,
                    'file' => $path,
                    'title' => $document->getClientOriginalName(),
                ]);
            }
        }
    
        return response()->json(['message' => 'Parent converti en tuteur', 'profile_id' => $tutorProfile->id], 200);
    }

    public function createCourse(Request $request)
    {
        if (Auth::user()->account_type !== 'tutor') {
            return response()->json(['message' => 'Seuls les tuteurs peuvent créer un cours.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string',
            'level' => 'required|string',
            'mode' => 'required|in:online,offline',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'files.*' => 'nullable|file|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $course = Course::create([
            'tutor_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'level' => $request->level,
            'mode' => $request->mode,
            'price' => $request->price,
            'duration' => $request->duration,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('archiving', 'public');
                CourseDocument::create([
                    'course_id' => $course->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return response()->json(['message' => 'Cours créé avec succès.', 'course' => $course->load('documents')], 201);
    }

    public function showParent(User $user)
    {
        if ($user->account_type !== 'parent') {
            return response()->json(['message' => 'Cet utilisateur n\'est pas un parent'], 404);
        }
        return response()->json($user);
    }

    public function showStudent(StudentProfile $studentProfile)
    {
        return response()->json($studentProfile->load('user'));
    }
}
