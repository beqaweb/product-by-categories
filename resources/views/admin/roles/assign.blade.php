@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{ route('roleUpdate') }}">
                    <input name="_method" type="hidden" value="PUT">
                    <input name="page" type="hidden" value="{{request('page') ?? 1}}">
                    @csrf

                    <div class="form-group">
                        @if(count($users) > 0)
                            <ul class="list-group">
                                @foreach($users as $user)
                                    <li class="list-group-item row" style="display:flex;">
                                        <div class="col-9 d-flex flex-column">
                                            <span>{{$user->name}}</span>
                                            <small class="text-muted">{{$user->email}}</small>
                                        </div>
                                        <span>
                                            <select name="roles[{{$user->id}}]">
                                                <option value="null" {{(count($user->roles) === 0) ? 'selected="true"' : ''}}>-</option>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}" {{(count($user->roles) > 0 && $user->roles->toArray()[0]['id'] === $role->id) ? 'selected="true"' : ''}}>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3 d-flex justify-content-center">{{ $users->links() }}</div>
                        @endif
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
