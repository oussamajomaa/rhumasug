<?php
namespace App\Services;
use Symfony\Component\HttpFoundation\Request;

class Recaptcha{

    public function verifyRecaptcha(Request $request):Bool
    {
        // une class qui compare la clé secrète et la clé du site de recaptcha
        $secret = '6LcbuN8UAAAAAG8WNPWnJpBKbCBf1bfWks1H1VC6';
        $recaptcha_response = $request->get('recaptcha_response');
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $verifyResponse = file_get_contents($url . '?secret=' . $secret . '&response=' . $recaptcha_response);
        // on convertit la réponse en json
        $verifyResponse = json_decode($verifyResponse);
        // on retourne la clé success de l'objet json true/false
        return $verifyResponse->success;

    }
}