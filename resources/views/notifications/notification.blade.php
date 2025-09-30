@extends('layouts.app')

@section('content')
<div class="container">
  <h3>Unread Notifications</h3>
  <ul class="list-group">
    @forelse($notifications as $n)
      @php $data = $n->data; @endphp
      {{-- @dd($data) --}}
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <div class="fw-bold">{{ $data['message'] ?? 'New notification' }}</div>
          @if(!empty($data['url']))
            <a href="{{ $data['url'] }}">Open Task</a>
          @endif
          <div class="text-muted small">Received: {{ $n->created_at->diffForHumans() }}</div>
        </div>
        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
          @csrf
          <button class="btn btn-sm btn-primary">Mark as read</button>
        </form>
      </li>
    @empty
      <li class="list-group-item">No unread notifications 🎉</li>
    @endforelse
  </ul>
  <div class="mt-3">{{ $notifications->links() }}</div>
</div>
@endsection
