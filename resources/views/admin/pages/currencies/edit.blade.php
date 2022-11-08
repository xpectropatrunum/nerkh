@extends('admin.layouts.master')

@section('title', 'edit currency')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">edit currency</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb @if(app()->getLocale() == 'fa') float-sm-left @else float-sm-right @endif">
                <li class="breadcrumb-item"><a href="{{ route('admin.currencies.index') }}">List currencies</a></li>
                <li class="breadcrumb-item active">edit currency</li>
            </ol>
        </div>
    </div>
@endsection
<script>const data__ = JSON.parse('{!!$js_data!!}');</script>
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$currency->slug}}</h3>
                </div>
                <form action="{{ route('admin.currencies.update', $currency->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <div class="row api-row" >
                            <div class="form-group col-lg-4">
                                <label>Slug</label>
                                <select name="slug" class="form-control select2"  required>
                                    @foreach ($data as $item)
                                        <option value="{{$item->slug}}"
                                            @if (old('slug', $currency->slug) == $item->slug) selected @endif>{{ $item->slug }}({{ $item->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Name</label>
                                <input type="text" value="{{ old('name', $currency->name) }}" name="name" class="form-control @error('name') is-invalid @enderror" required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Priority</label>
                                <input type="number" value="{{ old('priority', $currency->priority) }}" name="priority" class="form-control @error('priority') is-invalid @enderror">
                            </div>
                           
                        </div>
                        <div class="row custom-row" style="display: none">

                            <div class="form-group col-lg-4">
                                <label>Slug</label>
                                <input type="text" value="{{ old('slug',  $currency->slug) }}" name="slug"
                                    class="form-control @error('slug') is-invalid @enderror" >
                            </div>

                            <div class="form-group col-lg-4">
                                <label>Name</label>
                                <input type="text" value="{{ old('name',  $currency->name) }}" name="name"
                                    class="form-control @error('name') is-invalid @enderror" >
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Priority</label>
                                <input type="number" value="{{ old('priority',  $currency->priority) }}" name="priority"
                                    class="form-control @error('priority') is-invalid @enderror">
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

@push('admin_js')
    <script>
        $(function () {
            $("[name=name]").val('{{ old('name', $currency->name) }}')
            if(!data__['{{$currency->slug}}']){
                $(".api-row").hide()
                $("input, select, textarea", $(".api-row")).attr("disabled", "disabled");
                $(".custom-row").show()
            }else{
                $("input, select, textarea", $(".custom-row")).attr("disabled", "disabled");

            }
     

            $("[name=slug]").change(function(){
                if(data__['{{$currency->slug}}']){
                    $("[name=name]").val(data__[$(this).val()])
                }
         
               
            })

        })
    </script>
@endpush
