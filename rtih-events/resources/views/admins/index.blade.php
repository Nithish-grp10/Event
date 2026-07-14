<x-app-layout>
    <x-slot name="header">
        Admin & User Management
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User List -->
        <div class="lg:col-span-2">
            <x-ui.data-table search="false">
                <x-slot name="head">
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </x-slot>

                @foreach($admins as $admin)
                    <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ editing: false }">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-sm">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $admin->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $admin->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($admin->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-2">
                                <!-- Edit Role Form -->
                                <div x-show="editing" class="flex gap-2 items-center" x-cloak>
                                    <form method="POST" action="{{ route('admins.update', $admin) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="text-xs rounded-lg border-gray-300 py-1.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $admin->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-ui.button type="submit" variant="primary" size="sm" class="px-2 py-1">Save</x-ui.button>
                                        <x-ui.button type="button" @click="editing = false" variant="ghost" size="sm" class="px-2 py-1">Cancel</x-ui.button>
                                    </form>
                                </div>

                                <div x-show="!editing" class="flex items-center space-x-2">
                                    <x-ui.button type="button" @click="editing = true" variant="secondary" size="sm">
                                        <x-heroicon-o-pencil class="w-4 h-4 mr-1 text-gray-500"/> Edit Role
                                    </x-ui.button>
                                    
                                    @if(auth()->id() !== $admin->id)
                                        <form method="POST" action="{{ route('admins.destroy', $admin) }}" onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="danger" size="sm">
                                                <x-heroicon-o-trash class="w-4 h-4 text-red-500"/>
                                            </x-ui.button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if($admins->hasPages())
                    <x-slot name="pagination">
                        {{ $admins->links() }}
                    </x-slot>
                @endif
            </x-ui.data-table>
        </div>

        <!-- Add New User -->
        <div>
            <div class="sticky top-6">
                <x-ui.card>
                    <x-slot name="header">
                        <h3 class="text-lg font-semibold text-gray-900">Add New User</h3>
                    </x-slot>

                    <x-ui.form method="POST" action="{{ route('admins.store') }}" class="space-y-4">
                        <div>
                            <x-ui.input id="name" name="name" required label="Full Name" />
                        </div>

                        <div>
                            <x-ui.input type="email" id="email" name="email" required label="Email Address" />
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-1.5">Assign Role</label>
                            <select id="role" name="role" class="block w-full rounded-lg border border-gray-200/80 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <x-ui.input type="password" id="password" name="password" required label="Password" />
                        </div>

                        <div>
                            <x-ui.input type="password" id="password_confirmation" name="password_confirmation" required label="Confirm Password" />
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <x-ui.button type="submit" variant="primary" class="w-full">
                                <x-heroicon-o-user-plus class="w-4 h-4 mr-2"/>
                                Create User
                            </x-ui.button>
                        </div>
                    </x-ui.form>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-app-layout>
