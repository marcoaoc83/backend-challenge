<?php

namespace App\Http\Controllers\API\V1;



class MatrizController extends BaseController
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( )
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Monta a Matriz
        $Matriz[0]=[rand(1,99),rand(1,99),rand(1,99)];
        $Matriz[1]=[rand(1,99),rand(1,99),rand(1,99)];
        $Matriz[2]=[rand(1,99),rand(1,99),rand(1,99)];

        $MatrizTexto[0]=implode(",",$Matriz[0]);
        $MatrizTexto[1]=implode(",",$Matriz[1]);
        $MatrizTexto[2]=implode(",",$Matriz[2]);

        //Pega total de linhas e colunas da matriz
        $TotalLinhas=count($Matriz);
        $TotalColunas=count($Matriz[0]);

        //Variavel diagonal 1 e 2
        $Diagonal1=[];
        $Diagonal2=[];

        for($x=0;$x<$TotalLinhas;$x++) {
            $Linha=$Matriz[$x];
            $Diagonal1[]=$Linha[$x];
            $Diagonal2[]=$Linha[($TotalColunas-1)-$x];
        }

        $SomaDiagonal1=array_sum($Diagonal1);
        $SomaDiagonal2=array_sum($Diagonal2);

        $DiferencaDiagonais=$SomaDiagonal1-$SomaDiagonal2;

        $mensagem=("A Matriz (".implode("||",$MatrizTexto).") tem o resultado de diferenca das diagonais de ".$DiferencaDiagonais);
        return response()->json(['success' => true,'message'=>$mensagem]);
    }

}
