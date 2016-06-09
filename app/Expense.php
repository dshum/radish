<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\ElementTrait;

class Expense extends Model implements ElementInterface
{
    use ElementTrait;
    
    public function getDates()
	{
		return array(
            'date',
			'created_at',
			'updated_at',
			'deleted_at',
		);
	}
    
    public function getTouchListView()
    {        
        $str = '';
        
        if ($this->category) {
            $str .= '<div class="label one"><span class="glyphicons glyphicons-tag"></span>'. $this->category->name .'</div>';
        }
        
        if ($this->source) {
            $str .= '<div class="label one"><span class="glyphicons glyphicons-tag"></span>'. $this->source->name .'</div>';
        }
        
        if ($this->sum) {
            $str .= '<div class="label number"><span class="glyphicons glyphicons-calculator"></span>Сумма: '. $this->sum .' руб.</div>';
        }
        
        return $str;
    }
    
    public function category()
	{
		return $this->belongsTo('App\ExpenseCategory', 'category_id');
	}
    
    public function source()
	{
		return $this->belongsTo('App\ExpenseSource', 'source_id');
	}
}
