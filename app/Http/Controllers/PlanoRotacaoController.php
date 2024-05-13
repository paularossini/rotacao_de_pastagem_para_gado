<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Pastagem;
use App\Http\Controllers\Controller;
use App\Models\PlanoRotacao as ModelsPlanoRotacao;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class PlanoRotacaoController extends Controller
{
    protected array $animal_sem_pasto = [];
    protected array $errorMessages = [];

    public function simularRotacao(Request $request)
    {
        $dias = $request->input('dias');
        ModelsPlanoRotacao::truncate();
        $planoRotacao = $this->distribuiLocacao($dias);
        $planoRotacao = ModelsPlanoRotacao::all();
        $errorMessages = $this->errorMessages;
        return view('simular_rotacao', compact('planoRotacao', 'errorMessages'));
    }
    function distribuiLocacao($dias)
    {
        for ($i = 0; $i <= $dias; $i++) {
            $pastagens = Pastagem::all();
            //!ORGANIZA ANIMAIS
            foreach ($pastagens as $pastagem) {
                // Pega os animais na pastagem atual
                if ($i == 0) {
                    $pastagem->forragem_disponivel = $pastagem->quantidade_forragem;
                    $pastagem->save();
                    $animais = Animal::where('pastagem_atual', $pastagem->id)->get();
                    $qtdAnimais = $animais->count();
                    if ($qtdAnimais > 0) {
                        $this->criaPlanoRotacao($pastagem, $animais, $pastagem->forragem_disponivel, $i);
                    }
                } else {
                    $plano_rotacao_existente = $this->buscaPastoDiaEmPlanoRotacao($pastagem->id, ($i - 1));
                    if ($plano_rotacao_existente) {
                        $animais = $this->getIdAnimal($plano_rotacao_existente);
                        $pastagem->forragem_disponivel = $pastagem->forragem_disponivel - ($animais->sum('necessidade_nutricional'));
                        $this->criaPlanoRotacao($pastagem, $animais, $pastagem->forragem_disponivel, $i);
                        $degradacao = $this->verificarDegradacaoPastagem($pastagem->forragem_disponivel);
                        $nao_suporte = $this->verificaNaoSuportePastagem($animais, $pastagem);
                        if ($degradacao || $nao_suporte) {
                            $this->rotacionar($pastagem, $i);
                        }
                        $pastagem->save();
                    }
                }
            }

            //!ALOCA ANIMAIS SEM PASTO
            if (!empty($this->animal_sem_pasto) && ($i != 0)) {
                $this->procuraPasto($i);
                $this->esvaziaAnimalSemPasto();
            }

            //!RECUPERA PASTAGEM
            $this->recuperaPastagem($i);
        }

        $planoRotacao = ModelsPlanoRotacao::all();
        return $planoRotacao;
    }
    private function buscaPastoDiaEmPlanoRotacao($pastagem_id, $dia)
    {
        if ($dia == 0) {
            $plano_rotacao_existente = ModelsPlanoRotacao::where('dia', 0)
                ->where('pastagem_id', $pastagem_id)
                ->first();
            return $plano_rotacao_existente;
        }
        $plano_rotacao_existente = ModelsPlanoRotacao::where('dia', $dia)
            ->where('pastagem_id', $pastagem_id)
            ->first();
        return $plano_rotacao_existente;
    }
    private function getIdAnimal($plano_rotacao_existente)
    {
        $animais_ids = json_decode($plano_rotacao_existente->animais);
        return Animal::whereIn('id', $animais_ids)->get();
    }
    private function verificarDegradacaoPastagem($forragem_disponivel_pasto)
    {
        $degradacao = ($forragem_disponivel_pasto < 0) ? true : false;
        return $degradacao;
    }
    private function verificaNaoSuportePastagem($animais, $pastagem)
    {
        $nao_suporte = ($animais->count() > $pastagem->capacidade_suporte) ? true : false;
        return $nao_suporte;
    }
    private function criaPlanoRotacao($pastagem, $animais, $forragem_disponivel_pasto, $dia)
    {
        $planoRotacao = new ModelsPlanoRotacao();
        $planoRotacao->dia = $dia;
        $planoRotacao->pastagem_id = $pastagem->id;
        if (is_object($animais)) {
            $animaisIds = $animais->pluck('id')->toArray();
            $planoRotacao->animais = json_encode($animaisIds);
            $planoRotacao->qtd_animal = count($animaisIds);
        } else {
            $planoRotacao->animais = json_encode([$animais]);
            $planoRotacao->qtd_animal = 1;
        }

        if ($pastagem->quantidade_forragem > 0) {
            $planoRotacao->forragem_disponivel = round(($forragem_disponivel_pasto * 100) / $pastagem->quantidade_forragem, 2);
        }
        $planoRotacao->save();
    }
    private function rotacionar($pastagem, $dia)
    {
        //!Degradaçao
        $degradacao = $this->verificarDegradacaoPastagem($pastagem->forragem_disponivel);
        while ($degradacao == true) {
            $this->retirarAnimal($pastagem, $dia, "desc");
            $degradacao = $this->verificarDegradacaoPastagem($pastagem->forragem_disponivel);
        }

        //!Nao suporte 
        $plano_rotacao_existente = $this->buscaPastoDiaEmPlanoRotacao($pastagem->id, $dia);
        $animais = $this->getIdAnimal($plano_rotacao_existente);
        $nao_suporte = $this->verificaNaoSuportePastagem($animais, $pastagem);
        while ($nao_suporte == true) {
            $this->retirarAnimal($pastagem, $dia, "asc");
            $nao_suporte = $this->verificaNaoSuportePastagem($animais, $pastagem);
        }
    }
    private function retirarAnimal($pastagem, $dia, $sort)
    {
        $plano_rotacao_existente = $this->buscaPastoDiaEmPlanoRotacao($pastagem->id, $dia);

        if ($plano_rotacao_existente) {
            $animais = $this->getIdAnimal($plano_rotacao_existente);
            if ($animais->isNotEmpty()) {
                $animaisOrdenados = $sort == "desc" ? $animais->sortByDesc('necessidade_nutricional') : $animais->sortBy('necessidade_nutricional');
                $primeiroAnimal = $animaisOrdenados->first();
                if ($primeiroAnimal) {
                    $primeiroAnimalId = $primeiroAnimal->id;
                    $animaisJson = json_decode($plano_rotacao_existente->animais);
                    $animaisJson = array_diff($animaisJson, [$primeiroAnimalId]);
                    $plano_rotacao_existente->animais = json_encode(array_values($animaisJson));
                    $plano_rotacao_existente->qtd_animal = count($animaisJson);
                    $animal = Animal::find($primeiroAnimalId);
                    $pastagem->forragem_disponivel += $animal->necessidade_nutricional;
                    $plano_rotacao_existente->forragem_disponivel = round(($pastagem->forragem_disponivel * 100) / $pastagem->quantidade_forragem, 2);
                    $plano_rotacao_existente->save();
                    $this->addAnimalSemPasto($primeiroAnimalId);
                }
            }
        }
    }
    private function addAnimalSemPasto($animal_id)
    {
        $this->animal_sem_pasto[] = $animal_id;
    }
    private function esvaziaAnimalSemPasto()
    {
        $this->animal_sem_pasto[] = array();
    }
    private function procuraPasto($dia)
    {
        $errorMessages = [];
        foreach ($this->animal_sem_pasto as $animal_id) {
            $animal = Animal::find($animal_id);
            if ($animal instanceof Animal) {
                $pastagem = $this->encontraPastagem($animal, $dia);
                if ($pastagem) {
                    // Obter o plano de rotação para esta pastagem neste dia
                    if ($pastagem instanceof Pastagem) {
                        $pastagem->forragem_disponivel -= $animal->necessidade_nutricional;
                        $pastagem->save();
                        $this->criaPlanoRotacao($pastagem, $animal->id, $pastagem->forragem_disponivel, $dia);
                    } else {
                        $plano_rotacao = $pastagem;
                        if ($plano_rotacao) {
                            $pasto = Pastagem::where('id', $plano_rotacao->pastagem_id)->first();
                            $pasto->forragem_disponivel -= $animal->necessidade_nutricional;
                            $pasto->save();
                            $plano_rotacao->forragem_disponivel = round(($pasto->forragem_disponivel * 100) / $pasto->quantidade_forragem, 2);
                            $animaisArray = json_decode($plano_rotacao->animais, true);
                            $animaisArray[] = $animal->id;
                            $plano_rotacao->qtd_animal = count($animaisArray);
                            $novoJson = json_encode($animaisArray);
                            $plano_rotacao->animais = $novoJson;
                            $plano_rotacao->save();
                        }
                    }
                } else {
                    $errorMessages[$dia][] = $animal_id;
                }
            }
            foreach ($errorMessages as $dia => $animals) {
                $animalsWithError = implode(', ', array_unique($animals));
                $this->errorMessages[$dia] = "Dia $dia - Impossível rotacionar animal: $animalsWithError. Verifique forragem e capacidade de suporte das Pastagens.";
            }
        }
    }
    private function encontraPastagem($animal, $dia)
    {
        $pastagensOrdenadas = Pastagem::orderByDesc('forragem_disponivel')->get();
        foreach ($pastagensOrdenadas as $pastagem) {
            if ($pastagem->forragem_disponivel >= $animal->necessidade_nutricional) {
                //Verifica se esta em uso
                $plano_rotacao = $this->buscaPastoDiaEmPlanoRotacao($pastagem->id, $dia);

                if ($plano_rotacao) {
                    //Verifica se pode ser usado
                    if ($plano_rotacao->qtd_animal < $pastagem->capacidade_suporte) {
                        return $plano_rotacao;
                    }
                } else {
                    return $pastagem;
                }
            }
        }
        return null;
    }
    private function recuperaPastagem($dia)
    {
        $planoRotacao = ModelsPlanoRotacao::where('dia', $dia)->get();
        if ($planoRotacao->isNotEmpty()) {
            foreach ($planoRotacao as $plano) {
                if ($plano->qtd_animal == 0) {
                    $pastagensNaoEmUso = Pastagem::where('id', $plano->pastagem_id)->get();
                    foreach ($pastagensNaoEmUso as $pasto) {
                        if ($pasto->forragem_disponivel < $pasto->quantidade_forragem) {
                            $recuperacaoDiaria = $pasto->quantidade_forragem / $pasto->dias_recuperacao;
                            $pasto->forragem_disponivel += $recuperacaoDiaria;
                            $forragem_disponivel_pasto = $pasto->forragem_disponivel;
                            $pasto->save();

                            $plano_rotacao = $this->buscaPastoDiaEmPlanoRotacao($pasto->id, $dia);
                            $plano_rotacao->forragem_disponivel = round(($forragem_disponivel_pasto * 100) / $pasto->quantidade_forragem, 2);
                            $plano_rotacao->save();
                        }
                    }
                }
            }
        }
    }

}
