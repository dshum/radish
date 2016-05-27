<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\ElementTrait;

class Category extends Model implements ElementInterface
{
    use ElementTrait;

    public function getHref()
	{
		return '/'.$this->url;
	}
    
    public function getTouchListView()
    {
        $str = '';
        
        if ($this->image) {
            $str .= '<div><img src="/assets/categories/'.$this->image.'" width="120" alt="" /></div>';
        }
        
        return $str;
    }
}
