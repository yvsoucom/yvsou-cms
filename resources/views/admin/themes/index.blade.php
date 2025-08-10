@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Themes</h1>

    {{-- Upload Theme ZIP --}}
    <div class="card mb-4">
        <div class="card-header">Upload New Theme</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.themes.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" name="theme_zip" class="form-control" accept=".zip" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload Theme</button>
            </form>
        </div>
    </div>

    {{-- List Available Themes --}}
    <div class="row">
        @foreach($themes as $theme)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $theme['name'] ?? $theme['folder'] }}</h5>
                        <p class="card-text">{{ $theme['description'] ?? 'No description' }}</p>
                        <form action="{{ route('admin.themes.switch') }}" method="POST">
                            @csrf
                            <input type="hidden" name="theme" value="{{ $theme['folder'] }}">
                            <button type="submit" class="btn btn-success">
                                Activate
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <small>Version: {{ $theme['version'] ?? '1.0' }}</small>
                        <br>
                        <small>Author: {{ $theme['author'] ?? 'Unknown' }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
