<?php

use AdamWathan\Form\FormBuilder;

class FormBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->form = new FormBuilder;
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function testFormBuilderCanBeCreated()
	{
		$formBuilder = new FormBuilder;
	}

	public function testFormOpen()
	{
		$expected = '<form method="POST">';
		$result = $this->form->open();
		$this->assertEquals($expected, $result);
	}

	public function testCanCloseForm()
	{
		$expected = '</form>';
		$result = $this->form->close();
		$this->assertEquals($expected, $result);
	}

	public function testTextBox()
	{
		$expected = '<input type="text" name="email">';
		$result = (string)$this->form->text('email');
		$this->assertEquals($expected, $result);

		$expected = '<input type="text" name="first_name">';
		$result = (string)$this->form->text('first_name');
		$this->assertEquals($expected, $result);
	}

	public function testPassword()
	{
		$expected = '<input type="password" name="password">';
		$result = (string)$this->form->password('password');
		$this->assertEquals($expected, $result);

		$expected = '<input type="password" name="password_confirmed">';
		$result = (string)$this->form->password('password_confirmed');
		$this->assertEquals($expected, $result);
	}

	public function testCheckbox()
	{
		$expected = '<input type="checkbox" name="terms" value="1">';
		$result = (string)$this->form->checkbox('terms');
		$this->assertEquals($expected, $result);

		$expected = '<input type="checkbox" name="terms" value="agree">';
		$result = (string)$this->form->checkbox('terms', 'agree');
		$this->assertEquals($expected, $result);
	}

	public function testRadio()
	{
		$expected = '<input type="radio" name="terms" value="terms">';
		$result = (string)$this->form->radio('terms');
		$this->assertEquals($expected, $result);

		$expected = '<input type="radio" name="terms" value="agree">';
		$result = (string)$this->form->radio('terms', 'agree');
		$this->assertEquals($expected, $result);
	}

	public function testSubmit()
	{
		$expected = '<input type="submit" value="Sign In">';
		$result = (string)$this->form->submit('Sign In');
		$this->assertEquals($expected, $result);

		$expected = '<input type="submit" value="Log In">';
		$result = (string)$this->form->submit('Log In');
		$this->assertEquals($expected, $result);
	}

	public function testSelect()
	{
		$expected = '<select name="color"><option value="red">Red</option><option value="blue">Blue</option></select>';
		$result = (string)$this->form->select('color', array('red' => 'Red', 'blue' => 'Blue'));
		$this->assertEquals($expected, $result);

		$expected = '<select name="fruit"><option value="apple">Granny Smith</option><option value="berry">Blueberry</option></select>';
		$result = (string)$this->form->select('fruit', array('apple' => 'Granny Smith', 'berry' => 'Blueberry'));
		$this->assertEquals($expected, $result);
	}

	public function testTextArea()
	{
		$expected = '<textarea name="bio" rows="10" cols="50"></textarea>';
		$result = (string)$this->form->textarea('bio');
		$this->assertEquals($expected, $result);

		$expected = '<textarea name="description" rows="10" cols="50"></textarea>';
		$result = (string)$this->form->textarea('description');
		$this->assertEquals($expected, $result);
	}

	public function testLabel()
	{		
		$expected = '<label>Email</label>';
		$result = (string)$this->form->label('Email');
		$this->assertEquals($expected, $result);

		$expected = '<label>First Name</label>';
		$result = (string)$this->form->label('First Name');
		$this->assertEquals($expected, $result);
	}

	public function testRenderTextWithOldInput()
	{
		$oldInput = Mockery::mock('AdamWathan\Form\OldInput\OldInputInterface');
		$oldInput->shouldReceive('hasOldInput')->with('email')->andReturn(true);
		$oldInput->shouldReceive('getOldInput')->with('email')->andReturn('example@example.com');

		$this->form->setOldInputProvider($oldInput);

		$expected = '<input type="text" name="email" value="example@example.com">';
		$result = (string)$this->form->text('email');
		$this->assertEquals($expected, $result);
	}

	public function testRenderCheckboxWithOldInput()
	{
		$oldInput = Mockery::mock('AdamWathan\Form\OldInput\OldInputInterface');
		$oldInput->shouldReceive('hasOldInput')->with('terms')->andReturn(true);
		$oldInput->shouldReceive('getOldInput')->with('terms')->andReturn('agree');

		$this->form->setOldInputProvider($oldInput);

		$expected = '<input type="checkbox" name="terms" value="agree" checked="checked">';
		$result = (string)$this->form->checkbox('terms', 'agree');
		$this->assertEquals($expected, $result);
	}

	public function testRenderRadioWithOldInput()
	{
		$oldInput = Mockery::mock('AdamWathan\Form\OldInput\OldInputInterface');
		$oldInput->shouldReceive('hasOldInput')->with('color')->andReturn(true);
		$oldInput->shouldReceive('getOldInput')->with('color')->andReturn('green');

		$this->form->setOldInputProvider($oldInput);

		$expected = '<input type="radio" name="color" value="green" checked="checked">';
		$result = (string)$this->form->radio('color', 'green');
		$this->assertEquals($expected, $result);
	}

	public function testRenderSelectWithOldInput()
	{
		$oldInput = Mockery::mock('AdamWathan\Form\OldInput\OldInputInterface');
		$oldInput->shouldReceive('hasOldInput')->with('color')->andReturn(true);
		$oldInput->shouldReceive('getOldInput')->with('color')->andReturn('blue');

		$this->form->setOldInputProvider($oldInput);

		$expected = '<select name="color"><option value="red">Red</option><option value="blue" selected>Blue</option></select>';
		$result = (string)$this->form->select('color', array('red' => 'Red', 'blue' => 'Blue'));
		$this->assertEquals($expected, $result);
	}

	public function testRenderTextAreaWithOldInput()
	{
		$oldInput = Mockery::mock('AdamWathan\Form\OldInput\OldInputInterface');
		$oldInput->shouldReceive('hasOldInput')->with('bio')->andReturn(true);
		$oldInput->shouldReceive('getOldInput')->with('bio')->andReturn('This is my bio');

		$this->form->setOldInputProvider($oldInput);

		$expected = '<textarea name="bio" rows="10" cols="50">This is my bio</textarea>';
		$result = (string)$this->form->textarea('bio');
		$this->assertEquals($expected, $result);
	}

	public function testNoErrorStoreReturnsNull()
	{
		$expected = null;
		$result = $this->form->getError('email');
		$this->assertEquals($expected, $result);
	}

	public function testCanCheckForErrorMessage()
	{
		$errorStore = Mockery::mock('AdamWathan\Form\ErrorStore\ErrorStoreInterface');
		$errorStore->shouldReceive('hasError')->with('email')->andReturn(true);

		$this->form->setErrorStore($errorStore);

		$result = $this->form->hasError('email');
		$this->assertTrue($result);

		$errorStore = Mockery::mock('AdamWathan\Form\ErrorStore\ErrorStoreInterface');
		$errorStore->shouldReceive('hasError')->with('email')->andReturn(false);

		$this->form->setErrorStore($errorStore);

		$result = $this->form->hasError('email');
		$this->assertFalse($result);
	}

	public function testCanRetrieveErrorMessage()
	{
		$errorStore = Mockery::mock('AdamWathan\Form\ErrorStore\ErrorStoreInterface');
		$errorStore->shouldReceive('getError')->with('email')->andReturn('The e-mail address is invalid.');

		$this->form->setErrorStore($errorStore);

		$expected = 'The e-mail address is invalid.';
		$result = $this->form->getError('email');
		$this->assertEquals($expected, $result);
	}

	public function testCanRetrieveFormattedErrorMessage()
	{
		$errorStore = Mockery::mock('AdamWathan\Form\ErrorStore\ErrorStoreInterface');
		$errorStore->shouldReceive('getError')->with('email')->andReturn('The e-mail address is invalid.');

		$this->form->setErrorStore($errorStore);

		$expected = '<span class="error">The e-mail address is invalid.</span>';
		$result = $this->form->getError('email', '<span class="error">:message</span>');
		$this->assertEquals($expected, $result);
	}

	public function testHidden()
	{
		$expected = '<input type="hidden" name="secret">';
		$result = (string)$this->form->hidden('secret');
		$this->assertEquals($expected, $result);

		$expected = '<input type="hidden" name="token">';
		$result = (string)$this->form->hidden('token');
		$this->assertEquals($expected, $result);
	}

	public function testCanSetCsrfToken()
	{
		$this->form->setToken('12345');
	}

	public function testCanRenderCsrfToken()
	{
		$this->form->setToken('12345');

		$expected = '<input type="hidden" name="_token" value="12345">';
		$result = (string)$this->form->token();
		$this->assertEquals($expected, $result);
	}
}