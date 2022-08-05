<?php

return [
    'type_assumptions' => [
        '_id$' => 'foreignId',
        '_at$' => 'timestamp',
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
