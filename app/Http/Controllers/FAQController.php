<?php
// app/Http/Controllers/FAQController.php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = FAQ::all();
        return response()->json($faqs);
    }

    public function show($id)
    {
        $faq = FAQ::findOrFail($id);
        return response()->json($faq);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq = FAQ::create($data);

        return response()->json($faq, 201);
    }

    public function update(Request $request, $id)
    {
        $faq = FAQ::findOrFail($id);

        $data = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq->update($data);

        return response()->json($faq, 200);
    }

    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        return response()->json(null, 204);
    }
}
