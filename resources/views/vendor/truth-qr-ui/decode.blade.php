@extends('truth-qr-ui::layout')

@section('content')
    <h1>Decode</h1>

    <form method="POST" action="{{ route('truthqr.ui.decode.run') }}">
        @csrf
        <div class="card">
            <label>Paste QR Text Lines (one per line)</label><br>
            <textarea name="lines" rows="10" style="width:100%;">{{ old('lines', $input['linesText'] ?? '') }}</textarea>
        </div>

        <div class="card">
            <label><input type="checkbox" name="persist" value="1"> Persist decode</label>
            <input type="text" name="persist_dir" placeholder="optional subdir">
        </div>

        <button type="submit">Decode</button>
    </form>

    @if (!empty($result))
        <hr>
        <h2>Status</h2>
        <pre>@json($result['status'], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)</pre>

        @if ($result['complete'] ?? false)
            <h2>Decoded JSON</h2>
            <pre>@json($result['json'], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)</pre>
        @endif
    @endif
@endsection
