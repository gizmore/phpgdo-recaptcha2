<?php
namespace GDO\Captcha;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Secret;
use GDO\Core\Javascript;
use GDO\Core\GDT_Checkbox;

/**
 * Recaptcha Captcha implementation for gdo6.
 * Uses recaptcha2.
 * @see https://developers.google.com/recaptcha/docs/display#explicit_render
 * @author gizmore
 * @version 6.10.1
 * @since 6.10.1
 */
final class Module_Captcha extends GDO_Module
{
    ##############
    ### Module ###
    ##############
    public function onLoadLanguage() : void { $this->loadLanguage('lang/captcha'); }
//     public function getDependencies() { return ['JQuery']; }
    public function href_administrate_module() { return href('Captcha', 'CaptchaTest'); }
    
    ##############
    ### Config ###
    ##############
    public function getConfig() : array
    {
        return [
            GDT_Checkbox::make('google_gets_ip')->initial('0'),
            GDT_Secret::make('google_site_key')->initial('6xLfc1ooaAAAAAMUX3lLx6A1GiX1hs43VN2ebO43S'),
            GDT_Secret::make('google_site_secret')->initial('6xLfc1ooaAAAAAKVBEFcrl7wrXd4tDCEBLyjXPJJa'),
        ];
    }
    public function cfgGoogleGetsIP() { return $this->getConfigValue('google_gets_ip'); }
    public function cfgSiteKey() { return $this->getConfigVar('google_site_key'); }
    public function cfgSiteSecret() { return $this->getConfigVar('google_site_secret'); }
    
    public function validationURL() { return 'https://www.google.com/recaptcha/api/siteverify'; }
    
    #############
    ### Hooks ###
    #############
    public function onIncludeScripts()
    {
        $this->addInlineKey();
        $this->addJS('js/gdo6-recaptcha2.js');
        Javascript::addJS('//www.google.com/recaptcha/api.js?onload=googleCallbackRecaptcha&render=explicit');
    }
    
    private function addInlineKey()
    {
        $key = $this->cfgSiteKey();
        $script_html = 'window.gdo6_recaptcha_key = \'' . $key ."';\n";
        Javascript::addJSPreInline($script_html);
    }
    
}
