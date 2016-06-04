<?php 

namespace Moonlight\Properties;

class IntegerProperty extends BaseProperty
{
	public function __construct($name) {
		parent::__construct($name);

		$this->
		addRule('integer', 'Введите целое число');

		return $this;
	}

	public static function create($name)
	{
		return new self($name);
	}

	public function searchQuery($query)
	{
        $request = $this->getRequest();
		$name = $this->getName();

		$from = $request->input($name.'-from');
        $to = $request->input($name.'-to');

		if (strlen($from)) {
			$from = str_replace(array(',', ' '), array('.', ''), $from);
			$query->where($name, '>=', (int)$from);
		}

		if (strlen($to)) {
			$to = str_replace(array(',', ' '), array('.', ''), $to);
			$query->where($name, '<=', (int)$to);
		}

		return $query;
	}

	public function searching()
	{
		$name = $this->getName();

		$from = \Input::get($name.'_from');
		$to = \Input::get($name.'_to');

		return strlen($from) || strlen($to)
			? true : false;
	}

	public function getSearchView()
	{
		$request = $this->getRequest();
        $name = $this->getName();
        $from = $request->input($name.'-from');
        $to = $request->input($name.'-to');

		if ( ! mb_strlen($from)) $from = null;
		if ( ! mb_strlen($to)) $to = null;

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'from' => $from,
            'to' => $to,
		);

		return view('moonlight::properties.'.$this->getClassName().'.search', $scope)->render();
	}
}
