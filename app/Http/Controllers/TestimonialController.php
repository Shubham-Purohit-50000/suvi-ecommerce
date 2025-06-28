<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('backend.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('backend.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'image' => 'nullable|numeric',
            'designation' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        Testimonial::create($request->only(['full_name', 'image', 'designation', 'message']));
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('backend.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'designation' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update($request->only(['full_name', 'image', 'designation', 'message']));
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial deleted successfully.');
    }
}
