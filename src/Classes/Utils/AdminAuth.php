<?php

declare(strict_types=1);

namespace Wsi\Utils;

use Cake\Core\Configure;
use Cake\Http\Cookie\Cookie;
use Cake\Utility\Security;
use DateTime;

class AdminAuth
{
    static function checkIsAdmin($adminCookie)
    {
        $decrypted = Security::decrypt($adminCookie, Configure::read('Security.admin_cookie_key')) ?? '';
        if (strpos($decrypted, Configure::read('Security.admin_password')) === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    static function getAdminCookie()
    {
        $encrypted = Security::encrypt(Configure::read('Security.admin_password'), Configure::read('Security.admin_cookie_key'));
        $cookie = new Cookie('wsiA', $encrypted, new DateTime('+1 day'));

        return $cookie;
    }
}
