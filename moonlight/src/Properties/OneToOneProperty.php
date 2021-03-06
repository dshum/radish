<?php 

namespace Moonlight\Properties;

use Moonlight\Main\Item;
use Moonlight\Main\ElementInterface;
use Moonlight\Main\Element;

class OneToOneProperty extends BaseProperty 
{
	protected $relatedClass = null;
	protected $parent = false;

	public function __construct($name) {
		parent::__construct($name);

		$this->
		addRule('integer', 'Идентификатор элемента должен быть целым числом.');

		return $this;
	}

	public static function create($name)
	{
		return new self($name);
	}

	public function getRefresh()
	{
		return true;
	}

	public function setRelatedClass($relatedClass)
	{
		Item::assertClass($relatedClass);

		$this->relatedClass = $relatedClass;

		return $this;
	}

	public function getRelatedClass()
	{
		return $this->relatedClass;
	}

	public function setParent($parent)
	{
		$this->parent = $parent;

		return $this;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function setElement(ElementInterface $element)
	{
		$this->element = $element;

		$site = \App::make('site');

		$relatedClass = $this->getRelatedClass();
		$relatedItem = $site->getItemByName($relatedClass);
		$mainProperty = $relatedItem->getMainProperty();
		$id = $this->element->{$this->getName()};

		if ($relatedClass && $id) {
			$this->value = $relatedClass::find($id);
		} else {
            $this->value = null;
        }

		if ($this->value) {
			$this->value->id = $this->value->id;
			$this->value->classId = $this->value->getClassId();
			$this->value->value = $this->value->{$mainProperty};
		}

		return $this;
	}

	public function searchQuery($query)
	{
        $request = $this->getRequest();
		$name = $this->getName();

		$value = (int)$request->input($name);

		if ($value) {
			$query->where($name, $value);
		}

		return $query;
	}

	public function searching()
	{
		$request = $this->getRequest();
        $name = $this->getName();

		$value = $request->input($name);

		return $value
			? true : false;
	}

	public function getEditView()
	{
		$site = \App::make('site');

		$relatedClass = $this->getRelatedClass();
		$relatedItem = $site->getItemByName($relatedClass);

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'value' => $this->getValue(),
			'readonly' => $this->getReadonly(),
			'required' => $this->getRequired(),
			'relatedClass' => $relatedItem->getNameId(),
		);

		return view('moonlight::properties.'.$this->getClassName().'.edit', $scope)->render();
	}
    
    public function getCopyView()
	{
		$site = \App::make('site');

		$relatedClass = $this->getRelatedClass();
		$relatedItem = $site->getItemByName($relatedClass);

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'value' => $this->getValue(),
			'readonly' => $this->getReadonly(),
			'required' => $this->getRequired(),
			'relatedClass' => $relatedItem->getNameId(),
		);

		return view('moonlight::properties.'.$this->getClassName().'.copy', $scope)->render();
	}

	public function getMoveView()
	{
		$site = \App::make('site');

		$relatedClass = $this->getRelatedClass();
		$relatedItem = $site->getItemByName($relatedClass);

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'value' => $this->getValue(),
			'readonly' => $this->getReadonly(),
			'required' => $this->getRequired(),
			'relatedClass' => $relatedItem->getNameId(),
		);

		return view('moonlight::properties.'.$this->getClassName().'.move', $scope)->render();
	}

	public function getSearchView()
	{
        $site = \App::make('site');
        
		$request = $this->getRequest();
        $name = $this->getName();
        $id = (int)$request->input($name);
        $relatedClass = $this->getRelatedClass();
		$relatedItem = $site->getItemByName($relatedClass);
        $mainProperty = $relatedItem->getMainProperty();

		$element = $id 
            ? $relatedClass::find($id)
            : null;
        
        $value = $element
            ? [
                'id' => $element->id, 
                'name' => $element->{$mainProperty}
            ] : null;

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'value' => $value,
			'open' => $element !== null,
            'relatedClass' => $relatedItem->getNameId(),
		);

		return view('moonlight::properties.'.$this->getClassName().'.search', $scope)->render();
	}

	public function isOneToOne()
	{
		return true;
	}
}
