<?php 

namespace Moonlight\Properties;

use Illuminate\Support\Facades\Log;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\Item;

class OrderProperty extends BaseProperty
{
	public static function create($name)
	{
		return new self($name);
	}

	public function setItem(Item $item)
	{
		$item->setOrderProperty($this->name);

		parent::setItem($item);

		return $this;
	}

	public function getTitle()
	{
		return 'Порядок';
	}

	public function getReadonly()
	{
		return false;
	}

	public function getHidden()
	{
		return true;
	}

	public function set()
	{   
		if ( ! $this->element instanceof ElementInterface) return $this;

		$name = $this->getName();

		try {
			$maxOrder = $this->element->max($name);
			$this->element->$name = (int)$maxOrder + 1;
		} catch (\Exception $e) {
			$this->element->$name = 1;
		}

		return $this;
	}
    
    public function getEditView()
	{
		return null;
	}
}
