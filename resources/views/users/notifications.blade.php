@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Notifications!
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($notifications as $notification)
                                <li class="list-group-item">
                                    @if($notification->type==="App\Notifications\NewReplyAdded")
                                    A New Reply was posted in your question <strong>{{ $notification->data['question']['title'] }}</strong>
                                    <a href="{{ route('questions.show', $notification->data['question']['slug']) }}" class="float-right btn btn-sm btn-info text-white">
                                    View Question
                                    </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
