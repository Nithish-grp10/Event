<x-app-layout>
    <x-slot name="header">
        Feedback Responses
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">All Feedback</h2>
        </div>
        
        @if($feedbacks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-500 uppercase tracking-wider">
                            <th class="p-4 font-semibold">Event</th>
                            <th class="p-4 font-semibold">Overall</th>
                            <th class="p-4 font-semibold">Speaker</th>
                            <th class="p-4 font-semibold">Venue</th>
                            <th class="p-4 font-semibold">Content</th>
                            <th class="p-4 font-semibold">Submitted</th>
                            <th class="p-4 font-semibold text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($feedbacks as $feedback)
                        <tr class="hover:bg-gray-50 transition-colors" x-data="{ open: false }">
                            <td class="p-4 font-medium text-gray-900">{{ $feedback->event->title ?? 'Unknown' }}</td>
                            <td class="p-4"><span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded font-bold">{{ $feedback->overall_rating }}/5</span></td>
                            <td class="p-4 text-gray-600">{{ $feedback->speaker_rating ?: '-' }}</td>
                            <td class="p-4 text-gray-600">{{ $feedback->venue_rating ?: '-' }}</td>
                            <td class="p-4 text-gray-600">{{ $feedback->content_rating ?: '-' }}</td>
                            <td class="p-4 text-gray-500 text-sm">{{ $feedback->created_at->format('M d, Y') }}</td>
                            <td class="p-4 text-right">
                                <button @click="open = !open" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View Comments</button>
                            </td>
                        </tr>
                        <!-- Expanded details row (alpine) -->
                        <tr x-show="open" x-cloak class="bg-gray-50 border-b border-gray-200">
                            <td colspan="7" class="p-4">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <h4 class="font-semibold text-gray-700">What they liked:</h4>
                                        <p class="text-gray-600 mt-1 whitespace-pre-wrap">{{ $feedback->comments ?: 'No comments provided.' }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-700">Suggestions:</h4>
                                        <p class="text-gray-600 mt-1 whitespace-pre-wrap">{{ $feedback->suggestions ?: 'No suggestions provided.' }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($feedbacks->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                    <x-heroicon-o-chat-bubble-left-ellipsis class="w-8 h-8"/>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No feedback yet</h3>
                <p class="text-gray-500 max-w-sm mx-auto">Feedback submitted by attendees will appear here.</p>
            </div>
        @endif
    </div>
</x-app-layout>
