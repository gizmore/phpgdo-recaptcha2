<?php
namespace GDO\Captcha\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Form\GDT_Submit;
use GDO\Form\GDT_AntiCSRF;
use GDO\UI\GDT_Title;
use GDO\Captcha\GDT_Captcha;

final class CaptchaTest extends MethodForm
{
    public function isShownInSitemap() : bool { return false; }
    
    public function createForm(GDT_Form $form) : void
    {
        $form->addFields(
            GDT_Title::make('title')->initial('test')->max(3),
            GDT_Captcha::make(),
            GDT_AntiCSRF::make(),
        );
        $form->actions()->addField(GDT_Submit::make());
    }
    
    public function formValidated(GDT_Form $form)
    {
        $this->message('msg_test_passed');
        return $this->renderPage();
    }
    
}
