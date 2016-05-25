<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\ElementTrait;

class Good extends Model implements ElementInterface
{
    use ElementTrait;
    
    public function getTouchListView()
    {
        $str = '<div class="label number"><span class="glyphicons glyphicons-calculator"></span>Цена: '. $this->price .' руб.</div>';
        
        if ($this->supplier_price) {
            $str .= '<div class="label number"><span class="glyphicons glyphicons-calculator"></span>Цена поставщика: '. $this->supplier_price .' руб.</div>';
        }
        
        if ($this->image) {
            $str .= '<div><img src="/assets/goods/'.$this->image.'" width="120" alt="" /></div>';
        }
        
        return $str;
    }
}
