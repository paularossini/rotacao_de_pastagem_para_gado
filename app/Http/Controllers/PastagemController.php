<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pastagem;

class PastagemController extends Controller{
    public function index(){
        $pastagens = Pastagem::all(); 
        return view('lista_pastagens', compact('pastagens')); 
    }

    public function store(Request $request){
        $request->validate([
            'capacidade_suporte' => 'required|integer|min:1',
            'quantidade_forragem' => 'required|numeric|min:0',
            'dias_recuperacao' => 'required|integer|min:1',
        ]);

        $pastagem = new Pastagem();
        $pastagem->capacidade_suporte = $request->input('capacidade_suporte');
        $pastagem->quantidade_forragem = $request->input('quantidade_forragem');
        $pastagem->dias_recuperacao = $request->input('dias_recuperacao');
        $pastagem->forragem_disponivel = $request->input('quantidade_forragem');
        $pastagem->save();

        return redirect()->route('lista_pastagens');
    }

    public function show(Pastagem $pastagem){
        return view('pastagens.show', compact('pastagem'));
    }

    public function edit(Pastagem $pastagem){
        return view('pastagens.edit', compact('pastagem'));
    }

    public function update(Request $request, Pastagem $pastagem){
        $pastagem->update($request->all());
        return redirect()->route('lista_pastagens');
    }

    public function destroy(Pastagem $pastagem){
        $pastagem->delete();
        return redirect()->route('lista_pastagens');
    }
}