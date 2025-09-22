@extends('layouts.app-admin')
@extends('layouts.alert')
@section('content')
@if(Auth::user()->level != 4 and Auth::user()->level != 5)
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <h4>Preview</h4>
                {!! $petunjuk !!}

                <h4>Editor</h4>
                @foreach($data as $row => $value)
                {!! Form::model($value, ['url' => ['/update-petunjuk', $value->id]]) !!}
                <div class="form-group">
                    <textarea name="petunjuk" id="summernote">{{$value->petunjuk}}</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
                {!!Form::close()!!}
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
@endsection