<x-app-layout>
    <h2>Users</h2>
    <ul>
        @foreach($users as $user)
            <li>
                {{ $user->name }}
                <form method="POST" action="/messages/send">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                    <input type="text" name="message">
                    <button type="submit">Send</button>
                </form>
            </li>
        @endforeach
    </ul>
    <h2>Incoming messages</h2>
    <div id="messages"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let userId = {{ auth()->id() }};

            window.Echo.private(`user.${userId}`)
                .listen('.message.sent', (e) => {
                    addMessage(e.message);
                    markAsRead(e.id)
                });

            fetch('/messages/unread')
                .then(res => res.json())
                .then(messages => {
                    messages.forEach(function (e) {
                        addMessage(e.message)
                    });
                });
        });

        function addMessage(msg) {
            let div = document.getElementById('messages');
            div.innerHTML += `<p>${msg}</p>`;
        }

        function markAsRead(id) {
            fetch('/messages/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    ids: [id]
                })
            });
        }
    </script>
</x-app-layout>
