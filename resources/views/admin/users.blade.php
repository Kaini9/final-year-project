<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-ink mb-6 inline-block tracking-wide uppercase">&larr; Back to Dashboard</a>

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-1">User Management</h1>
                    <p class="text-sm text-gray-500 font-medium">View and manage all registered members on the platform.</p>
                </div>
                <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }">
                    <button @click="open = true" class="px-6 py-3 bg-ink hover:bg-gray-800 text-white text-xs font-bold uppercase tracking-widest transition-colors shadow-sm">
                        + Add New User
                    </button>
                    
                    <!-- Modal -->
                    <div x-show="open" x-cloak class="fixed text-left inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            
                            <!-- Background overlay -->
                            <div x-show="open" 
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                 @click="open = false" aria-hidden="true"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal panel -->
                            <div x-show="open"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="relative inline-block align-bottom bg-white border border-gray-200 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                
                                <form action="{{ route('admin.users.store') }}" method="POST">
                                    @csrf
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="mb-4 text-center sm:text-left">
                                            <h3 class="text-2xl font-display uppercase tracking-widest text-ink mb-1" id="modal-title">
                                                Add New User
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                A secure password will be automatically generated and emailed to the user.
                                            </p>
                                        </div>

                                        <div class="space-y-4">
                                            <div>
                                                <label for="name" class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-1">Full Name</label>
                                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="block w-full border-gray-300 focus:border-ink focus:ring-ink sm:text-sm bg-gray-50 px-4 py-3">
                                                @error('name')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                                            </div>
                                            <div>
                                                <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-1">Email Address</label>
                                                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="block w-full border-gray-300 focus:border-ink focus:ring-ink sm:text-sm bg-gray-50 px-4 py-3">
                                                @error('email')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                                            </div>
                                            <div>
                                                <label for="role_id" class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-1">User Role</label>
                                                <select name="role_id" id="role_id" required class="block w-full border-gray-300 focus:border-ink focus:ring-ink sm:text-sm bg-gray-50 px-4 py-3">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                        <button type="submit" class="w-full inline-flex justify-center border border-transparent px-6 py-3 bg-ink text-xs font-bold uppercase tracking-widest text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ink sm:ml-3 sm:w-auto sm:text-sm transition-colors shadow-sm">
                                            Create & Invite
                                        </button>
                                        <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center border border-gray-300 px-6 py-3 bg-white text-xs font-bold uppercase tracking-widest text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors shadow-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 text-sm text-green-800 rounded-lg bg-green-50 bg-white border border-green-200" role="alert">
                    <span class="font-bold uppercase tracking-widest text-xs">Success</span> <span class="ml-2">{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 text-sm text-rose-800 rounded-lg bg-rose-50 bg-white border border-rose-200" role="alert">
                    <span class="font-bold uppercase tracking-widest text-xs">Error</span> <span class="ml-2">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase tracking-widest font-bold text-gray-500 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-4">User</th>
                                <th scope="col" class="px-6 py-4">Role</th>
                                <th scope="col" class="px-6 py-4">Bio / Skills</th>
                                <th scope="col" class="px-6 py-4">Joined</th>
                                <th scope="col" class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($user->profile && $user->profile->avatar)
                                                <img src="{{ Storage::url($user->profile->avatar) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
                                                    <span class="text-gray-500 font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold text-ink">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full 
                                            {{ $user->role->name === 'Admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $user->role->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-[200px]">
                                        @if($user->profile)
                                            <p class="truncate text-xs text-gray-500 mb-1" title="{{ $user->profile->bio }}">{{ $user->profile->bio ?: 'No bio provided' }}</p>
                                            <div class="flex gap-1 overflow-hidden">
                                                @if($user->profile->skills && is_array($user->profile->skills))
                                                    @foreach(array_slice($user->profile->skills, 0, 2) as $skill)
                                                        <span class="text-[9px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">{{ $skill }}</span>
                                                    @endforeach
                                                    @if(count($user->profile->skills) > 2)
                                                        <span class="text-[9px] font-bold text-gray-400">...</span>
                                                    @endif
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">No profile setup</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        {{ $user->created_at->format('M j, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('profile.show', $user->id) }}" target="_blank" class="text-xs font-bold uppercase tracking-widest text-indigo-600 hover:text-indigo-900 transition-colors">
                                                View
                                            </a>
                                            @if(auth()->id() !== $user->id)
                                                <div x-data="{ suspendOpen: false, deleteOpen: false }" class="flex items-center gap-3">
                                                    @if($user->isSuspended())
                                                        <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-xs font-bold uppercase tracking-widest text-emerald-600 hover:text-emerald-900 transition-colors">
                                                                Unsuspend
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button @click="suspendOpen = true" class="text-xs font-bold uppercase tracking-widest text-amber-600 hover:text-amber-900 transition-colors">
                                                            Suspend
                                                        </button>
                                                    @endif
                                                    <button @click="deleteOpen = true" class="text-xs font-bold uppercase tracking-widest text-rose-600 hover:text-rose-900 transition-colors">
                                                        Delete
                                                    </button>

                                                    <!-- Suspend Modal -->
                                                    <div x-show="suspendOpen" x-cloak class="fixed text-left inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                                                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="suspendOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="suspendOpen = false" aria-hidden="true"></div>
                                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                            <div x-show="suspendOpen" class="relative inline-block align-bottom bg-white border border-gray-200 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                                                                    @csrf
                                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                        <h3 class="text-2xl font-display uppercase tracking-widest text-ink mb-1">Suspend User</h3>
                                                                        <div class="mt-4 space-y-4">
                                                                            <div>
                                                                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-1">Reason</label>
                                                                                <textarea name="reason" required class="block w-full border-gray-300 focus:border-ink focus:ring-ink sm:text-sm bg-gray-50 px-4 py-3"></textarea>
                                                                            </div>
                                                                            <div>
                                                                                <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-1">Duration (Days)</label>
                                                                                <input type="number" name="days" min="1" max="365" required class="block w-full border-gray-300 focus:border-ink focus:ring-ink sm:text-sm bg-gray-50 px-4 py-3" value="7">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                                                        <button type="submit" class="w-full inline-flex justify-center border border-transparent px-6 py-3 bg-amber-600 text-xs font-bold uppercase tracking-widest text-white hover:bg-amber-700 focus:outline-none sm:ml-3 sm:w-auto transition-colors shadow-sm">Suspend</button>
                                                                        <button type="button" @click="suspendOpen = false" class="mt-3 w-full inline-flex justify-center border border-gray-300 px-6 py-3 bg-white text-xs font-bold uppercase tracking-widest text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto transition-colors shadow-sm">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Modal -->
                                                    <div x-show="deleteOpen" x-cloak class="fixed text-left inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
                                                        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="deleteOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="deleteOpen = false" aria-hidden="true"></div>
                                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                            <div x-show="deleteOpen" class="relative inline-block align-bottom bg-white border border-gray-200 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                        <h3 class="text-2xl font-display uppercase tracking-widest text-rose-600 mb-1">Delete User</h3>
                                                                        <p class="text-sm text-gray-500 mb-4">This action cannot be undone. All data will be permanently removed.</p>
                                                                        <div>
                                                                            <label class="block text-xs font-bold uppercase tracking-widest text-gray-700 mb-1">Reason</label>
                                                                            <textarea name="reason" required class="block w-full border-gray-300 focus:border-ink focus:ring-ink sm:text-sm bg-gray-50 px-4 py-3"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                                                                        <button type="submit" class="w-full inline-flex justify-center border border-transparent px-6 py-3 bg-rose-600 text-xs font-bold uppercase tracking-widest text-white hover:bg-rose-700 focus:outline-none sm:ml-3 sm:w-auto transition-colors shadow-sm">Delete</button>
                                                                        <button type="button" @click="deleteOpen = false" class="mt-3 w-full inline-flex justify-center border border-gray-300 px-6 py-3 bg-white text-xs font-bold uppercase tracking-widest text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto transition-colors shadow-sm">Cancel</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
