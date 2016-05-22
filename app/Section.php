<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\ElementTrait;

class Section extends Model implements ElementInterface
{
    use ElementTrait;

    public function getHref()
	{
		return URL::route($this->url);
	}
}
