<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();

        // dd(request('project_id'));
        
        
        if (request('project_id')) {

            $tasks = Task::orderBy('priority')->where('project_id', request('project_id'))->get();
            $project_id = request('project_id');

        } else {

            $tasks = Task::orderBy('priority')->where('project_id', 1)->get();
            $project_id = 1;
            
        }

        return view("welcome", compact("tasks", "projects", "project_id"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $task = Task::create($request->validate([
            'project_id' => 'required|integer',
            'task_name' => 'required|min:5|max:50',
            'priority'  => 'integer',
        ]));

        return redirect('/')->with('message', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::find($id);
  
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::find($id)->update($request->validate([
            'project_id' => 'required|integer',
            'task_name' => 'required|min:5|max:50',
            'priority'  => 'integer',
        ]));

        return redirect('/')->with('message', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json($task);
    }

    public function updateOrder(Request $request)
    {
        $input = $request->all();

        if (isset($input["order"])) {

            $order  = explode(",", $input["order"]);

            for ($i = 0; $i < count($order); $i++) {

                Task::where('id', $order[$i])->update(['priority' => $i]);
            }

            return json_encode([
                "status" => true,
                "message" => "Order updated"
            ]);
        }
    }
}
