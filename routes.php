<?php

return [
    '/' => 'TicketsController@index',
    '/tickets' => 'TicketsController@store',
    '/success' => 'TicketsController@success',

    '/types' => 'TicketTypesController@index',
    '/types/create' => 'TicketTypesController@create',
    '/types/store' => 'TicketTypesController@store',
    '/types/destroy' => 'TicketTypesController@destroy',
    '/types/edit' => 'TicketTypesController@edit',
    '/types/update' => 'TicketTypesController@update',
];