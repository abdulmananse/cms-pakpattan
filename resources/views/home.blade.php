<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200 shadow-md rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">#</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Complaint No</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Category</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Description</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Location</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Created At</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($complaints as $index => $complaint)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $complaint->complaint_no }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $complaint->category->name ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600 truncate max-w-xs">{{ $complaint->description }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $complaint->location }}</td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        {{ $complaint->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $complaint->status == 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $complaint->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $complaint->created_at->format('d M, Y') }}</td>
                                {{-- <td class="px-4 py-2 text-center">
                                    <a href="{{ route('complaints.show', $complaint->id) }}" 
                                    class="text-blue-600 hover:underline text-sm">View</a>
                                    <a href="{{ route('complaints.edit', $complaint->id) }}" 
                                    class="ml-2 text-indigo-600 hover:underline text-sm">Edit</a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
