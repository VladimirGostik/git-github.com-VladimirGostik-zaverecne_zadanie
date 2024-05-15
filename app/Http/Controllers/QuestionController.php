<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // zobrazenie formulara na vytvorenie otazky
    public function create()
    {
        return view('questions.create');
    }

     // ulozenie novej otazky do DB
     public function store(Request $request)
     {
         
 
         // Logika pre ukladanie otázky do databázy
         // Vytvoriť nový objekt Question a uložiť ho
 
         return redirect()->route('dashboard')->with('success', 'Question created successfully!');
     }
}
