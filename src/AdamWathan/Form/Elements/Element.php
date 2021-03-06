<?php namespace AdamWathan\Form\Elements;

abstract class Element
{
	protected $attributes = array();

	protected function setAttribute($attribute, $value = null)
	{
		if (is_null($value)) {
			return;
		}
		
		$this->attributes[$attribute] = $value;
	}

	protected function removeAttribute($attribute) {
		unset($this->attributes[$attribute]);
	}

	public function attribute($attribute, $value)
	{
		$this->setAttribute($attribute, $value);
		return $this;
	}

	public function clear($attribute)
	{
		if (! isset($this->attributes[$attribute])) {
			return $this;
		}

		$this->removeAttribute($attribute);
		return $this;
	}

	public function addClass($class)
	{
		if (isset($this->attributes['class'])) {
			$class = $this->attributes['class'] . ' ' . $class;
		}

		$this->setAttribute('class', $class);
		return $this;
	}

	public function id($id)
	{
		$this->setId($id);
		return $this;
	}

	protected function setId($id)
	{
		$this->setAttribute('id', $id);
	}	

	public abstract function render();

	public function __toString()
	{
		return $this->render();
	}

	protected function renderAttributes()
	{
		$result = '';

		foreach ($this->attributes as $attribute => $value) {
			$result .= " {$attribute}=\"$value\"";
		}

		return $result;
	}
}