<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\ElementTrait;
use App\Category;

class Subcategory extends Model implements ElementInterface
{
    use ElementTrait;

    public function getHref()
	{
		$category = $this->category;

		return $category->getHref().'/'.$this->url;
	}

	public function category()
	{
		return $this->belongsTo('App\Category', 'category_id');
	}
}
