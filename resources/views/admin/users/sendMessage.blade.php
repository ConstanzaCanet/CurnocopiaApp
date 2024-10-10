<div>
    <h3>Enviar mensaje a {{ $user->name}}</h3>
    <form action="{{ route('admin.users.sendMessage', $user) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="subject">Asunto:</label>
            <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="message">Mensaje:</label>
            <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar mensaje</button>
    </form>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
</div>