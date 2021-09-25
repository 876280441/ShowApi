<?php

declare(strict_types=1);

use Yansongda\Pay\Pay;

return [
    'alipay' => [
        'default' => [
            // 支付宝分配的 app_id
            'app_id' => '2021000118603034',
            // 应用私钥
            'app_secret_cert' => 'MIIEpAIBAAKCAQEArgMNRNlLzPg4V200ZtIxWxbf/3IJ9br4f4UuBVespxGaOXHcVqmgwG4TK+N7+cp4Gkl9nU/jhAT1L2EcgOgoBuVKvY5WZJkitM22SblhhMSaVwBOwDi2FKnVUv3Zl7FIAcos+3dJrAfpvNs6JMy0LcJabSEg171SvRIIffp94dDrnZSKleme/NKPkO4e6YFMFOGoR977A/eddcz+F31Qx3k+GOjVxtU+whO5SZPCCrmMWhKGmWg1iXgbG8UTBlUKsSLBUWfvw6XBOiOA/aqkUC2w81sdzRJ5otkMTRkDgBNeOThzPT9iIAb2efO39qhLULtjMvvpDdmF3SuXm24tgwIDAQABAoIBAEKJ4DUHcji6YbDsjTEUBtNx981R6gUQrZz1bBeW4uovjO2SYFKzIkjyzlnl7q4hgbNrjqDsv27oBmlLlP6lx0h7vQymtNpaxC6myqF+RY/jLbE+6N4P0XAtUOkfMGkU3RfyGyk8/+rMS9Bvc9hGfk9RFgrMtCZKuqyZjK2/bws9mq9I+yjzBTaoaTJpYAs/Vgfq8pnbaIsDmWNTTs4NCgAuRiRY96EwuHUDVHDGmkaLdu+nELTiypgHt1d6ic3XC2AoZbDTnvrLzqRkh0V/k3R/uG0iL1guTi5WlrQtr1ICDn8ghkupQPmZbFaho5FU9rvcr9g7P4uy4DlxGzkteuECgYEA1QFJqn/Rng/b+pyLk1FcRVEq/dMsU2lZM/emfnM6HGuGJwPJmVgBxo++c7BwrWbic/XxRmg2PDOsNJBB9PvGl0uWfO9Zanu3l6JFbFgQTMatVjdvo8xjRdRgUBVfM/RBbmJGsY1ebxO9KcItdGTICWakJXVpJ/MlDVnuqFxNQbUCgYEA0SLarzIaT4bfnnJWBDZeTUJLNs7w/Ek4aqXcb11eYI8wCE34VfMXsS5pmzPuirghfV63NdrHALPL00QwW9yJiPBmKjgJiZZwQI47iQq1A9ninjA6IYMuN83uu9zN1pPbFbTgIWy0z+CqAn3aWHX2PZMaBdT/FOHM6C9gMQ1FFVcCgYArHdGdk7YIuskal6mhLmzxExcSvjZQxBhsborjOcvfiNxk9V1PjwL9AIfavzJvx027j2NBj0K7OPJ4yWKwhm6SnZhevcxpw5VMOmq3HbRe9jCMLTiJ/YtyzSSZ0VmuGJlOENKiii09as91rnqo/uWHEaHUe0nH7m3nn/axWc3BqQKBgQCtB1dDCAL4tTAiWZqNFaZj6WttRz3enX3NzrhYczl4Tj+BP3EtO1jowOui+w5rOviKT2jpZ76p+Be1DX+tIQOxhqQgXgiWCx+IaaUcNv2Y2BZEpsYRoAUHKpxQVdj/pYjnpqShQt09+DTwpsuV0NMQErq/BICe+Eqd90RwkPgNNQKBgQDFbIDempf/Kcfu7wUXt+66lTKjyQjl94ECI/SRWymvPv2btVuvbzkIdf2PKQ7Yu4ZHZ9PPVHv9FJzUPu4l1sYnNZTIv/coE+zjAefpe3eKNsbd5HPXUojwfbvIhMcIHlBIypJosIf++wpP2igAtTKjmtCdN7jYU6l4Wp+dD4Estw==',
            // 应用公钥证书 路径
            'app_public_cert_path' => 'appCertPublicKey_2021000118603034.crt',
            // 支付宝公钥证书 路径
            'alipay_public_cert_path' => 'alipayCertPublicKey_RSA2.crt',
            // 支付宝根证书 路径
            'alipay_root_cert_path' => 'alipayRootCert.crt',
            'return_url' => '',
            'notify_url' => 'http://6a95-112-96-52-100.ngrok.io/api/pay/notify/aliyun',
            'mode' => Pay::MODE_SANDBOX,
        ],
    ],
    'wechat' => [
        'default' => [
            // 公众号 的 app_id
            'mp_app_id' => '',
            // 小程序 的 app_id
            'mini_app_id' => '',
            // app 的 app_id
            'app_id' => '',
            // 商户号
            'mch_id' => '',
            // 合单 app_id
            'combine_app_id' => '',
            // 合单商户号
            'combine_mch_id' => '',
            // 商户秘钥
            'mch_secret_key' => '',
            // 商户私钥
            'mch_secret_cert' => '',
            // 商户公钥证书路径
            'mch_public_cert_path' => '',
            // 微信公钥证书路径
            'wechat_public_cert_path' => [
                '' => '',
            ],
            'notify_url' => 'http://6a95-112-96-52-100.ngrok.io/api/pay/notify/wechat',
            'mode' => Pay::MODE_NORMAL,
        ],
    ],
    'http' => [ // optional
        'timeout' => 5.0,
        'connect_timeout' => 5.0,
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
    ],
    // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
    'logger' => [
        'enable' => false,
        'file' => storage_path('logs/alipay.log'),
        'level' => 'debug',
        'type' => 'single', // optional, 可选 daily.
        'max_file' => 30,
    ],
];
