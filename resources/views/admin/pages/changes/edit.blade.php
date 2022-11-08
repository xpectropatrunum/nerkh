@extends('admin.layouts.master')

@section('title', 'edit diff')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Edit diff</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb @if (app()->getLocale() == 'fa') float-sm-left @else float-sm-right @endif">
                <li class="breadcrumb-item"><a href="{{ route('admin.changes.index') }}">List diffs</a></li>
                <li class="breadcrumb-item active">Edit diff</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit diff</h3>
                </div>
                <form action="{{ route('admin.changes.update', $change->id) }}" method="post">
                    @csrf
                    @method("PUT")
                    <div class="card-body">

                        <div class="row api-row" >
                            <div class="form-group col-lg-4">
                                <label>Slug</label>
                                <select name="currency_id" class="form-control select2" >
                                    @foreach ($data as $item)
                                        <option value="{{$item->id}}"
                                            @if (old('currency_id', $change->currency_id) == $item->id) selected @endif>
                                            {{ $item->slug }}({{ $item->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Type</label>
                                <select name="type" class="form-control select2" >
                                    @foreach ($types as $item)
                                        <option value="{{$item->id}}"
                                            @if (old('type', $change->type) == $item->id) selected @endif>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Value</label>
                                <input type="number" value="{{ old('value', $change->value) }}" name="value"
                                    class="form-control @error('value') is-invalid @enderror">
                            </div>

                        </div>
                    
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary">{{ __('admin.update') }}</button>
                    </div>
                </form>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('admin_css')
@endpush

