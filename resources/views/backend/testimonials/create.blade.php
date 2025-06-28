@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">Add Testimonial</h1>
        </div>
    </div>
</div>
<div class="card">
    <form class="p-4" action="{{ route('admin.testimonial.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Full Name <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="full_name" value="{{ old('full_name') }}" required>
            </div>
        </div>
        

        <!-- Image -->
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Image')}}</label>
                <div class="col-sm-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{translate('Choose File')}}</div>
                        <input type="hidden" name="image" class="selected-files" value="{{ $content->banner_image ?? '' }}">
                    </div>
                    {{--<div class="file-preview">
                        @if($content->banner_image)
                            <div class="d-flex justify-content-start mt-2">
                                <div class="img-fit img-fit-xs" style="background-image:url('{{ uploaded_asset($content->banner_image) }}')"></div>
                            </div>
                        @endif--}}
                    </div>
                </div>
            </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Designation <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="designation" value="{{ old('designation') }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Message</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="message" rows="4">{{ old('message') }}</textarea>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
