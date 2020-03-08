<?php
/**
 * Created by PhpStorm.
 * User: ccl
 * Date: 2019/5/25
 * Time: 20:29
 */

namespace Config\Enum;

class EyRsaKey
{

    private static $aes_key = 'find_str_2009';

    private static $primary_key = "-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDYzvIXAIvSptjth/86PLe++5578bqCImPDeJWa8UtXby+bCsKk
nR7AfAMNsC4bCMU2zma1ZtT5/MCImHDP2wqeIYlR6fshuCW8qjeEHGJYYVWQU5mn
eCrd2asTIDVfAmEKe/aZqkV03vHXqf6ZOTR1lRxR9PPa/VottvTnAhsjAQIDAQAB
AoGBAJyFIm8KvObR/0/UW6TeisS9AJh4Ve53B1Dp8A+Q3ZR8t4CVzxlqOPY7UTUD
mPV5am77qViSqXTkTNcQPnlAqDNWJWyAzKDW5kxucVV81nGbs24PoJp0v3NAylji
lhjjjh8/TQp+EiJlcGwzqEXGWwrh7xDzAEkRdlv0C5HLgwpFAkEA+xxw+uh4Ik78
VmdsYx8EOmR36qlVwBVqeLhAJAgRk7kYwolokJ5INB8O7Hsl3EFre9br83PyvDzh
xKQeEDKGbwJBAN0HiWcOXkUWdLlaTOvnHwzL9QMnLxwfxkJ4x80lh6/AxRrTB3Pv
DzwnATp03yXR6dQ4E+hl1RR2YJ5yGvjMJY8CQDyeoBOiPSYjJT2CmCLRoQarrFtE
58OIJ/zelfVc0Ul1HKoR2+FVpJ6YhNTH4drrHBR4TNunuQiCNgpviCZm3BcCP0iG
MI+gJ06ED8jB2HuPWqDYS4y9TrfrtSIaf3TQ27TPi91BYTCpQFZ8deq4bn/6YL9B
p0aaI1CThyB2UGNSmQJBAN1OPc4lr3MOR9LWWY4qmAHP56CI8E+Y3wFBhXZews0t
YvsuEpMxyvcnPi7brfGD6GDGghWZqC87nt0eX/Po93A=
-----END RSA PRIVATE KEY-----
";
    private static $public_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDYzvIXAIvSptjth/86PLe++557
8bqCImPDeJWa8UtXby+bCsKknR7AfAMNsC4bCMU2zma1ZtT5/MCImHDP2wqeIYlR
6fshuCW8qjeEHGJYYVWQU5mneCrd2asTIDVfAmEKe/aZqkV03vHXqf6ZOTR1lRxR
9PPa/VottvTnAhsjAQIDAQAB
-----END PUBLIC KEY-----
";

    /**
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return self::$primary_key;
    }

    /**
     * @return string
     */
    public static function getPublicKey(): string
    {
        return self::$public_key;
    }

    /**
     * @return string
     */
    public static function getAesKey(): string
    {
        return self::$aes_key;
    }

}