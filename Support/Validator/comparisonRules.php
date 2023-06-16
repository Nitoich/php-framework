<?php

return [
    'validator' => [
        'rules' => [
            'required' => \Framework\Support\Validator\Rules\RequiredRule::class,
            'email' => \Framework\Support\Validator\Rules\EmailRule::class
        ]
    ]
];