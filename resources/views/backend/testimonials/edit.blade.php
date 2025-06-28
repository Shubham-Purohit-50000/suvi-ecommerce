@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">Edit Testimonial</h1>
        </div>
    </div>
</div>
<div class="card">
    <form class="p-4" action="{{ route('admin.testimonial.update', $testimonial->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Full Name <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="full_name" value="{{ old('full_name', $testimonial->full_name) }}" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Image</label>
            <div class="col-md-9">
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                            {{ translate('Browse')}}
                        </div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="image" class="selected-files" value="{{ old('image', $testimonial->image) }}">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Designation <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="designation" value="{{ old('designation', $testimonial->designation) }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Message</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="message" rows="4">{{ old('message', $testimonial->message) }}</textarea>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection
