<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $queryParams = [
            'statusFilter' => [],
            'priorityFilter' => [],
            'dueDateFilter' => [],
        ];

        $tasks = Task::latest()
            ->when($request->status, function ($query, $status) use (&$queryParams) {
                $query->where("status", $status);
                foreach (array_keys($queryParams) as $key) {
                    if ('statusFilter' !== $key) {
                        $queryParams[$key]['status'] = $status;
                    }
                }
            })
            ->when($request->priority, function ($query, $priority) use (&$queryParams) {
                $query->where("priority", $priority);
                foreach (array_keys($queryParams) as $key) {
                    if ('priorityFilter' !== $key) {
                        $queryParams[$key]['priority'] = $priority;
                    }
                }
            })
            ->when($request->dueDate, function ($query, $dueDate) use (&$queryParams) {
                $query->where("due_date", $dueDate);
                foreach (array_keys($queryParams) as $key) {
                    if ('dueDateFilter' !== $key) {
                        $queryParams[$key]['dueDate'] = $dueDate;
                    }
                }
            })
            ->where("user_id", Auth::id())
            ->paginate(5);

        return view('tasks.index', compact('tasks'))
            ->with('i', (request()->input('page', 1) - 1) * 5)
            ->with('selectedStatus', $request->status)
            ->with('selectedPriority', $request->priority)
            ->with('dueDate', $request->dueDate)
            ->with('queryParams', $queryParams);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        $user = User::find($task->user_id);

        return view('tasks.show', compact('task', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
