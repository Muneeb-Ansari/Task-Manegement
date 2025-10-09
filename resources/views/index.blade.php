@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <!-- Top (header) -->
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white/25 bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                                style="width:42px;height:42px;">
                                <i class="bi bi-person-fill fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">User1</h6>
                                <small class="text-white-50">online</small>
                            </div>
                        </div>
                        <span class="badge bg-success">Live</span>
                    </div>

                    <!-- Messages -->
                    <div class="messages p-3" style="height: 60vh; overflow-y: auto; background: #f8fafc;">
                        @include('receive', ['message' => 'hey! whats up'])
                        {{-- example of a sent bubble (optional) --}}
                        {{-- 
          <div class="d-flex justify-content-end mb-2">
            <div class="bg-primary text-white px-3 py-2 rounded-3 shadow-sm" style="max-width: 80%;">
              Sure! send me the details.
              <div class="small text-white-50 mt-1">12:40 PM</div>
            </div>
          </div>
          --}}
                    </div>

                    <!-- Bottom (input) -->
                    <div class="card-footer bg-white">
                        <form class="d-flex gap-2 align-items-center">
                            <input type="text" id="message" name="message"
                                class="form-control form-control-lg rounded-3" placeholder="Type your message..."
                                autocomplete="off">
                            <button class="btn btn-primary btn-lg px-4 rounded-3 d-flex align-items-center gap-2"
                                type="submit">
                                <i class="bi bi-send-fill"></i> Send
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Pusher init — cluster must be present
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: 'mt1',
            forceTLS: true
        });

        const channel = pusher.subscribe('public');

        // receive
        channel.bind('chat', function(data) {
            $.post('/receive', {
                _token: '{{ csrf_token() }}',
                message: data.message
            }).done(function(res) {
                console.log(res,'asasas');
                
                $('.messages').append(res);
                $(document).scrollTop($(document).height());
            });
        });

        // send
        $('form').on('submit', function(e) {
            e.preventDefault();
            const msg = $('#message').val().trim();
            
            if (!msg) return;

            $.ajax({
                url: '/broadcast',
                method: 'POST',
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token: '{{ csrf_token() }}',
                    message: msg
                }
            }).done(function(res) {
                $('.messages').append(res);
                $('#message').val('');
                $(document).scrollTop($(document).height());
            });
        });
    </script>
@endpush
