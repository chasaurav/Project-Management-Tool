<?php

namespace App\Http\Controllers;

use App\Settings;
use App\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getSchedule()
    {
        return response()->json([
            "tasks" => Tasks::all(),
            "settings" => Settings::first()->toArray()
        ]);
    }

    public function getSettings()
    {
        return response()->json([Settings::first(['startDate', 'workHours', 'workTime'])->toArray()]);
    }

    public function getTask()
    {
        return response()->json(Tasks::all());
    }

    public function storeTask(Request $request)
    {
        Tasks::create([
            "name" => $request->taskName,
            "time" => $request->taskTime
        ]);
        return response()->json("Task Saved");
    }

    public function updateTask(Request $request, Tasks $tasks)
    {
        $tasks->update([
            "name" => $request->taskName,
            "time" => $request->taskTime
        ]);
        return response()->json("Task Updated");
    }

    public function deleteTask(Request $request, Tasks $tasks)
    {
        $tasks->delete();
        return response()->json("Task Deleted");
    }

    public function storeSettings(Request $request)
    {
        Settings::query()->delete();

        Settings::create([
            "startDate" => $request->startDate,
            "workTime" => $request->startTime,
            "workHours" => $request->workingHour,
        ]);
        return response()->json("Settings Updated");
    }
}
