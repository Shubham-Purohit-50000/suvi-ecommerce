@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">Testimonials</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.testimonial.create') }}" class="btn btn-primary">Add New</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Full Name</th>
                    <th>Designation</th>
                    <th>Message</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testimonials as $key => $testimonial)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>
                        @if($testimonial->image)
                            <img src="{{ uploaded_asset($testimonial->image) }}" alt="Image" height="40">
                        @endif
                    </td>
                    <td>{{ $testimonial->full_name }}</td>
                    <td>{{ $testimonial->designation }}</td>
                    <td>{{ $testimonial->message }}</td>
                    <td class="text-right">
                        <a href="{{ route('admin.testimonial.edit', $testimonial->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('admin.testimonial.destroy', $testimonial->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
