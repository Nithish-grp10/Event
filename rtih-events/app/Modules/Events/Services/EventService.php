<?php

namespace App\Modules\Events\Services;

use App\Modules\Events\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class EventService
{
    /**
     * Store a newly created event.
     */
    public function storeEvent(array $validatedData, int $userId): Event
    {
        $validatedData['slug'] = $this->generateUniqueSlug($validatedData['title']);
        $validatedData['created_by'] = $userId;
        $validatedData['status'] = 'draft';

        $validatedData = $this->handleFileUploads($validatedData);

        return Event::create(Arr::except($validatedData, ['banner_image', 'agenda_pdf', 'form_ids']));
    }

    /**
     * Update an existing event.
     */
    public function updateEvent(Event $event, array $validatedData): Event
    {
        $validatedData = $this->handleFileUploads($validatedData);

        $event->update(Arr::except($validatedData, ['form_ids', 'banner_image', 'agenda_pdf']));

        if (isset($validatedData['form_ids'])) {
            $event->forms()->sync($validatedData['form_ids']);
        }

        return $event;
    }

    /**
     * Handle banner and agenda file uploads.
     */
    protected function handleFileUploads(array $data): array
    {
        if (isset($data['banner_image'])) {
            $data['banner_image_path'] = $data['banner_image']->store('events/banners', 'public');
        }
        if (isset($data['agenda_pdf'])) {
            $data['agenda_pdf_path'] = $data['agenda_pdf']->store('events/agendas', 'public');
        }

        return $data;
    }

    /**
     * Generate a unique slug for the event.
     */
    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 2;
        
        while (Event::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
