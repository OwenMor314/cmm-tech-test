<?php

return [
    '/form' => [
        [
            'type'      => 'GET',
            'handler'   => 'FormController@index',   
        ],    
        [
            'type'      => 'POST',
            'handler'   => 'FormController@submit',   
        ] 
    ],
    '/' => [
        [
            'type'      => 'GET',
            'handler'   => 'FormController@index',   
        ],  
    ]
];