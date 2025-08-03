@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Show Task') }}</div>

                <div class="card-body">
                    @auth
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-primary btn-sm" href="{{ route('tasks.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    @endauth
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong> <br />
                                {{ $task->name }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Description:</strong> <br />
                                {{ $task->description }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Due Date:</strong> <br />
                                {{ $task->due_date }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Status:</strong> <br />
                                <span style="font-size: 0.9em;" class="badge bg-{{ $task->status->color() }}">
                                    {{ $task->status->label()}}
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Priority:</strong> <br />
                                {{ $task->priority->label() }}
                            </div>
                        </div>
                        @if(isset($user))
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Created By:</strong> <br />
                                {{ $user->name }}
                            </div>
                        </div>
                        @endif
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Created At:</strong> <br />
                                {{ $task->created_at }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <strong>Updated At:</strong> <br />
                                {{ $task->updated_at }}
                            </div>
                        </div>
                        @auth
                        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                            <div class="form-group">
                                <form action="{{ route('task.link',$task->id) }}" method="POST">

                                    @csrf
                                    @method('POST')

                                    <button type="submit" class="btn btn-info btn-sm">Create link</button>
                                </form>
                            </div>
                            @session('info')
                            <div class="mt-2 mb-0 alert alert-info" role="alert">
                                {{ $value }}
                            </div>
                            @endsession
                        </div>
                        @endauth
                    </div>

                    @if($audits)
                    <div class="row mt-2">
                        <div class="col-12">
                            <table id="datatable" class="table table-bordered table-hover mb-0" style="width:100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Time</th>
                                        <th>Changes</th>
                                    </tr>
                                </thead>
                                <tbody id="audits">
                                    @foreach($audits as $audit)
                                    <tr>
                                        <td>{{ $audit->created_at }}</td>
                                        <td>
                                            @foreach ($audit->getModified() as $field => $values)
                                            {{ $field }}: {{ $values['old'] }} -> {{ $values['new'] }} <br />
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection