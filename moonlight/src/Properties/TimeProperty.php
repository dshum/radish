<?php 

namespace Moonlight\Properties;

use Carbon\Carbon;
use Moonlight\Main\ElementInterface;

class TimeProperty extends BaseProperty
{
	protected static $format = 'H:i:s';

	protected $fillNow = false;

	public function __construct($name) {
		parent::__construct($name);

		$this->
		addRule('date_format:"'.static::$format.'"', 'Недопустимый формат времени.');

		return $this;
	}

	public static function create($name)
	{
		return new self($name);
	}

	public function setFillNow($fillNow)
	{
		$this->fillNow = $fillNow;

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

		if ($this->value) {
			$this->value = [
				'value' => $this->value->format(static::$format),
				'time' => $this->value->toTimeString(),
				'hour' => $this->value->hour,
				'minute' => $this->value->minute,
				'second' => $this->value->second,
				'human' => $this->value->format('H:i:s')
			];
		}

		return $this;
	}

	public function searchQuery($query)
	{
		return $query;
	}

	public function getSearchView()
	{
		return null;
	}
}
