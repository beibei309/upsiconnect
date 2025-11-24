<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\StudentService;


use Illuminate\Http\Request;

class PagesController extends Controller
{
public function services()
{
    $categories = Category::withCount('services')->get(); // fetch all categories
    $services = \App\Models\StudentService::with('category', 'user')->latest()->get(); // fetch all services

    return view('services', compact('categories', 'services'));
    
}

    public function index() {
    return view('index', ['categories' => Category::all(), 'q' => request('q')]);
    }

public function home()
{
    $services = \App\Models\StudentService::with('category', 'user')->latest()->get();
    $categories = Category::all();

    return view('services', compact('services', 'categories'));
}


}