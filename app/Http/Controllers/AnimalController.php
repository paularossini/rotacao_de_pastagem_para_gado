<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\Pastagem;

class AnimalController extends Controller{
    public function index(){
        $animais = Animal::all(); 
        $pastagens = Pastagem::all(); 

        return view('lista_animais', compact('animais', 'pastagens'));
    }    
    public function create(){
        return view('animais.create');
    }
    public function store(Request $request){
        $request->validate([
            'peso' => 'required|numeric|min:1',
            'necessidade_nutricional' => 'required|numeric|min:1',
            'idade' => 'required|integer|min:1',
            'pastagem_atual' => 'required|integer|min:1'
        ]);

        $animal = new Animal();
        $animal->peso = $request->input('peso');
        $animal->necessidade_nutricional = $request->input('necessidade_nutricional');
        $animal->idade = $request->input('idade');
        $animal->pastagem_atual = $request->input('pastagem_atual');
        $animal->save();

        return redirect()->route('lista_animais');
    }

    public function show(Animal $animal){
        return view('animais.show', compact('animal'));
    }
    public function edit(Animal $animal){
        return view('lista_animais', ['animal' => $animal]);
    }
    
    public function update(Request $request, Animal $animal){
        $animal->update($request->all());
        return redirect()->route('lista_animais');
    }

    public function destroy(Animal $animal){
        $animal->delete();
        return redirect()->route('lista_animais');
    }
}