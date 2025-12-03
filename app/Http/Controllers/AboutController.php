<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    // FRONTEND page
    public function show()
    {
        $about = About::first();
        return view('about.show', compact('about'));
    }

    // ADMIN edit form
    public function edit()
    {
        $about = About::first();
        return view('admin.about.edit', compact('about'));
    }

    // ADMIN update
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image',
        ]);

        $about = About::first();

        if (!$about) {
            $about = new About();
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('about', 'public');
            $about->image_path = $path;
        }

        $about->title = $request->title;
        $about->description = $request->description;
        $about->save();

        return redirect()->back()->with('success', 'About updated successfully!');
    }

}
