<?php
namespace GDO\Captcha;

use GDO\Form\GDT_Form;
use GDO\Core\Application;
use GDO\Core\GDT_Template;
use GDO\UI\WithIcon;
use GDO\Core\GDT_String;
use GDO\Captcha\Method\Validate;
use GDO\Session\GDO_Session;

/**
 * Google recaptcha implementation.
 * 
 * @author gizmore
 * @version 6.10.4
 * @since 3.4.0
 */
class GDT_Captcha extends GDT_String
{
	use WithIcon;
	
	public $notNull = true;
	
	public $cli = false;
	
	public function defaultName() { return 'captcha'; }
	
	public function addFormValue(GDT_Form $form, $value) {}
	
	protected function __construct()
	{
		$this->icon('captcha');
		$this->tooltip('tt_captcha');
		$this->initial = GDO_Session::get('php_captcha_lock');
	}
	
	public function renderForm() : string
	{
		return GDT_Template::php('Captcha', 'form/captcha.php', ['field' => $this]);
	}
	
	################
	### Validate ###
	################
	public function validate($value) : bool
	{
	    # skip tests and cli
	    $app = Application::instance();
	    if ($app->isCLI() || $app->isUnitTests())
	    {
	        return true;
	    }
	    
	    if ($this->initial)
	    {
	        return true;
	    }
	    
	    if (!(Validate::make()->validateRecaptcha($this->getVar())))
	    {
	        return $this->error('err_captcha_failed');
	    }
	    
	    GDO_Session::set('php_captcha_lock', '1');

	    $this->initial = '1';
	    
	    return true;
	}

	public function onValidated()
	{
	    GDO_Session::remove('php_captcha_lock');
	    $this->unsetRequest();
	}
	
	private function unsetRequest()
	{
	    $this->var = $this->initial = null;
	    unset($_REQUEST[$this->formVariable()][$this->name]);
	}
	
}
