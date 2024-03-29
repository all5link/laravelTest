@extends('layouts.app')

@section('content')
<div class="container">
    <div class='fixed-search'>
    <div class="col-md-12 py-4">
    <form action="search" method="POST" class="form-inline align-items-start">
        @csrf
        @method('GET')
        <div class="container">
        <div class="row d-flex">
            <div class="flex-grow-1">
            <input type="text" name="keyword" placeholder={{ $keyword }} class="form-control w-100">
            </div>
            <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
        </div>
        </div>
    </form>
    </div>
    </div>
    <div class="col-md-12">
      @foreach ($tweets_data as $tweet)
      <div class="card mt-4">
          <div class="card-haeder p-3 w-100 d-flex">
              <img src="{{ asset('storage/profile_image/' .$tweet->user->profile_image) }}" class="rounded-circle" width="50" height="50">
              <div class="ml-2 d-flex flex-column">
                  <p class="mb-0">{{ $tweet->user->name }}</p>
                  <a href="{{ url('users/' .$tweet->user->id) }}" class="text-secondary">{{ $tweet->user->screen_name }}</a>
              </div>
              <div class="d-flex justify-content-end flex-grow-1">
                  <p class="mb-0 text-secondary">{{ $tweet->created_at->format('Y-m-d H:i') }}</p>
              </div>
          </div>
          <div class="card-body">
              {!! nl2br(e($tweet->text)) !!}
          </div>
          <div class="card-footer py-1 d-flex justify-content-end bg-white">
              @if ($tweet->user->id === Auth::user()->id)
                  <div class="dropdown mr-3 d-flex align-items-center">
                      <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v fa-fw"></i>
                      </a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                          <form method="POST" action="{{ url('tweets/' .$tweet->id) }}" class="mb-0">
                              @csrf
                              @method('DELETE')

                              <a href="{{ url('tweets/' .$tweet->id .'/edit') }}" class="dropdown-item">編集</a>
                              <button type="submit" class="dropdown-item del-btn">削除</button>
                          </form>
                      </div>
                  </div>
              @endif
              <div class="mr-3 d-flex align-items-center">
                  <a href="{{ url('tweets/' .$tweet->id) }}"><i class="far fa-comment fa-fw"></i></a>
                  <p class="mb-0 text-secondary">{{ count($tweet->comments) }}</p>
              </div>

              <!-- ここから -->
              <div class="d-flex align-items-center">
                  @if (!in_array($user->id, array_column($tweet->favorites->toArray(), 'user_id'), TRUE))
                      <form method="POST" action="{{ url('favorites/') }}" class="mb-0">
                          @csrf

                          <input type="hidden" name="tweet_id" value="{{ $tweet->id }}">
                          <button type="submit" class="btn p-0 border-0 text-primary"><i class="far fa-heart fa-fw"></i></button>
                      </form>
                  @else
                      <form method="POST" action="{{ url('favorites/' .array_column($tweet->favorites->toArray(), 'id', 'user_id')[$user->id]) }}" class="mb-0">
                          @csrf
                          @method('DELETE')

                          <button type="submit" class="btn p-0 border-0 text-danger"><i class="fas fa-heart fa-fw"></i></button>
                      </form>
                  @endif
                  <p class="mb-0 text-secondary">{{ count($tweet->favorites) }}</p>
              </div>
              <!-- ここまで -->

          </div>
      </div>
    @endforeach
    </div>
</div>
@endsection
