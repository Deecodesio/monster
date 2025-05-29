<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribuut moet worden geaccepteerd.',
    'active_url'           => 'The :attribuut heeft geen geldige URL.',
    'after'                => 'The :attribuut moet een datum zijn na :date.',
    'after_or_equal'       => 'The :attribuut moet een datum na of gelijk zijn aan :date.',
    'alpha'                => 'The :attribuut mag alleen letters bevatten.',
    'alpha_dash'           => 'The :attribuut mag alleen letters, cijfers en streepjes bevatten.',
    'alpha_num'            => 'The :attribuut mag alleen letters en cijfers bevatten.',
    'array'                => 'The :attribuut moet een array zijn.',
    'before'               => 'The :attribuut moet een eerdere datum zijn dan :date.',
    'before_or_equal'      => 'The :attribuut moet een datum voor of gelijk aan zijn dan :date.',
    'between'              => [
        'numeric' => 'The :attribuut moet zijn tussen :min en :max.',
        'file'    => 'The :attribuut moet zijn tussen :min en :max kilobytes.',
        'string'  => 'The :attribuut moet zijn tussen :min en :max characters.',
        'array'   => 'The :attribuut moet zijn tussen :min en :max items.',
    ],
    'boolean'              => 'The :attribuutveld moet waar of niet waar zijn.',
    'confirmed'            => 'The :attribuutbevestiging komt niet overeen.',
    'date'                 => 'The :attribuut heeft geen geldige datum.',
    'date_format'          => 'The :attribuut komt niet overeen met het formaat :format.',
    'different'            => 'The :attribuut en :andere moet verschillend zijn.',
    'digits'               => 'The :attribuut moet zijn: cijfers cijfers.',
    'digits_between'       => 'The :attribuut moet tussen: min en: max cijfers zijn.',
    'dimensions'           => 'The :attribuut heeft ongeldige afbeeldingsafmetingen.',
    'distinct'             => 'The :attribuutveld heeft een dubbele waarde',
    'email'                => 'The :attribuut moet een geldig e-mailadres zijn.',
    'exists'               => 'The selected :attribuut is ongeldig.',
    'file'                 => 'The :attribuut moet een bestand zijn.',
    'filled'               => 'The :attribuutveld is verplicht.',
    'image'                => 'The :attribuut moet een afbeelding zijn.',
    'in'                   => 'The selected :attribuut is ongeldig.',
    'in_array'             => 'The :attribuutveld bestaat niet in: andere.',
    'integer'              => 'The :attribuut moet een geheel getal zijn.',
    'ip'                   => 'The :attribuut moet een geldig IP-adres zijn.',
    'json'                 => 'The :attribuut moet een geldige JSON-reeks zijn.',
    'max'                  => [
        'numeric' => 'The :attribuut mag niet groter zijn dan: max.',
        'file'    => 'The :attribuut mag niet groter zijn dan: max kilobytes.',
        'string'  => 'The :attribuut mag niet groter zijn dan: max karakters.',
        'array'   => 'The :attribuut mag niet meer hebben dan: max items.',
    ],
    'mimes'                => 'The :attribuut moet een bestand zijn van het type :waarden.',
    'mimetypes'            => 'The :attribuut moet een bestand zijn van het type :waarden.',
    'min'                  => [
        'numeric' => 'The :attribuut moet minimaal zijn: min.',
        'file'    => 'The :attribuut moet minimaal zijn :min kilobytes.',
        'string'  => 'The :attribuut moet minimaal zijn :min karakters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'Het geselecteerde attribuut :is ongeldig.',
    'numeric'              => 'The :attribuut moet een nummer zijn.',
    'present'              => 'The :attribuutveld moet aanwezig zijn.',
    'regex'                => 'The :attribuut formaat is ongeldig.',
    'required'             => 'The :attribute is vereist.',
    'required_if'          => 'The :attribuutveld is vereist wanneer :anders is :waarde.',
    'required_unless'      => 'The :attribuutveld is vereist tenzij :anders is in :waarden.',
    'required_with'        => 'The :attribuutveld is vereist wanneer :waarden aanwezig zijn.',
    'required_with_all'    => 'The :attribuutvelden zijn vereist wanneer :waarden aanwezig zijn.',
    'required_without'     => 'The :attribuutveld is vereist wanneer :waarden niet aanwezig zijn.',
    'required_without_all' => 'The :attribuutveld is vereist wanneer geen van :waarden aanwezig zijn.',
    'same'                 => 'The :attribuut en :andere moet overeenkomen.',
    'size'                 => [
        'numeric' => 'The :attribuut moet zijn: grootte.',
        'file'    => 'The :attribute must be :grote kilobytes.',
        'string'  => 'The :attribuut moet zijn :grote karakters.',
        'array'   => 'The :attribuut moet vatten :maat items.',
    ],
    'string'               => 'The :attribuut moet een string zijn.',
    'timezone'             => 'The :attribuut moet een geldige zone zijn.',
    'unique'               => 'The :attribuut is al bezet.',
    'uploaded'             => 'The :kenmerk kan niet worden geüpload.',
    'url'                  => 'The :attribuutformaat is ongeldig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'Aangepast bericht',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
