<?php
declare(strict_types=1);

/**
 * Exporta el CV de src/data.php al formato de RenderCV (https://rendercv.com) para
 * generar un PDF sencillo y amigable con los ATS, sin volver a escribir los datos.
 *
 *   php herramientas/rendercv.php es                    → versiones/base/cv-es.yaml
 *   php herramientas/rendercv.php en --version=acme-iot → versiones/acme-iot/cv-en.yaml
 *   php herramientas/rendercv.php es --pdf              → además genera el PDF
 *
 * Para --pdf hace falta RenderCV: pip install "rendercv[full]"
 *
 * Uso típico para una vacante: generar la versión, editar a mano su YAML (orden de
 * las competencias, palabras clave de la vacante, proyectos que conviene destacar)
 * y volver a generar el PDF con «python -m rendercv render versiones/<v>/cv-xx.yaml».
 * Los PDF curados a mano de assets/ no se tocan.
 *
 * El YAML se escribe como JSON, que también es YAML válido: así no hace falta
 * ninguna biblioteca de YAML en PHP.
 */

require __DIR__ . '/../src/data.php';

$args = array_slice($argv, 1);
$lang = in_array('en', $args, true) ? 'en' : 'es';
$version = 'base';
foreach ($args as $a) {
    if (preg_match('/^--version=([a-z0-9][a-z0-9-]{0,60})$/', $a, $m)) {
        $version = $m[1];
    }
}
$pdf = in_array('--pdf', $args, true);

$d = cv_data($lang);
$t = $lang === 'en'
    ? ['summary' => 'Profile', 'exp' => 'Research & development experience', 'projects' => 'Projects', 'skills' => 'Technical skills',
       'pubs' => 'Publications & talks', 'edu' => 'Education', 'other' => 'Additional experience', 'certs' => 'Certifications', 'langs' => 'Languages',
       'language' => 'english']
    : ['summary' => 'Perfil profesional', 'exp' => 'Experiencia en investigación y desarrollo', 'projects' => 'Proyectos', 'skills' => 'Competencias técnicas',
       'pubs' => 'Publicaciones y ponencias', 'edu' => 'Formación académica', 'other' => 'Otra experiencia', 'certs' => 'Certificaciones', 'langs' => 'Idiomas',
       'language' => 'spanish'];

/** Escapa los caracteres que RenderCV interpreta como Markdown en textos que no lo son. */
function md(string $s): string
{
    return str_replace(['*', '_', '[', ']'], ['\*', '\_', '\[', '\]'], $s);
}

$experiencia = [];
foreach ($d['experience'] as $job) {
    $puntos = [];
    foreach ($job['groups'] as $g) {
        foreach ($g['items'] as $it) {
            $puntos[] = md($it);
        }
    }
    $experiencia[] = [
        'company'    => md($job['org']),
        'position'   => md($job['title']),
        'date'       => $job['period'],
        'summary'    => md($job['intro']),
        'highlights' => $puntos,
    ];
}

$proyectos = [];
foreach ($d['projects'] as $p) {
    [$titulo, $desc] = $d['project_text'][$p['key']];
    $proyectos[] = [
        'name'       => $p['url'] ? '[' . md($titulo) . '](' . $p['url'] . ')' : md($titulo),
        'summary'    => md($desc),
        'highlights' => [md($p['stack'])],
    ];
}

$publicaciones = [];
foreach ($d['publications'] as $p) {
    $autores = array_map(
        static fn (string $a): string => str_starts_with($a, 'Adame Rodríguez') ? '**' . $a . '**' : $a,
        // «Apellido, I. N., Apellido, I.» → un autor por elemento
        preg_split('/(?<=\.),\s+(?=[A-ZÁÉÍÓÚÑ])/u', $p['authors'])
    );
    $publicaciones[] = [
        'title'   => md($p['title'][$lang]) . ' (' . md($p['role'][$lang]) . ')',
        'authors' => $autores,
        'journal' => $p['venue'],
        'date'    => (string) $p['year'],
        'url'     => $p['url'],
    ];
}

$formacion = [];
foreach ($d['education'] as $ed) {
    $formacion[] = [
        'institution' => $ed['org'],
        'area'        => md($ed['title']),
        'date'        => $ed['period'],
        'highlights'  => array_merge([md(implode(' · ', $ed['stats'] ?? []))], [md($ed['note'])]),
    ];
}

$otra = [];
foreach ($d['other'] as $o) {
    $otra[] = ['company' => md($o['org']), 'position' => md($o['title']), 'date' => $o['period'], 'summary' => md($o['note'])];
}

$competencias = [];
foreach ($d['skills'] as $grupo => $items) {
    $competencias[] = ['label' => md($grupo), 'details' => md(implode(', ', $items))];
}

$linkedin = rawurldecode(basename(rtrim($d['profile']['linkedin'], '/')));
$sitio = rtrim((string) getenv('SITE_URL'), '/');

$cv = [
    'cv' => array_filter([
        'name'     => $d['profile']['name'],
        'headline' => $d['role'] . ' · ' . $d['subrole'],
        'location' => $d['location'],
        'email'    => $d['profile']['email'],
        'phone'    => preg_replace('/\s+/', '', $d['profile']['phone']),
        'website'  => $sitio !== '' ? $sitio . ($lang === 'en' ? '/en' : '/') : null,
        'social_networks' => [
            ['network' => 'LinkedIn', 'username' => $linkedin],
            ['network' => 'GitHub', 'username' => basename($d['profile']['github'])],
            ['network' => 'ORCID', 'username' => basename($d['profile']['orcid'])],
        ],
        'sections' => [
            $t['summary']  => [md($d['summary'])],
            $t['exp']      => $experiencia,
            $t['projects'] => $proyectos,
            $t['skills']   => $competencias,
            $t['pubs']     => $publicaciones,
            $t['edu']      => $formacion,
            $t['other']    => $otra,
            $t['certs']    => array_map(static fn ($c) => ['bullet' => md($c)], $d['certs']),
            $t['langs']    => array_map(static fn ($l) => ['bullet' => md($l)], $d['languages']),
        ],
    ], static fn ($v) => $v !== null),
    // Teléfono con +52: el CV se manda a vacantes remotas fuera de México.
    'design' => ['theme' => 'classic', 'header' => ['connections' => ['phone_number_format' => 'international']]],
    'locale' => ['language' => $t['language']],
];

$dir = dirname(__DIR__) . '/versiones/' . $version;
if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
    fwrite(STDERR, "No se pudo crear $dir\n");
    exit(1);
}
$yaml = "$dir/cv-$lang.yaml";
file_put_contents($yaml, "# Generado por herramientas/rendercv.php desde src/data.php (" . CV_UPDATED . ").\n# Se puede editar para adaptarlo a una vacante; volver a generarlo lo sobrescribe.\n"
    . json_encode($cv, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n");
echo "YAML: $yaml\n";

if ($pdf) {
    $salida = "$dir/cv-$lang.pdf";
    // RenderCV toma las rutas de salida relativas a la carpeta del YAML; el .typ intermedio se queda ahí.
    $cmd = [getenv('PYTHON') ?: 'python', '-m', 'rendercv', 'render', $yaml, '--pdf-path', basename($salida),
            '--typst-path', 'cv-' . $lang . '.typ', '--dont-generate-markdown', '--dont-generate-html', '--dont-generate-png', '--quiet'];
    $p = proc_open($cmd, [1 => STDOUT, 2 => STDERR], $pipes, $dir);
    $codigo = is_resource($p) ? proc_close($p) : 1;
    if ($codigo !== 0 || !is_file($salida)) {
        fwrite(STDERR, "RenderCV falló (código $codigo). ¿Está instalado? pip install \"rendercv[full]\"\n");
        exit(1);
    }
    echo "PDF:  $salida\n";
}
