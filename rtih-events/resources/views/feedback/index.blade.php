<x-app-layout>
    <x-slot name="header">
        Feedback Responses
    </x-slot>

    @if($feedbacks->count() > 0)
        <x-ui.data-table search="false">
            <x-slot name="head">
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Event</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ratings</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Details</th>
            </x-slot>

            @foreach($feedbacks as $feedback)
                <tbody x-data="{ open: false }" class="border-b border-gray-200 hover:bg-gray-50/50 transition-colors last:border-0">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $feedback->event->title ?? 'Unknown' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-4 text-sm">
                                <div>
                                    <span class="text-xs text-gray-500 block">Overall</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $feedback->overall_rating }}/5</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Speaker</span>
                                    <span class="font-medium text-gray-700">{{ $feedback->speaker_rating ?: '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Venue</span>
                                    <span class="font-medium text-gray-700">{{ $feedback->venue_rating ?: '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Content</span>
                                    <span class="font-medium text-gray-700">{{ $feedback->content_rating ?: '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $feedback->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-ui.button type="button" @click="open = !open" variant="secondary" size="sm">
                                <span x-show="!open" class="flex items-center"><x-heroicon-o-chevron-down class="w-4 h-4 mr-1 text-gray-400"/> View</span>
                                <span x-show="open" class="flex items-center"><x-heroicon-o-chevron-up class="w-4 h-4 mr-1 text-gray-400"/> Hide</span>
                            </x-ui.button>
                        </td>
                    </tr>
                    
                    <!-- Expanded details row -->
                    <tr x-show="open" x-cloak x-transition class="bg-gray-50/80 border-t border-gray-100">
                        <td colspan="4" class="px-6 py-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                                        <x-heroicon-o-hand-thumb-up class="w-4 h-4 mr-1.5 text-emerald-500"/> What they liked
                                    </h4>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $feedback->comments ?: 'No comments provided.' }}</p>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center">
                                        <x-heroicon-o-light-bulb class="w-4 h-4 mr-1.5 text-amber-500"/> Suggestions
                                    </h4>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $feedback->suggestions ?: 'No suggestions provided.' }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            @endforeach

            @if($feedbacks->hasPages())
                <x-slot name="pagination">
                    {{ $feedbacks->links() }}
                </x-slot>
            @endif
        </x-ui.data-table>
    @else
        <!-- Empty State -->
        <x-ui.card class="flex flex-col items-center justify-center p-16 text-center">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm border border-indigo-100">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-8 h-8"/>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No feedback received</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto text-sm">Feedback submitted by attendees for your events will appear here.</p>
        </x-ui.card>
    @endif
</x-app-layout>
