<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;
use App\Models\CustomRules\MyCustomRules;

class Validation
{
    //--------------------------------------------------------------------
    // Setup
    //--------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        MyCustomRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    //--------------------------------------------------------------------
    // Rules
    //--------------------------------------------------------------------

    public $cliente_validation = [
        'nombre' => [
            'label' => 'Nombre',
            'rules' => 'required|alpha_space|min_length[3]|max_length[75]',
        ],
        'apellido' => [
            'label' => 'Apellido',
            'rules' => 'required|alpha_space|min_length[3]|max_length[75]',
        ],
        'telefono' => [
            'label' => 'Telefono',
            'rules' => 'required|alpha_space|min_length[3]|max_length[8]',
        ],
        'correo' => [
            'label' => 'Correo',
            'rules' => 'permit_empty|alpha_numeric_space|valid_email|max_length[85]',
        ],
    ];

    public $cuenta_validation = [
        'moneda' => [
            'label' => 'Moneda',
            'rules' => 'required|alpha_space|min_length[3]|max_length[3]',
        ],
        'fondo' => [
            'label' => 'Fondo',
            'rules' => 'required|numeric|min_length[1]|max_length[10]',
        ],
        'cliente_id' => [
            'label' => 'Cliente',
            'rules' => 'required|integer|is_valid_cliente',
            'errors' => [
                'is_valid_cliente' => 'El cliente no existe debe de ser valido',
            ],
        ],
    ];


}
