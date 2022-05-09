<?php
namespace GDO\Captcha\Method;

use GDO\Core\Method;
use GDO\Net\HTTP;
use GDO\Captcha\Module_Captcha;
use GDO\Core\GDT_String;
use GDO\Net\GDT_IP;
use GDO\Core\GDT;

final class Validate extends Method
{
    public function showInSitemap() { return false; }
    
    public function gdoParameters()
    {
        return [
            GDT_String::make('response'),
        ];
    }
    
    public function execute() : GDT
    {
        return $this->validateRecaptcha($this->gdoParameterVar('response')) ?
        $this->message('msg_captcha_passed') :
        $this->error('err_captcha_failed');
    }
    
    public function validateRecaptcha($response)
    {
        $module = Module_Captcha::instance();
        $url = $module->validationURL();
        $secret = $module->cfgSiteSecret();
        
        $postdata = [
            'secret' => $secret,
            'response' => $response,
        ];
        
        if ($module->cfgGoogleGetsIP())
        {
            if (!GDT_IP::isLocal())
            {
                $postdata['remoteip'] = GDT_IP::current();
            }
        }
        
        if (!($json = HTTP::post($url, $postdata)))
        {
            $this->error('err_url_not_reachable');
            return false;
        }
        
        $json = json_decode($json, true);
        if ($json['success'])
        {
            return true;
        }
        
        return false;
    }
    
}
