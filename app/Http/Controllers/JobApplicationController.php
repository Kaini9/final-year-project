<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use App\Notifications\GeneralNotification;

class JobApplicationController extends Controller
{
    public function create(Job $job)
    {
        $this->authorize('create', JobApplication::class);

        if ($job->deadline && now()->startOfDay()->greaterThan($job->deadline)) {
            return redirect()->route('jobs.show', $job)->with('error', 'The deadline for this opportunity has passed.');
        }

        // Prevent duplicate applications
        if ($job->applications()->where('user_id', request()->user()->id)->exists()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You have already applied for this opening.');
        }

        return view('job_applications.create', compact('job'));
    }

    public function store(Request $request, Job $job)
    {
        $this->authorize('create', JobApplication::class);

        if ($job->deadline && now()->startOfDay()->greaterThan($job->deadline)) {
            return redirect()->route('jobs.show', $job)->with('error', 'The deadline for this opportunity has passed.');
        }

        // Prevent duplicate applications logically
        if ($job->applications()->where('user_id', $request->user()->id)->exists()) {
            return redirect()->route('jobs.show', $job)->with('error', 'You have already applied for this opening.');
        }

        $request->validate([
            'message' => 'required|string|max:2000',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        JobApplication::create([
            'job_id' => $job->id,
            'user_id' => $request->user()->id,
            'message' => $request->message,
            'status' => 'pending',
            'cv_path' => $cvPath,
        ]);

        // Notify Designer of new application
        $job->user->notify(new GeneralNotification(
            'application',
            $request->user()->name . ' applied for your gig "' . $job->title . '".',
            route('jobs.show', $job),
            $request->user()->id,
            $request->user()->name
        ));

        return redirect()->route('jobs.show', $job)->with('status', 'Application submitted! The designer will review your portfolio.');
    }

    public function update(Request $request, JobApplication $jobApplication)
    {
        // Only the designer managing the job can trigger this
        $this->authorize('update', $jobApplication);

        $request->validate([
            'status' => 'required|in:accepted,rejected,pending',
        ]);

        $jobApplication->update([
            'status' => $request->status,
        ]);

        // Notify Applicant of status change
        $statusMsg = $request->status === 'accepted' ? 'accepted your application' : 'declined your application';
        $jobApplication->user->notify(new GeneralNotification(
            'application_status',
            $jobApplication->job->user->name . ' ' . $statusMsg . ' for "' . $jobApplication->job->title . '".',
            route('job_applications.mine'),
            $jobApplication->job->user->id,
            $jobApplication->job->user->name
        ));

        return back()->with('status', 'Application ' . $request->status . ' successfully.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        $this->authorize('delete', $jobApplication);
        $jobApplication->delete();
        return back()->with('status', 'Application withdrawn.');
    }

    /**
     * Display the authenticated user's applications with status tracking.
     */
    public function myApplications()
    {
        $applications = JobApplication::with(['job.user.profile', 'job.user.role'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('job_applications.index', compact('applications'));
    }

    /**
     * Preview CV in embedded viewer
     */
    public function previewCv(JobApplication $jobApplication)
    {
        // Authorization: Only the applicant or the job poster can view the CV
        if (auth()->id() !== $jobApplication->user_id && auth()->id() !== $jobApplication->job->user_id) {
            abort(403, 'Unauthorized');
        }

        if (!$jobApplication->cv_path) {
            abort(404, 'CV not found');
        }

        $cvUrl = \Storage::url($jobApplication->cv_path);
        $fileName = basename($jobApplication->cv_path);

        return view('job_applications.preview-cv', compact('jobApplication', 'cvUrl', 'fileName'));
    }
}
