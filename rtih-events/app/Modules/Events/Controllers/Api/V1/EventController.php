<?php

namespace App\Modules\Events\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Events\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return response()->json(Event::all());
    }

    public function store(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        return response()->json($event);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($request->all());
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }
}
