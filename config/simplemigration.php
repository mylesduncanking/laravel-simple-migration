<?php

return [
    'type_assumptions' => [
        '_id$'  => 'foreignId',
        '_at$'  => 'timestamp',
        '^is_'  => 'boolean',
        '_log$' => 'json',
        '_by$'  => 'date',
        '_on$'  => 'date',
    ],
    'create_triggers' => [
        'id',
        'uuid',
    ],
    'auto_after' => true,
    'auto_index' => [
        '_id$'
    ],
];
