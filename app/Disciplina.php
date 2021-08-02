<?php

namespace SimuladoENADE;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    //
    public function curso(){
    	return $this->belongsTo('SimuladoENADE\Curso');
    }
    
    public function questaos(){
        return $this->hasMany('\SimuladoENADE\Questao', 'disciplina_id', 'id');
    }
    
    public function questao_discursivas(){
        return $this->hasMany('\SimuladoENADE\QuestaoDiscursiva', 'disciplina_id', 'id');
    }

    public function questaos_facil(){
        return $this->questaos()->where([
            ['disciplina_id','=',$this->id],
            ['dificuldade','=',1]]);
    }

    public function questaos_medio(){
        return $this->questaos()->where([
            ['disciplina_id','=',$this->id],
            ['dificuldade','=',2]]);
    }

    public function questaos_dificil(){
        return $this->questaos()->where([
            ['disciplina_id','=',$this->id],
            ['dificuldade','=',3]]);
    }

    protected $fillable = ['nome', 'curso_id'];
    
    public static $rules = [
    	'nome' => 'required',
    	//'curso_id' => 'required'
    ];

    public static $messages = [
    	'required' => 'O campo :attribute deve ser preenchido na forma correta'
    ];

    public static function queryToArrayIds($values) {
        
        $array = [];

        foreach($values as $value)
            array_push($array, $value->id);
        
        return $array;
    }
    
}
