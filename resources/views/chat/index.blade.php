@extends('layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div style="display:flex; gap:20px;">
        <div style="width:250px; border-right:1px solid #ddd; padding:10px;">
            <h4>Contacts</h4>
            <ul id="users">
                @if (isset($users))
                    @foreach ($users as $u)
                        <li data-id="{{ $u->id }}" style="cursor:pointer; padding:6px;" class="contact">
                            {{ $u->name }}</li>
                    @endforeach
                @else
                    <li data-id="{{ $user[0]->id }}" class="contact">{{ $user[0]->name }}</li>
                @endif
            </ul>
        </div>

        <!-- Chat window -->
        <div style="flex:1; display:flex; flex-direction:column;">
            <div id="chatHeader" style="padding:10px; border-bottom:1px solid #eee;">Select a contact</div>
            <div id="messages" style="flex:1; padding:10px; overflow-y:auto; height:400px;"></div>

            <form id="msgForm" style="display:flex; gap:10px; padding:10px; border-top:1px solid #eee;">
                <input id="msgInput" autocomplete="off" style="flex:1; padding:8px;" placeholder="Type a message...">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <script type="module">
        import axios from 'axios';

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

        let selectedUserId = null;

        // click contact
        document.querySelectorAll('.contact').forEach(el => {
            el.addEventListener('click', async () => {
                selectedUserId = el.dataset.id;
                document.getElementById('chatHeader').innerText = el.innerText;
                await loadMessages(selectedUserId);

                // subscribe to your private channel (auth'd automatically)
                window.Echo.private('private-user.' + selectedUserId)
                    .listen('MessageSent', (e) => {
                        // if the event belongs to this conversation (either from or to)
                        if (e.from_id == selectedUserId || e.to_id == selectedUserId) {
                            appendMessage(e);
                        }
                    });

                // also subscribe to your own private channel to get messages sent to you
                // (the server already broadcasts to both channels; we'll also subscribe to our own)
                const meId = {{ auth()->id() }};
                window.Echo.private('private-user.' + meId)
                    .listen('MessageSent', (e) => {
                        // show if belongs to current selected chat
                        if (e.from_id == selectedUserId || e.to_id == selectedUserId) {
                            appendMessage(e);
                        }
                    });
            });
        });

        async function loadMessages(userId) {
            const res = await axios.get(`/chat/${userId}`);
            const container = document.getElementById('messages');
            container.innerHTML = '';
            res.data.forEach(m => {
                const side = m.from_id == {{ auth()->id() }} ? 'right' : 'left';
                container.innerHTML +=
                    `<div style="margin:6px 0; text-align:${side};"><b>${m.sender.name}:</b> ${m.body} <br><small>${m.created_at}</small></div>`;
            });
            container.scrollTop = container.scrollHeight;
        }

        function appendMessage(e) {
            const container = document.getElementById('messages');
            const senderName = e.sender?.name || 'You';
            const side = e.from_id == {{ auth()->id() }} ? 'right' : 'left';
            container.innerHTML +=
                `<div style="margin:6px 0; text-align:${side};"><b>${senderName}:</b> ${e.body} <br><small>${e.created_at}</small></div>`;
            container.scrollTop = container.scrollHeight;
        }

        document.getElementById('msgForm').addEventListener('submit', async (ev) => {
            ev.preventDefault();
            if (!selectedUserId) return alert('Select a contact first');
            const body = document.getElementById('msgInput').value.trim();
            if (!body) return;
            await axios.post(`/chat/${selectedUserId}`, {
                body
            });
            document.getElementById('msgInput').value = '';
            // message will appear when broadcast is received (appendMessage)
        });
    </script>

@endsection
