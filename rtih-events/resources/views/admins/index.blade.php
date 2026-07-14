<x-app-layout>
    <x-slot name="header">
        Admin & User Management
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">System Users</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-500 uppercase tracking-wider">
                                <th class="p-4 font-semibold">Name</th>
                                <th class="p-4 font-semibold">Email</th>
                                <th class="p-4 font-semibold">Role</th>
                                <th class="p-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($admins as $admin)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 font-medium text-gray-900">{{ $admin->name }}</td>
                                <td class="p-4 text-gray-700">{{ $admin->email }}</td>
                                <td class="p-4">
                                    @foreach($admin->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex justify-end gap-2" x-data="{ editing: false }">
                                        <!-- Edit Role Form -->
                                        <div x-show="editing" class="flex gap-2 items-center" x-cloak>
                                            <form method="POST" action="{{ route('admins.update', $admin) }}" class="flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="text-sm rounded border-gray-300 py-1">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->name }}" {{ $admin->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="text-sm bg-indigo-600 text-white px-2 py-1 rounded">Save</button>
                                                <button type="button" @click="editing = false" class="text-sm text-gray-500 px-2">Cancel</button>
                                            </form>
                                        </div>

                                        <div x-show="!editing">
                                            <button @click="editing = true" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm mr-3">Edit Role</button>
                                            @if(auth()->id() !== $admin->id)
                                                <form method="POST" action="{{ route('admins.destroy', $admin) }}" class="inline" onsubmit="return confirm('Delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($admins->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Add New User -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Add New User</h2>
                </div>
                <form method="POST" action="{{ route('admins.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="name" value="Full Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email Address" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="role" value="Assign Role" />
                        <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirm Password" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 shadow-sm mt-4">
                        Create User
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
