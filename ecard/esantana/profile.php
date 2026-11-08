<?php
declare(strict_types=1);

// Configuración de la plantilla. Publicar solo con autorización y revisión familiar.
// No incluir dirección de casa, documentos, escuela ni información clínica innecesaria.
// Vacío significa desconocido, nunca "sin alergias" o "sin medicación".
return [
    'demo' => true,
    'name' => 'Emiliano Francisco Santana Parra',
    'short_name' => 'Emiliano',
    'initials' => 'ES',
    'comfort' => '',
    'reviewed_at' => '',
    // Confirmar el número de emergencias de la localidad antes de configurarlo.
    'emergency_phone' => '',
    // Orden de llamada: contacto principal primero y alternativos después.
    // Usar teléfonos internacionales y mantener cada VCF sincronizado con su contacto.
    'contacts' => [
        [
            'name' => 'Leidert Parra',
            'relationship' => 'mamá',
            'phone' => '+584122852922',
            'phone_display' => '+58 412 285 2922',
            'email' => 'leidertparra@gmail.com',
            'vcard' => 'leidert-parra.vcf',
        ],
        [
            'name' => 'Jesus Santana',
            'relationship' => 'papá',
            'phone' => '+584244380137',
            'phone_display' => '+58 424 438 0137',
            'email' => 'venecode.contact@gmail.com',
            'vcard' => 'jesus-santana.vcf',
        ],
    ],
    'medical' => [
        'Alergias' => '',
        'Medicación e indicaciones' => '',
        'Condiciones médicas relevantes' => '',
        'Comunicación y apoyos' => '',
        'Atención médica de referencia' => '',
        'Indicaciones para emergencias' => '',
    ],
];
