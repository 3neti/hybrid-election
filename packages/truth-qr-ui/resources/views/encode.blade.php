@extends('truth-qr-ui::layout')

@section('content')
    <h1>Encode</h1>

    <form method="POST" action="{{ route('truthqr.ui.encode.run') }}">
        @csrf
        <div class="card">
            <label>Code</label><br>
            <input type="text" name="code" value="{{ old('code', $input['code'] ?? '') }}" style="width: 320px;">
        </div>

        <div class="card">
            <label>Payload (JSON / YAML / text)</label><br>
            <textarea name="payload" rows="10" style="width:100%;">{{ old('payload', $input['payloadText'] ?? '') }}</textarea>
        </div>

        <div class="card">
            <label>Writer</label>
            <select name="writer">
                @foreach (['bacon','endroid','null'] as $w)
                    <option value="{{ $w }}" @selected(($input['writer'] ?? 'bacon') === $w)>{{ $w }}</option>
                @endforeach
            </select>

            <label style="margin-left:1rem;">Format</label>
            <select name="format">
                @foreach (['svg','png'] as $f)
                    <option value="{{ $f }}" @selected(($input['format'] ?? 'svg') === $f)>{{ $f }}</option>
                @endforeach
            </select>

            <label style="margin-left:1rem;">QR Image Size</label>
            <input type="number" name="size" value="{{ old('size', $input['imgSize'] ?? 800) }}" min="128" max="2048">
        </div>

        <div class="card">
            <label>Chunk Strategy</label>
            <select name="by">
                @foreach (['size','count'] as $b)
                    <option value="{{ $b }}" @selected(($input['by'] ?? 'size') === $b)>{{ $b }}</option>
                @endforeach
            </select>

            <label style="margin-left:1rem;">Chunk size</label>
            <input type="number" name="chunk" value="{{ old('chunk', $input['chunk'] ?? 800) }}" min="100" max="4000">

            <label style="margin-left:1rem;">Count</label>
            <input type="number" name="count" value="{{ old('count', $input['count'] ?? 3) }}" min="1" max="20">
        </div>

        <button type="submit">Generate</button>
    </form>

    @if (!empty($result))
        <hr>
        <h2>Result</h2>
        <p><strong>Code:</strong> {{ $result['code'] }}</p>
        <div class="grid">
            @foreach (($result['qr'] ?? []) as $idx => $blob)
                <div class="card">
                    <div><strong>Chunk {{ $idx }}</strong></div>
                    @if (str_starts_with($blob, '<svg'))
                        <div>{!! $blob !!}</div>
                    @else
                        <img alt="QR {{ $idx }}" src="data:image/png;base64,{{ base64_encode($blob) }}">
                    @endif
                </div>
            @endforeach
        </div>

        <h3>URLs</h3>
        <pre>@json($result['urls'], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)</pre>
    @endif
@endsection
