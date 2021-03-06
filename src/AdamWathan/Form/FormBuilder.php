<?php namespace AdamWathan\Form;

use AdamWathan\Form\Elements\Text;
use AdamWathan\Form\Elements\Password;
use AdamWathan\Form\Elements\Checkbox;
use AdamWathan\Form\Elements\RadioButton;
use AdamWathan\Form\Elements\Submit;
use AdamWathan\Form\Elements\Select;
use AdamWathan\Form\Elements\TextArea;
use AdamWathan\Form\Elements\Label;
use AdamWathan\Form\Elements\FormOpen;
use AdamWathan\Form\Elements\Hidden;
use AdamWathan\Form\OldInput\OldInputInterface;
use AdamWathan\Form\ErrorStore\ErrorStoreInterface;

class FormBuilder
{
	private $oldInput;
	private $errorStore;
	private $csrfToken;

	public function setOldInputProvider(OldInputInterface $oldInputProvider)
	{
		$this->oldInput = $oldInputProvider;
	}

	public function setErrorStore(ErrorStoreInterface $errorStore)
	{
		$this->errorStore = $errorStore;
	}

	public function setToken($token) {
		$this->csrfToken = $token;
	}

	public function open()
	{
		return new FormOpen;
	}

	public function close()
	{
		return '</form>';
	}
	
	public function text($name)
	{
		$text = new Text($name);
		
		$value = $this->getValueFor($name);

		if ($value) {
			$text->value($value);
		}

		return $text;
	}

	protected function getValueFor($name)
	{
		if ($this->hasOldInput($name)) {
			return $this->getOldInput($name);
		}

		return '';
	}

	protected function hasOldInput($name)
	{
		if ( ! isset($this->oldInput)) {
			return false;
		}

		return $this->oldInput->hasOldInput($name);
	}

	protected function getOldInput($name)
	{
		return $this->oldInput->getOldInput($name);
	}

	public function hasError($name)
	{
		if ( ! isset($this->errorStore)) {
			return false;
		}

		return $this->errorStore->hasError($name);
	}

	public function getError($name, $format = null)
	{
		if ( ! isset($this->errorStore)) {
			return null;
		}

		$message = $this->errorStore->getError($name);

		if ($format) {
			$message = str_replace(':message', $message, $format);
		}

		return $message;
	}
	
	public function password($name)
	{
		return new Password($name);
	}
	
	public function checkbox($name, $value = 1)
	{
		$checkbox = new Checkbox($name, $value);

		$oldValue = $this->getValueFor($name);

		if ($value == $oldValue) {
			$checkbox->check();
		}

		return $checkbox;
	}
	
	public function radio($name, $value = null)
	{
		$value = is_null($value) ? $name : $value;

		$radio = new RadioButton($name, $value);

		$oldValue = $this->getValueFor($name);

		if ($value == $oldValue) {
			$radio->check();
		}

		return $radio;
	}
	
	public function submit($value = 'Submit')
	{
		return new Submit($value);
	}

	public function select($name, $options = array())
	{
		$select = new Select($name, $options);

		$selected = $this->getValueFor($name);
		$select->select($selected);

		return $select;
	}

	public function textarea($name)
	{
		$textarea = new TextArea($name);
		
		$value = $this->getValueFor($name);

		if ($value) {
			$textarea->value($value);
		}

		return $textarea;
	}

	public function label($label)
	{
		return new Label($label);
	}

	public function hidden($name)
	{
		return new Hidden($name);
	}

	public function token()
	{
		$token = $this->hidden('_token');

		if (isset($this->csrfToken)) {
			$token->value($this->csrfToken);
		}

		return $token;
	}
}