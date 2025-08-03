<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Spatie\GoogleCalendar\Event;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\AccessToken;
use App\Services\TaskEmailReminderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(private TaskEmailReminderService $taskEmailReminderService) {}

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
        $task = Task::create($request->validated());

        $this->taskEmailReminderService->send($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        $user = User::find($task->user_id);

        $audits = \OwenIt\Auditing\Models\Audit::with('user')
            ->where('auditable_id', $task->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view(
            'tasks.show',
            compact('task', 'user', 'audits')
        );
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

        $this->taskEmailReminderService->send($task);

        if (null !== $task->event_id) {
            $event = Event::find($task->event_id);

            $event->update([
                'name' => $task->name,
                'endDateTime' => Carbon::parse($task->due_date),
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $event = Event::find($task->event_id);

        DB::table("jobs")->where("task_id", $task->id)->delete();

        $event->delete();

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function link(int $taskId): RedirectResponse
    {
        $attr = [
            'task_id' => $taskId,
            'token' => hash('sha256', time()),
            'expires_at' => now()->add('15 minutes')->format('Y-m-d H:i:s'),
        ];

        $accessToken = AccessToken::create($attr);

        return redirect()->route('tasks.show', ['task' => $taskId])
            ->with('info', route('task.share', ['token' => $accessToken->token]));
    }

    public function addToCalendar(int $taskId): RedirectResponse
    {
        try {
            $task = Task::findOrFail($taskId);

            if (Carbon::now()->diffInDays(Carbon::parse($task->due_date)) > 0) {
                $newEvent = Event::create([
                    'name' => $task->name,
                    'startDateTime' => Carbon::now(),
                    'endDateTime' => Carbon::parse($task->due_date),
                ]);

                $task->event_id = $newEvent->id;
                $task->save();

                $info = "Task added to Google Calendar";
            } else {
                $info = "Unable to add event to calendar";
            }

            return redirect()->route('tasks.show', ['task' => $taskId])
                ->with('info', $info);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function share(string $token): View
    {
        try {
            $accessToken = AccessToken::where('token', $token)->firstOrFail();

            if (now() > new Carbon($accessToken->expires_at)) {
                abort(403,  'Unauthorized action.');
            }

            $task = Task::find(intval($accessToken->task_id));

            return view('tasks.show', compact('task'));
        } catch (Exception $e) {
            abort(404);
        }
    }
}
