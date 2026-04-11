<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function edit()
    {
        $faqs = Faq::all();
        return view('faqs.edit', compact('faqs'));
    }
    public function update(Request $request, $slug)
    {
        $faq = Faq::where('slug', $slug)->firstOrFail();
        $data = [];

        foreach ($request->input('ques', []) as $index => $question) {

            $answer = $request->input("answer.$index");

            if (!empty($question) && !empty($answer)) {
                $data[] = [
                    'ques'   => $question,
                    'answer' => $answer,
                ];
            }
        }

        $faq->content = json_encode([
            'faqs' => $data
        ]);

        $faq->save();

        return back()->with('success', 'FAQ updated successfully');
    }
}
