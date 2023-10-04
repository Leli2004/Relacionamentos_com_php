<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Turma;
use App\Models\Aluno;
use App\Models\CategoriaAluno;
use App\Models\Matricula;

class RelacionamentoController extends Controller
{
    public function index(){

       //$aluno = Aluno::with('matricula')->get();
       $matricula = Matricula::with('curso','turma','aluno')->get();
        //dd($aluno);,
        //dd($aluno[0]->matricula->data_matricula); // aluno indice 0 > matricula delu > data matricula
        //dd($aluno[2]->categoria->nome); // aluno indice 2 > categoria delu > nome da categoria
        dd( $matricula[0]->curso->nome. " - ".
            $matricula[0]->turma->nome. " - ".
            $matricula[0]->matricula->nome  );
    }

    public function store(){

        Curso::create([
            'nome'=>"Técnico em Informática",
            'requisito'=>"Fundamental",
            'carga_horaria'=>2000,
            'valor'=>0.0,
        ]);

        $curso = Curso::find(1);
        Turma::create([
            'nome'=>"Tec_2020_1",
            'curso_id'=>$curso->id,
            'codigo'=>"MTEC_2020/1",
            'data_inicio'=>"2020/02/10",
            'data_fim'=>"2023/12/20",
            'carga_horaria'=>36,
        ]);
       // dd($turma);

        $catAluno = CategoriaAluno::create(
            [ 'nome'=>"Graduação",
            'nivel'=>"Superior", ]
        );

        $aluno = Aluno::create([
            'nome'=>"Danieli do Nascimento Dalla Vecchia",
            'data_nascimento'=>'2004/06/07',
            'cpf'=>"139.767.909-31",
            'email'=>"dani@gmail.com",
            'telefone'=>"49991360963",
            'categoria_aluno_id'=>$catAluno->id,
        ]);
        dd($aluno);

        $curso = Curso::find(1);
        $turma = Turma::find(1);
        $aluno = Aluno::find(1);

        $matricula = Matricula::create([
            'curso_id'=>$curso->id,
            'turma_id'=>$turma->id,
            'aluno_id'=>$aluno->id,
            'data_matricula'=>date("Y-m-d"),
        ]);
    }
}
