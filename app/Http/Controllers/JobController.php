<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Role;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('user.profile')->where('status', 'active');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role_required', $request->role);
        }

        $jobs = $query->latest()->paginate(15)->appends($request->query());
        $roles = Role::whereNotIn('name', ['Admin'])->get();

        return view('jobs.index', compact('jobs', 'roles'));
    }

    public function create()
    {
        // Handled by our architecture RolePolicy bindings
        $this->authorize('create', Job::class);
        $roles = Role::whereNotIn('name', ['Admin'])->get();
        return view('jobs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Job::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'role_required' => 'required|string|exists:roles,name',
            'budget' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
        ]);

        $job = new Job([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'role_required' => $request->role_required,
            'status' => 'active', // Forcibly default bypassing potential pending systems for now
            'budget' => $request->budget,
            'deadline' => $request->deadline,
        ]);

        $job->save();

        return redirect()->route('jobs.show', $job)->with('status', 'Opportunity posted successfully!');
    }

    public function show(Job $job)
    {
        $job->load('user.profile');
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        $roles = Role::whereNotIn('name', ['Admin'])->get();
        return view('jobs.edit', compact('job', 'roles'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'role_required' => 'required|string|exists:roles,name',
            'status' => 'required|in:active,closed,pending_payment',
            'budget' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
        ]);

        $job->update($request->all());

        return redirect()->route('jobs.show', $job)->with('status', 'Opportunity updated successfully!');
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);
        $job->delete();
        return redirect()->route('jobs.index')->with('status', 'Opportunity deleted completely.');
    }
}
