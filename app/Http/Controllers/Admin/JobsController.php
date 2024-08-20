<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ) {

        $jobs = Job::query();

        if(!empty($request->search)) {
            $jobs->where('title', 'like', '%' . $request->search . '%');
        }

        $jobs = $jobs->paginate(10);

        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('admin.jobs.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ) {

        $request->validate([]);

        try {

            $job = new Job();
            $job->title = $request->title;
        $job->description = $request->description;
        $job->visible_from = $request->visible_from;
        $job->visible_to = $request->visible_to;
        $job->deadline = $request->deadline;
            $job->save();

            activity()->performedOn($job)->log('created job:'.$job->title);

            return redirect()->route('jobs.index', [])->with('success', __('Job created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('jobs.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Job $job
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job,) {

        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Job $job
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job,) {

        return view('admin.jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job,) {

        $request->validate([]);

        try {
            $job->title = $request->title;
        $job->description = $request->description;
        $job->visible_from = $request->visible_from;
        $job->visible_to = $request->visible_to;
        $job->deadline = $request->deadline;
            $job->save();

            activity()->performedOn($job)->log('updated job:'.$job->title);

            return redirect()->route('jobs.index', [])->with('success', __('Job edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('jobs.edit', compact('job'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Job $job
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job,) {

        try {
            $job->delete();

            activity()->performedOn($job)->log('deleted job:'.$job->title);

            return redirect()->route('jobs.index', [])->with('success', __('Job deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('jobs.index', [])->with('error', 'Cannot delete Job: ' . $e->getMessage());
        }
    }
}
