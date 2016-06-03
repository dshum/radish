<?php 

namespace Moonlight\Properties;

class PasswordProperty extends BaseProperty
{
	public static function create($name)
	{
		return new self($name);
	}

	public function getSearchView()
	{
		return null;
	}
}
