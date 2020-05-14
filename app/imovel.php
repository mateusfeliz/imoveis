<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class imovel extends Model
{
    protected $fillable = [
        "descricao", "logradouroEndereco", "bairroEndereco", "numeroEndereco", "cepEndereco", 
        "cidadeEndereco", "preco", "qtdQuartos", "tipo", "finalidade"
      ];
      protected $table = "imoveis";
}
