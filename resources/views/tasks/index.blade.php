@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Tasks') }}</div>

                <div class="card-body">
                    @session('success')
                    <div class="alert alert-success" role="alert">
                        {{ $value }}
                    </div>
                    @endsession

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-success btn-sm" href="{{ route('tasks.create') }}"> <i class="fa fa-plus"></i> Create New Task</a>
                    </div>

                    <div class="row mt-2">
                        <div class="col-4">
                            <label for="status_filter">By Status</label>
                            <select onchange="document.location=this.options[this.selectedIndex].value" class="form-select" id="status_filter">
                                <option value={{ route('tasks.index', $queryParams['statusFilter']) }}>All</option>
                                @foreach(App\Enums\TaskStatus::cases() as $status)
                                <option value="{{ route('tasks.index', array_merge($queryParams['statusFilter'], ['status' => $status])) }}" @selected(isset($selectedStatus) && $selectedStatus==$status->value)>
                                    {{ $status->label() }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-4">
                            <label for="priority_filter">By Priority</label>
                            <select onchange="document.location=this.options[this.selectedIndex].value" class="form-select" id="priority_filter">
                                <option value={{ route('tasks.index', $queryParams['priorityFilter']) }}>All</option>
                                @foreach(App\Enums\TaskPriority::cases() as $priority)
                                <option value="{{ route('tasks.index', array_merge($queryParams['priorityFilter'], ['priority' => $priority])) }}" @selected(isset($selectedPriority) && $selectedPriority==$priority->value)>
                                    {{ $priority->label() }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-4">
                            <label for="due_date_filter">By Due Date</label>
                            <input onchange="document.location = this.getAttribute('data-url') + this.value" type="date" class="form-control" id="due_date_filter" value="{{ $dueDate }}" data-url="{{ route('tasks.index', array_merge($queryParams['dueDateFilter'], ['dueDate' => ''])) }}">
                        </div>
                    </div>

                    <table class="table table-bordered table-striped mt-4">
                        <thead>
                            <tr>
                                <th width="80px">No</th>
                                <th>Name</th>
                                <th>Priority</th>
                                <th width="100px">Status</th>
                                <th width="100px">Due date</th>
                                <th width="250px">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($tasks as $task)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->priority->label() }}</td>
                                <td>
                                    <span style="font-size: 0.9em;" class="badge bg-{{ $task->status->color() }}">
                                        {{ $task->status->label() }}
                                    </span>
                                </td>
                                <td>{{ $task->due_date }}</td>
                                <td>
                                    <form action="{{ route('tasks.destroy',$task->id) }}" method="POST">

                                        <a class="btn btn-info btn-sm" href="{{ route('tasks.show',$task->id) }}"><i class="fa-solid fa-list"></i> Show</a>

                                        <a class="btn btn-primary btn-sm" href="{{ route('tasks.edit',$task->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">There are no data.</td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>

                    {!! $tasks->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection