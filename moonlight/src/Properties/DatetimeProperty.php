<?php 

namespace Moonlight\Properties;

use Carbon\Carbon;
use Moonlight\Main\ElementInterface;

class DatetimeProperty extends BaseProperty
{
	protected $format = 'Y-m-d H:i:s';
	protected $fillNow = false;

	public function __construct($name) {
		parent::__construct($name);

		$this->
		addRule('date_format:"'.$this->format.'"', 'Недопустимый формат даты.');

		return $this;
	}

	public static function create($name)
	{
		return new self($name);
	}

	public function setFillNow()
	{
		$this->fillNow = true;

		return $this;
	}

	public function getFillNow()
	{
		return $this->fillNow;
	}

	public function setElement(ElementInterface $element)
	{
		parent::setElement($element);

		if (is_string($this->value)) {
			try {
				$this->value = Carbon::createFromFormat($this->format, $this->value);
			} catch (\Exception $e) {}
		}

		if ( ! $this->value && $this->getFillNow()) {
			$this->value = Carbon::now();
		}

		return $this;
	}

	public function searchQuery($query)
	{
        $request = $this->getRequest();
		$name = $this->getName();

		$from = $request->input($name.'-from');
        $to = $request->input($name.'-to');

		if ($from) {
			try {
				$from = Carbon::createFromFormat('Y-m-d', $from);
				$query->where($name, '>=', $from->format('Y-m-d'));
			} catch (\Exception $e) {}
		}

		if ($to) {
			try {
				$to = Carbon::createFromFormat('Y-m-d', $to);
				$query->where($name, '<=', $to->format('Y-m-d'));
			} catch (\Exception $e) {}
		}

		return $query;
	}

	public function searching()
	{
		$name = $this->getName();

		$from = \Input::get($name.'_from');
		$to = \Input::get($name.'_to');

		return $from || $to
			? true : false;
	}

	public function getSearchView()
	{
		$request = $this->getRequest();
        $name = $this->getName();
        $from = $request->input($name.'-from');
        $to = $request->input($name.'-to');

		try {
			$from = Carbon::createFromFormat('Y-m-d', $from);
		} catch (\Exception $e) {
			$from = null;
		}

		try {
			$to = Carbon::createFromFormat('Y-m-d', $to);
		} catch (\Exception $e) {
			$to = null;
		}

		$scope = array(
			'name' => $this->getName(),
			'title' => $this->getTitle(),
			'from' => $from,
			'to' => $to,
		);

		return view('moonlight::properties.'.$this->getClassName().'.search', $scope)->render();
	}
    
    public function buildInput()
    {
        $request = $this->getRequest();
        $name = $this->getName();
        
        $date = $request->input($name.'_date');
        $hour = $request->input($name.'_hour');
        $minute = $request->input($name.'_minute');
        $second = $request->input($name.'_second');

        $value = $date
            ? $date.' '.$hour.':'.$minute.':'.$second
            : null;
        
        return $value;
    }
    
    public function set()
	{
        $request = $this->getRequest();
        $name = $this->getName();
        
        $date = $request->input($name.'_date');
        $hour = $request->input($name.'_hour');
        $minute = $request->input($name.'_minute');
        $second = $request->input($name.'_second');
        
         $value = $date
            ? $date.' '.$hour.':'.$minute.':'.$second
            : null;

		$this->element->$name = $value;

		return $this;
	}
}
