<div class="bg-gray p-2">
    <h3 class="text-center">Enviar mensaje a {{ $user->name}}</h3>
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
        <div class="text-center">
            <button type="submit" class="btn btn-info">Enviar mensaje</button>
        </div>
    </form>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
</div>