@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Task') }}</div>

                <div class="card-body">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-primary btn-sm" href="{{ route('tasks.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>

                    <form action="{{ route('tasks.update',$task->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                        <div class="mb-3">
                            <label for="inputName" class="form-label"><strong>Name:</strong></label>
                            <input
                                type="text"
                                name="name"
                                value="{{ $task->name }}"
                                class="form-control @error('name') is-invalid @enderror"
                                id="inputName"
                                placeholder="Name">
                            @error('name')
                            <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputDescription" class="form-label"><strong>Description:</strong></label>
                            <textarea
                                class="form-control @error('description') is-invalid @enderror"
                                style="height:150px"
                                name="description"
                                id="inputDescription"
                                placeholder="Description">{{ $task->description }}</textarea>
                            @error('description')
                            <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputDueDate" class="form-label"><strong>Due Date:</strong></label>
                            <input type="date"
                                name="due_date"
                                value="{{ $task->due_date }}"
                                class="form-control @error('due_date') is-invalid @enderror"
                                id="inputDueDate">
                            @error('due_date')
                            <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputStatus" class="form-label"><strong>Status:</strong></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" id="inputStatus">
                                @foreach(App\Enums\TaskStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $task->status->value) == $status->value)>
                                    {{ $status->label() }}
                                </option>
                                @endforeach
                            </select>
                            @error('status')
                            <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="inputPriority" class="form-label"><strong>Priority:</strong></label>
                            <select name="priority" class="form-select @error('priority') is-invalid @enderror" id="inputPriority">
                                @foreach(App\Enums\TaskPriority::cases() as $priority)
                                <option value="{{ $priority->value }}" @selected(old('priority', $task->priority->value) == $priority->value)>
                                    {{ $priority->label() }}
                                </option>
                                @endforeach
                            </select>
                            @error('priority')
                            <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection