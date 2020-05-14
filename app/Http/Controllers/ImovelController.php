<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imovel;
use Validator;
use Illuminate\Support\Facades\DB; // DB:table
use Illuminate\Pagination\Paginator; // paginator do index
class ImovelController extends Controller
{
   



/** Criacao do Validador  backend */
    protected function validarImovel($request){
        $validator = Validator::make($request->all(), [
            "descricao" => "required",
            "logradouroEndereco"=> "required",
            "bairroEndereco" => "required",
            "numeroEndereco" => "required | numeric",
            "cepEndereco" => "required",
            "cidadeEndereco" => "required",
            "preco" => "required | numeric",
            "qtdQuartos" => "required | numeric ",
            "tipo" => "required",
            "finalidade" => "required"
        ]);
        return $validator;
    }






    
    public function index(Request $request)
    {
        $qtd = $request['qtd'] ?: 2;
        $page = $request['page'] ?: 1;
        $buscar = $request['buscar'];
        $tipo = $request['tipo'];
 
        Paginator::currentPageResolver(function () use ($page){
            return $page;
        });
 
        if($buscar){
            $imoveis = DB::table('imoveis')->where('cidadeEndereco', '=', $buscar)->paginate($qtd);
        }else{  
            if($tipo){
                $imoveis = DB::table('imoveis')->where('tipo', '=', $tipo)->paginate($qtd);
            }else{
                $imoveis = DB::table('imoveis')->paginate($qtd);
            }
        }
        $imoveis = $imoveis->appends(Request::capture()->except('page'));
 
        return view('imoveis.index', compact('imoveis'));
    }


    public function create()
    {
        return view('imoveis.create');
    }



    public function store(Request $request)
    {

        /** Usando o validador */
        $validator = $this->validarImovel($request);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }


        $dados = $request->all(); // pega as informacoes 
        Imovel::create($dados); // cria no banco passando as infos
        return redirect()->route('imoveis.index'); // redireciona pro index
    }

 


    public function show($id)
    {
        $imovel = Imovel::find($id);
        return view('imoveis.show', compact('imovel'));
    }


  
    public function edit($id)
    {
        $imovel = Imovel::find($id);
        return view('imoveis.edit', compact('imovel'));
    }



    public function update(Request $request, $id)
    {
        $validator = $this->validarImovel($request);
     
        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }
         
        $imovel = Imovel::find($id);
        $dados = $request->all();
        $imovel->update($dados);
         
        return redirect()->route('imoveis.index');  
    }


    public function remover($id){
    $imovel = Imovel::find($id);
    return view('imoveis.remove', compact('imovel'));
    }


    public function destroy($id)
    {
        Imovel::find($id)->delete();
        return redirect()->route('imoveis.index');
    }
}
