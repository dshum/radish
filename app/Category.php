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
		return route('catalogue', array('url' => $this->url));
	}
}
