<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Display the help page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // You can pass any data to the view if needed, e.g., FAQs or user-specific content
        // For a public page, keep it simple and avoid assuming Auth::user() unless checked
        $data = [
            'title' => 'Help & Support',
            // Add more data as needed, e.g., $faqs = Faq::all();
        ];

        return view('help.index', $data);  // Assuming your view is at resources/views/help/index.blade.php
    }
}