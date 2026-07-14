<?php

namespace App\Modules\Applications\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\Applications\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return response()->json(Application::all());
    }

    public function store(Request $request)
    {
        $application = Application::create($request->all());
        return response()->json($application, 201);
    }

    public function show(Application $application)
    {
        return response()->json($application);
    }

    public function update(Request $request, Application $application)
    {
        $application->update($request->all());
        return response()->json($application);
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return response()->json(null, 204);
    }
}
