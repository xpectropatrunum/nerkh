@extends('admin.layouts.master')

@section('title', 'create new currency')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Create new currency</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb @if (app()->getLocale() == 'fa') float-sm-left @else float-sm-right @endif">
                <li class="breadcrumb-item"><a href="{{ route('admin.currencies.index') }}">List currencies</a></li>
                <li class="breadcrumb-item active">Create new currency</li>
            </ol>
        </div>
    </div>
@endsection
<script>
    const data__ = JSON.parse('{!! $js_data !!}');
</script>
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create new currency</h3>
                </div>
                <form action="{{ route('admin.currencies.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <label for="is_custom">extra currency?</label>
                        <input type="checkbox" id="is_custom" value="0">
                        <div class="row api-row">
                            <div class="form-group col-lg-4">
                                <label>Slug</label>
                                <select name="slug" class="form-control select2">
                                    @foreach ($data as $item)
                                        <option value="{{ $item->slug }}"
                                            @if (old('slug') == $item->slug) selected @endif>
                                            {{ $item->slug }}({{ $item->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Name</label>
                                <input type="text" value="{{ old('name') }}" name="name"
                                    class="form-control @error('name') is-invalid @enderror">
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Priority</label>
                                <input type="number" value="{{ old('priority') }}" name="priority"
                                    class="form-control @error('priority') is-invalid @enderror">
                            </div>

                        </div>
                        <div class="row custom-row" style="display: none">

                            <div class="form-group col-lg-4">
                                <label>Slug</label>
                                <input type="text" value="{{ old('slug') }}" name="slug"
                                    class="form-control @error('slug') is-invalid @enderror">
                            </div>

                            <div class="form-group col-lg-4">
                                <label>Name</label>
                                <input type="text" value="{{ old('name') }}" name="name"
                                    class="form-control @error('name') is-invalid @enderror">
                            </div>
                            <div class="form-group col-lg-4">
                                <label>Priority</label>
                                <input type="number" value="{{ old('priority') }}" name="priority"
                                    class="form-control @error('priority') is-invalid @enderror">
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

@push('admin_js')
    <script>
        $(function() {
            $("[name=name]").val('{{ $data[0]->name }}')

            $("[name=slug]").change(function() {
                $("[name=name]").val(data__[$(this).val()])
            })
            $("#is_custom").change(function() {
                if ($(this).is(':checked')) {
                    $(".api-row").hide()
                    $(".custom-row").show()
                    $("input, select, textarea", $(".api-row")).attr("disabled", "disabled");
                    $("input, select, textarea", $(".custom-row")).removeAttr("disabled");
                } else {
                    $(".api-row").show()
                    $("input, select, textarea", $(".custom-row")).attr("disabled", "disabled");
                    $("input, select, textarea", $(".api-row")).removeAttr("disabled");
                    $(".custom-row").hide()
                }
            })

        })
    </script>
@endpush
