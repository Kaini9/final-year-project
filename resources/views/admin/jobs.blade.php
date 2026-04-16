<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-ink mb-6 inline-block tracking-wide uppercase">&larr; Back to Dashboard</a>

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-1">Job Management</h1>
                    <p class="text-sm text-gray-500 font-medium">View and manage job opportunities.</p>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
                    <span class="font-bold uppercase tracking-widest text-xs">Success</span> <span class="ml-2">{{ session('status') }}</span>
                </div>
            @endif

            <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-xs uppercase tracking-widest font-bold text-gray-500 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4">Poster</th>
                            <th scope="col" class="px-6 py-4">Title / Role</th>
                            <th scope="col" class="px-6 py-4">Applications</th>
                            <th scope="col" class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($jobs as $job)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-ink">{{ $job->user->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold">{{ $job->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $job->role_required }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $job->applications()->count() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3" x-data="{ open: false }">
                                        <a href="{{ route('jobs.show', $job) }}" target="_blank" class="text-xs font-bold uppercase tracking-widest text-indigo-600 hover:text-indigo-900 transition-colors">
                                            View
                                        </a>
                                        <button @click="open = true" class="text-xs font-bold uppercase tracking-widest text-rose-600 hover:text-rose-900 transition-colors">
                                            Delete
                                        </button>

                                        <!-- Delete Modal -->
                                        <div x-show="open" x-cloak class="fixed text-left inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <div x-show="open" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false" aria-hidden="true"></div>
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                <div x-show="open" class="relative inline-block align-bottom bg-white border border-gray-200 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                    <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <h3 class="text-2xl font-display uppercase tracking-widest text-ink mb-1">Delete Job</h3>
                                                            <div class="mt-2">
                                                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-1">Reason for deletion</label>
                                                                <textarea name="reason" required class="block w-full border-gray-300 focus:border-ink focus:ring-ink sm:text-sm bg-gray-50 px-4 py-3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                                            <button type="submit" class="w-full inline-flex justify-center border border-transparent px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm">Delete</button>
                                                            <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center border border-gray-300 px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $jobs->links() }}</div>
        </div>
    </div>
</x-app-layout>
