@if(session('success'))
    <div class="message message-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="message message-error">{{ session('error') }}</div>
@endif
@if(isset($errors) && $errors->any())
    <div class="message message-error">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif
