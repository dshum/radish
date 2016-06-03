<?php 

namespace Moonlight\Properties;

class TextfieldProperty extends BaseProperty 
{
	public static function create($name)
	{
		return new self($name);
	}
}
