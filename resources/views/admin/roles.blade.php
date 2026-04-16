<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-500 hover:text-ink mb-6 inline-block tracking-wide uppercase">&larr; Back to Dashboard</a>

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="font-display text-4xl uppercase tracking-widest text-ink mb-1">Role Management</h1>
                    <p class="text-sm text-gray-500 font-medium">Create roles and manage capabilities like job posting.</p>
                </div>
                <!-- Create Role Modal -->
                <div x-data="{ open: {{ $errors->any() && !old('role_id') ? 'true' : 'false' }} }">
                    <button @click="open = true" class="px-6 py-3 bg-ink hover:bg-gray-800 text-white text-xs font-bold uppercase tracking-widest transition-colors shadow-sm">
                        + Add New Role
                    </button>
                    <div x-show="open" x-cloak class="fixed text-left inset-0 z-50 overflow-y-auto">
                        <div class="flex items-center justify-center min-h-screen">
                            <div x-show="open" class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="open = false"></div>
                            <div x-show="open" class="relative bg-white p-6 max-w-lg w-full mx-auto">
                                <h3 class="text-2xl font-display uppercase text-ink mb-4">Create Role</h3>
                                <form action="{{ route('admin.roles.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block font-bold text-xs">Role Name</label>
                                        <input type="text" name="name" class="w-full mt-1 border-gray-300 px-4 py-2" required>
                                    </div>
                                    <div class="mb-4 flex items-center">
                                        <input type="checkbox" name="can_post_jobs" value="1" class="border-gray-300">
                                        <label class="ml-2 font-bold text-xs">Can Post Jobs?</label>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="open = false" class="px-4 py-2 border">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-ink text-white">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 text-green-800 bg-green-50 border border-green-200">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 p-4 text-rose-800 bg-rose-50 border border-rose-200">{{ session('error') }}</div>
            @endif

            <div class="bg-white border rounded-xl overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4">Role Name</th>
                            <th class="px-6 py-4">Users</th>
                            <th class="px-6 py-4">Can Post Jobs</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr class="border-t">
                                <td class="px-6 py-4 font-bold">{{ $role->name }}</td>
                                <td class="px-6 py-4">{{ $role->users_count }}</td>
                                <td class="px-6 py-4">
                                    @if($role->can_post_jobs || $role->name === 'Admin') 
                                        <span class="text-green-600 font-bold">Yes</span>
                                    @else
                                        <span class="text-gray-400 font-bold">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($role->name !== 'Admin' && $role->name !== 'User')
                                    <div x-data="{ open: {{ (old('role_id') == $role->id && $errors->any()) ? 'true' : 'false' }} }" class="inline-block">
                                        <button @click="open = true" class="text-indigo-600 font-bold">Edit</button>
                                        <div x-show="open" x-cloak class="fixed text-left inset-0 z-50 overflow-y-auto">
                                            <div class="flex items-center justify-center min-h-screen">
                                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="open = false"></div>
                                                <div class="relative bg-white p-6 max-w-lg w-full mx-auto text-left">
                                                    <h3 class="text-2xl font-display uppercase text-ink mb-4">Edit Role</h3>
                                                    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                        <div class="mb-4">
                                                            <label class="block font-bold text-xs">Role Name</label>
                                                            <input type="text" name="name" value="{{ $role->name }}" class="w-full mt-1 border-gray-300 px-4 py-2" required>
                                                        </div>
                                                        <div class="mb-4 flex items-center">
                                                            <input type="checkbox" name="can_post_jobs" value="1" {{ $role->can_post_jobs ? 'checked' : '' }} class="border-gray-300">
                                                            <label class="ml-2 font-bold text-xs">Can Post Jobs?</label>
                                                        </div>
                                                        <div class="flex justify-end gap-2">
                                                            <button type="button" @click="open = false" class="px-4 py-2 border">Cancel</button>
                                                            <button type="submit" class="px-4 py-2 bg-ink text-white">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
