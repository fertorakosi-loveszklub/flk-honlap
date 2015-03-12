@extends('layouts.master')

@section ('content')
    <div class="content text-justify">
        <h2>{{ $pageTitle }}</h2>
        {{ Form::open(array('url' => URL::to($editAction, array(), true), 'method' => 'post')) }}
            <div class="form-group">
                {{ Form::label('title', 'Cím') }}
                <input class="form-control" type="text" id="title" name="title" value="{{ $title }}" @if($titleReadonly) readonly @endif required />
            </div>
            <div class="form-group">
                <textarea id="edit-content" name="content" class="form-control" required>
                    {{ $content }}
                </textarea>
            </div>
            <script type="text/javascript">
                $('#edit-content').wysihtml5({
                    locale: 'hu-HU',
                    toolbar: {
                    "font-styles": false,
                    "emphasis": true,
                    "lists": true,
                    "html": true,
                    "link": true,
                    "image": true,
                    "color": false,
                    "blockquote": false,
                    "fa": true
                    }
                });
            </script>
            <button type="submit" class="btn btn-primary">Mentés</button>
        {{ Form::close() }}
    </div>
@overwrite

@section('scripts')
    <link rel="stylesheet" type="text/css" href="{{ secure_asset('css/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <script src="{{ secure_asset('js/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <script src="{{ secure_asset('js/bootstrap3-wysihtml5/hu_HU.js') }}"></script>
@overwrite
