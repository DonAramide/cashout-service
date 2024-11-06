<?php

namespace App\Utilities;

use Exception;
use GuzzleHttp\Client;

class PinBlock
{
    public function decryptPinBlock($sStr, $sKey)
    {
        $sStr = str_replace("-", "+", $sStr);
        $decrypted = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $sKey,
            base64_decode($sStr),
            MCRYPT_MODE_ECB
        );
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);
        if (!$decrypted) throw new Exception("Pin Block Decryption Error");
        return $decrypted;
    }

    public function decryptPinBlockNew($sStr, $sKey)
    {
        $sStr = str_replace("-", "+", $sStr);
        $decrypted = openssl_decrypt(
            base64_decode($sStr),
            'aes-128-ecb',
            $sKey,
            OPENSSL_RAW_DATA
        );
        $padding = ord($decrypted[strlen($decrypted) - 1]);
        $decrypted = substr($decrypted, 0, -$padding);
        if (!$decrypted) {
            throw new Exception("Pin Block Decryption Error");
        }
        return $decrypted;
    }


    public function encryptPinBlock($input, $key)
    {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    function pkcs5_unpad($text)
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    public function encrypt($clear_pin_block, $key = '')
    {
        $data = [
            "clearPinBlock" => $clear_pin_block,
            "pinKey" => $key
        ];
        return $this->encrypterApi('/encrypt', $data);
    }

    private function constant($key)
    {
        $data =  [
            'encrypter_base_url' => getenv('ENCRYPTER_URL'),
        ];
        $result =  $data[$key];
        if (!$result) throw new Exception('Error getting [' . $key . '] from env');
        return $result;
    }

    private function encrypterApi($endpoint, $payload)
    {
        $base_url =  $this->constant('encrypter_base_url');

        $client = new Client();

        $request = $client->post(
            $base_url . $endpoint,
            [
                'json' => $payload,
            ]
        );
        $response = $request->getBody()->getContents();

        return json_decode($response);
    }
}
