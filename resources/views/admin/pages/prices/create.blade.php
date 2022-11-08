@extends('admin.layouts.master')

@section('title', 'create new price')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Create new price</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb @if (app()->getLocale() == 'fa') float-sm-left @else float-sm-right @endif">
                <li class="breadcrumb-item"><a href="{{ route('admin.prices.index') }}">List prices</a></li>
                <li class="breadcrumb-item active">Create new price</li>
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
                    <h3 class="card-title">Create new price</h3>
                </div>
                <form action="{{ route('admin.prices.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row api-row" >
                            <div class="form-group col-lg-4">
                                <label>Slug</label>
                                <select name="currency_id" class="form-control select2" >
                                    @foreach ($data as $item)
                                        <option value="{{$item->id}}"
                                            @if (old('currency_id') == $item->id) selected @endif>
                                            {{ $item->slug }}({{ $item->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>price</label>
                                <input type="number" value="{{ old('price') }}" name="price"
                                    class="form-control @error('price') is-invalid @enderror">
                            </div>

                        </div>
                    
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary">{{ __('admin.add') }}</button>
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

