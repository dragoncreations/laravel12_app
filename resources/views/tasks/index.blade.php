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

                    <table class="table table-bordered table-striped mt-4">
                        <thead>
                            <tr>
                                <th width="80px">No</th>
                                <th>Name</th>
                                <th>Description</th>
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
                                <td>{{ $task->description }}</td>
                                <td>{{ ucfirst($task->priority) }}</td>
                                <td>
                                    <span style="font-size: 0.9em;" class="badge {{ $task->status | statusColor }}">
                                        {{ $task->status | wholeWords }}
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
                                <td colspan="4">There are no data.</td>
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