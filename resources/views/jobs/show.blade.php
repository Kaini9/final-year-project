<x-app-layout>
    <div class="bg-ivory min-h-screen py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('jobs.index') }}" class="text-[10px] font-bold text-gray-500 hover:text-ink mb-6 inline-block tracking-widest uppercase transition-colors">&larr; Back to Marketplace</a>

            <!-- Verification Messages -->
            @if (session('status'))
                <div class="p-4 mb-6 text-sm text-green-800 rounded-sm bg-green-50 border border-green-200" role="alert">
                    <span class="font-medium">Notice:</span> {{ session('status') }}
                </div>
            @endif

            <div class="bg-white border p-8 md:p-12 shadow-sm">
                <!-- Header Component -->
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-10 pb-8 border-b">
                    <div class="flex-grow">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-ink text-white text-xs font-bold uppercase tracking-wider">{{ $job->role_required }}</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 border border-gray-200 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full {{ $job->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $job->status }}
                            </span>
                        </div>
                        
                        <h1 class="font-display text-4xl md:text-5xl uppercase tracking-widest text-ink mb-4 leading-tight">{{ $job->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-6 text-sm font-semibold text-gray-500 uppercase tracking-wide">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full overflow-hidden bg-gray-100 border text-gray-400 flex items-center justify-center font-display">
                                    @if($job->user->profile && $job->user->profile->avatar)
                                        <img src="{{ asset('storage/' . $job->user->profile->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xs">{{ substr($job->user->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('profile.show', $job->user) }}" class="hover:text-ink hover:underline flex items-center">
                                    {{ $job->user->name }}
                                    @if($job->user->verification && $job->user->verification->status === 'approved')
                                        <x-verified-badge />
                                    @endif
                                </a>
                            </div>
                            <span>&bull;</span>
                            <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Actions Panel -->
                    <div class="flex flex-col gap-3 min-w-[200px] shrink-0">
                        @if(Auth::id() === $job->user_id || Auth::user()->isAdmin())
                            <a href="{{ route('jobs.edit', $job) }}" class="text-center w-full px-4 py-3 bg-gray-100 text-ink border border-gray-200 text-xs font-bold tracking-widest uppercase hover:bg-gray-200 transition-colors">Edit Opportunity</a>
                            <form action="{{ route('jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Are you strictly sure you want to permanently delete this opportunity?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-center w-full px-4 py-3 bg-white text-red-600 border border-red-200 text-xs font-bold tracking-widest uppercase hover:bg-red-50 hover:border-red-300 transition-colors">Delete Gig</button>
                            </form>
                        @else
                            @if(Auth::user()->role->name !== $job->role_required)
                                <div class="p-4 bg-gray-50 border text-center text-xs text-gray-500 uppercase tracking-widest font-semibold">
                                    Only {{ $job->role_required }}s can apply
                                </div>
                            @else
                                @if($job->status === 'active')
                                    @if($job->applications()->where('user_id', Auth::id())->exists())
                                        <div class="p-4 bg-gray-100 border border-ink text-center text-xs text-ink uppercase tracking-widest font-semibold flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Proposal Sent
                                        </div>
                                    @else
                                        <a href="{{ route('job_applications.create', $job) }}" class="block text-center w-full px-6 py-4 bg-ink text-white text-sm font-bold tracking-widest uppercase hover:bg-gray-800 transition-colors shadow-md transform hover:-translate-y-0.5">Apply Now</a>
                                    @endif
                                @else
                                    <div class="p-4 bg-gray-100 border text-center text-xs text-gray-500 uppercase tracking-widest font-semibold">
                                        Submissions Closed
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Job Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="md:col-span-2 space-y-8">
                        <div>
                            <h2 class="font-bold text-sm tracking-widest text-gray-400 uppercase mb-4 border-b pb-2">The Brief</h2>
                            <div class="prose prose-sm max-w-none text-gray-700 leading-loose">
                                {!! nl2br(e($job->description)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 border self-start space-y-6">
                        <div>
                            <h3 class="font-bold text-[10px] tracking-widest text-gray-500 uppercase mb-1">Budget</h3>
                            <p class="font-semibold text-lg text-ink">
                                {{ $job->budget ? 'NRS ' . number_format($job->budget, 2) : 'Unpaid / Collab' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="font-bold text-[10px] tracking-widest text-gray-500 uppercase mb-1">Deadline</h3>
                            <p class="font-semibold text-ink">
                                {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('F j, Y') : 'Open Ended' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="font-bold text-[10px] tracking-widest text-gray-500 uppercase mb-1">Director</h3>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 border">
                                    @if($job->user->profile && $job->user->profile->avatar)
                                        <img src="{{ asset('storage/' . $job->user->profile->avatar) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('profile.show', $job->user) }}" class="font-bold text-sm hover:underline hover:text-indigo-600 flex items-center">
                                        {{ $job->user->name }}
                                        @if($job->user->verification && $job->user->verification->status === 'approved')
                                            <x-verified-badge />
                                        @endif
                                    </a>
                                    @if($job->user->verification && $job->user->verification->status === 'approved')
                                        <span class="text-[10px] uppercase tracking-widest text-gray-500 flex items-center gap-1 mt-0.5">
                                            Verified
                                            <svg class="w-3 h-3 text-ink" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Tracking Panel (Designers Only) -->
                @if(Auth::id() === $job->user_id)
                    <div class="mt-16 border-t pt-10">
                        <h2 class="font-display text-2xl uppercase tracking-widest text-ink mb-6 flex items-center gap-3">
                            Incoming Proposals
                            <span class="px-2 py-0.5 bg-ink text-white text-xs rounded-full">{{ $job->applications->count() }}</span>
                        </h2>
                        
                        @if($job->applications->count() > 0)
                            <div class="space-y-6">
                                @foreach($job->applications as $application)
                                    <div class="border p-6 shadow-sm flex flex-col md:flex-row gap-6 {{ $application->status === 'accepted' ? 'border-green-500 bg-green-50/30' : ($application->status === 'rejected' ? 'border-red-200 bg-red-50/10 opacity-75' : 'bg-white') }} transition-all">
                                        
                                        <div class="flex items-start gap-4 flex-grow">
                                            <div class="w-12 h-12 shrink-0 rounded-full overflow-hidden bg-gray-200 border text-gray-400 font-display flex items-center justify-center text-xl">
                                                @if($application->user->profile && $application->user->profile->avatar)
                                                    <img src="{{ asset('storage/' . $application->user->profile->avatar) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($application->user->name, 0, 1) }}
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('profile.show', $application->user) }}" class="font-bold hover:underline hover:text-indigo-600 flex items-center text-ink w-fit">
                                                    {{ $application->user->name }}
                                                    @if($application->user->verification && $application->user->verification->status === 'approved')
                                                        <x-verified-badge />
                                                    @endif
                                                </a>
                                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">{{ $application->user->role->name }}</span>
                                                
                                                <div class="mt-4 text-sm text-gray-700 leading-relaxed bg-white border border-gray-100 p-4 shadow-inner">
                                                    {!! nl2br(e($application->message)) !!}
                                                </div>
                                                <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest">{{ $application->created_at->diffForHumans() }}</p>

                                                @if($application->cv_path)
                                                    <div class="mt-4 border-t pt-4 border-dashed border-gray-200">
                                                        <div class="flex gap-2 flex-wrap">
                                                            <a href="{{ route('job_applications.preview-cv', $application) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 transition-colors rounded text-xs font-bold uppercase tracking-widest shadow-sm">
                                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                                Preview CV
                                                            </a>
                                                            <a href="{{ Storage::url($application->cv_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 transition-colors rounded text-xs font-bold uppercase tracking-widest shadow-sm">
                                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                Download CV
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Application Actions -->
                                        <div class="flex flex-col gap-2 shrink-0 w-full md:w-32 justify-start">
                                            @if($application->status === 'pending')
                                                <form action="{{ route('job_applications.update', $application) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="accepted">
                                                    <button type="submit" class="w-full text-center px-4 py-2 bg-green-600 text-white text-xs font-bold uppercase tracking-widest hover:bg-green-700 shadow-sm transition-colors">Accept</button>
                                                </form>
                                                <form action="{{ route('job_applications.update', $application) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="w-full text-center px-4 py-2 bg-white text-red-600 border border-red-200 text-xs font-bold uppercase tracking-widest hover:bg-red-50 shadow-sm transition-colors">Reject</button>
                                                </form>
                                            @else
                                                <div class="px-4 py-2 text-center text-xs font-bold uppercase tracking-widest border {{ $application->status === 'accepted' ? 'text-green-700 border-green-300 bg-green-100' : 'text-red-700 border-red-300 bg-red-100' }}">
                                                    {{ $application->status }}
                                                </div>
                                                <form action="{{ route('job_applications.update', $application) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="w-full mt-2 text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest hover:text-ink underline">Undo</button>
                                                </form>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-10 border border-dashed border-gray-300 text-center flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">No proposals submitted yet.</p>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
