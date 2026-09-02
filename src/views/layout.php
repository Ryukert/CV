<?php /** @var array $d */ ?>
<!DOCTYPE html>
<html lang="<?= e($d['lang']) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($d['profile']['name']) ?> — <?= e($d['role']) ?></title>
<meta name="description" content="<?= e($d['meta_desc']) ?>">
<meta name="robots" content="index, follow">
<meta name="theme-color" content="#14171b">
<meta name="color-scheme" content="light dark">
<meta property="og:title" content="<?= e($d['profile']['name']) ?> — <?= e($d['role']) ?>">
<meta property="og:description" content="<?= e($d['meta_desc']) ?>">
<meta property="og:type" content="profile">
<meta property="og:locale" content="<?= e($d['locale']) ?>">
<meta property="og:url" content="<?= e(site_url($d['lang'] === 'en' ? '/en' : '/')) ?>">
<meta property="og:image" content="<?= e(site_url($d['profile']['photo'])) ?>">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="<?= e($d['profile']['name']) ?> — <?= e($d['role']) ?>">
<meta name="twitter:description" content="<?= e($d['meta_desc']) ?>">
<meta name="twitter:image" content="<?= e(site_url($d['profile']['photo'])) ?>">
<link rel="canonical" href="<?= e(site_url($d['lang'] === 'en' ? '/en' : '/')) ?>">
<link rel="alternate" hreflang="es" href="<?= e(site_url('/')) ?>">
<link rel="alternate" hreflang="en" href="<?= e(site_url('/en')) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e(site_url('/')) ?>">
<link rel="preload" as="font" type="font/woff2" href="/assets/fonts/IBMPlexSans-Regular-Latin1.woff2" crossorigin>
<link rel="preload" as="font" type="font/woff2" href="/assets/fonts/IBMPlexSans-SemiBold-Latin1.woff2" crossorigin>
<link rel="stylesheet" href="/assets/style.css">
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Person',
    'name'     => $d['profile']['name'],
    'jobTitle' => $d['role'],
    'email'    => 'mailto:' . $d['profile']['email'],
    'url'      => site_url($d['lang'] === 'en' ? '/en' : '/'),
    'image'    => site_url($d['profile']['photo']),
    'sameAs'   => [$d['profile']['github'], $d['profile']['linkedin'], $d['profile']['orcid']],
    'alumniOf' => ['@type' => 'CollegeOrUniversity', 'name' => 'Universidad Autónoma de Guerrero'],
    'hasCredential' => array_map(static fn(array $ed): array => [
        '@type'              => 'EducationalOccupationalCredential',
        'name'               => $ed['title'],
        'credentialCategory' => 'degree',
        'recognizedBy'       => ['@type' => 'CollegeOrUniversity', 'name' => $ed['org']],
    ], $d['education']),
    'knowsLanguage' => ['es', 'en'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
</head>
<body>

<a class="skip" href="#main"><?= $d['lang'] === 'en' ? 'Skip to content' : 'Ir al contenido' ?></a>

<header class="hero">
  <div class="wrap">
    <div class="hero-top">
      <img class="avatar" src="<?= e($d['profile']['photo']) ?>" alt="<?= e($d['profile']['name']) ?>"
           width="96" height="96" fetchpriority="high">
      <div>
        <h1><?= e($d['profile']['name']) ?></h1>
        <p class="role"><?= e($d['role']) ?></p>
        <p class="subrole"><?= e($d['subrole']) ?></p>
        <p class="meta"><?= e($d['location']) ?><br><?= e($d['available']) ?></p>
        <p class="links">
          <a href="mailto:<?= e($d['profile']['email']) ?>"><?= e($d['profile']['email']) ?></a>
          <a href="<?= e($d['profile']['github']) ?>" rel="noopener">GitHub</a>
          <a href="<?= e($d['profile']['linkedin']) ?>" rel="noopener">LinkedIn</a>
          <a href="<?= e($d['profile']['orcid']) ?>" rel="noopener">ORCID</a>
        </p>
        <p class="actions">
          <a class="btn" href="<?= e($d['pdf']['plain']) ?>" download><?= e($d['cta_pdf']) ?></a>
          <a class="btn ghost" href="<?= e($d['pdf']['design']) ?>" download><?= e($d['cta_design']) ?></a>
          <a class="btn ghost" href="<?= e($d['switch_url']) ?>"><?= e($d['switch']) ?></a>
        </p>
      </div>
    </div>

    <?php /* Acelerograma: la señal que este CV sabe medir, dibujada como
             marca de la casa en lugar de un banner decorativo. */ ?>
    <div class="trace" aria-hidden="true">
      <svg viewBox="0 0 1200 150" preserveAspectRatio="none" role="presentation" focusable="false">
        <?php for ($i = 1; $i < 8; $i++): ?>
          <line class="trace-grid" x1="<?= $i * 150 ?>" y1="0" x2="<?= $i * 150 ?>" y2="150"/>
        <?php endfor; ?>
        <line class="trace-base" x1="0" y1="75" x2="1200" y2="75"/>
        <polyline class="trace-line" points="<?= trace_points() ?>"/>
      </svg>
    </div>

    <div class="axis">
      <?php foreach ($d['metrics'] as $m): ?>
        <div>
          <span class="stat-v"><?= e($m['value']) ?></span>
          <span class="stat-k"><?= e($d['metric_labels'][$m['key']]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</header>

<nav class="nav" aria-label="<?= $d['lang'] === 'en' ? 'Sections' : 'Secciones' ?>">
  <div class="wrap nav-inner">
    <?php foreach ($d['nav'] as $id => $label): ?>
      <a href="#<?= e($id) ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
  </div>
</nav>

<main id="main">

  <section id="about" class="wrap sec first">
    <h2><?= e($d['nav']['about']) ?></h2>
    <div class="sec-body">
      <p class="lede"><?= e($d['summary']) ?></p>
    </div>
  </section>

  <section id="exp" class="wrap sec">
    <h2><?= e($d['sections']['exp']) ?></h2>
    <div class="sec-body">
      <?php foreach ($d['experience'] as $job): ?>
        <article class="entry">
          <div class="entry-head">
            <h3><?= e($job['title']) ?></h3>
            <span class="period"><?= e($job['period']) ?></span>
          </div>
          <p class="org"><?= e($job['org']) ?></p>
          <p class="intro"><?= e($job['intro']) ?></p>
          <?php foreach ($job['groups'] as $g): ?>
            <h4><?= e($g['name']) ?></h4>
            <ul>
              <?php foreach ($g['items'] as $it): ?><li><?= e($it) ?></li><?php endforeach; ?>
            </ul>
          <?php endforeach; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="pubs" class="wrap sec">
    <h2><?= e($d['sections']['pubs']) ?></h2>
    <div class="sec-body">
      <ol class="pubs">
        <?php foreach ($d['publications'] as $p): ?>
          <li>
            <div>
              <a class="ptitle" href="<?= e($p['url']) ?>" rel="noopener"><?= e($p['title'][$d['lang']]) ?></a>
              <p class="cite">
                <?= e($p['authors']) ?> (<?= (int) $p['year'] ?>). <?= e($p['venue']) ?>.
                <span class="tag"><?= e($p['role'][$d['lang']]) ?></span>
              </p>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>
      <p class="talk"><b><?= e($d['talk']['label']) ?>.</b> <?= e($d['talk']['text']) ?></p>
    </div>
  </section>

  <section id="projects" class="wrap sec">
    <h2><?= e($d['sections']['projects']) ?></h2>
    <div class="sec-body">
      <?php foreach ($d['projects'] as $pr): ?>
        <?php [$title, $desc] = $d['project_text'][$pr['key']]; ?>
        <article class="project">
          <h3><?= e($title) ?></h3>
          <p class="stack"><?= e($pr['stack']) ?></p>
          <p class="desc"><?= e($desc) ?></p>
          <?php if ($pr['url']): ?>
            <a class="repo" href="<?= e($pr['url']) ?>" rel="noopener"><?= e(short_repo($pr['url'])) ?></a>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section id="skills" class="wrap sec">
    <h2><?= e($d['sections']['skills']) ?></h2>
    <div class="sec-body">
      <dl class="skills">
        <?php foreach ($d['skills'] as $group => $items): ?>
          <div class="skillrow">
            <dt><?= e($group) ?></dt>
            <dd><?= e(implode(', ', $items)) ?></dd>
          </div>
        <?php endforeach; ?>
      </dl>
    </div>
  </section>

  <section id="areas" class="wrap sec">
    <h2><?= e($d['sections']['areas']) ?></h2>
    <div class="sec-body">
      <p class="areas-intro"><?= e($d['areas']['intro']) ?></p>
      <p class="legend">
        <span><i class="on"></i><?= e($d['areas']['legend']['applied']) ?></span>
        <span><i></i><?= e($d['areas']['legend']['coursework']) ?></span>
      </p>
      <div class="areas">
        <?php foreach ($d['areas']['items'] as $a): ?>
          <article class="area<?= $a['applied'] ? ' is-applied' : '' ?>">
            <span class="dot" aria-hidden="true"></span>
            <div>
              <h3><?= e($a['name']) ?></h3>
              <?php if (!empty($a['evidence'])): ?>
                <p class="evidence"><?= e($a['evidence']) ?></p>
              <?php endif; ?>
              <p class="area-courses"><?= e(implode('   ', $a['courses'])) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="edu" class="wrap sec">
    <h2><?= e($d['sections']['edu']) ?></h2>
    <div class="sec-body">
      <?php foreach ($d['education'] as $ed): ?>
        <article class="entry">
          <div class="entry-head">
            <h3><?= e($ed['title']) ?></h3>
            <span class="period"><?= e($ed['period']) ?></span>
          </div>
          <p class="org"><?= e($ed['org']) ?></p>
          <?php if (!empty($ed['stats'])): ?>
            <p class="stats">
              <?php foreach ($ed['stats'] as $s): ?><span><?= e($s) ?></span><?php endforeach; ?>
            </p>
          <?php endif; ?>
          <p class="note"><?= e($ed['note']) ?></p>
          <?php if (!empty($ed['coursework'])): ?>
            <details class="coursework">
              <summary><?= e($ed['coursework']['label']) ?></summary>
              <?php if (!empty($ed['coursework']['note'])): ?>
                <p class="cw-note"><?= e($ed['coursework']['note']) ?></p>
              <?php endif; ?>
              <?php foreach ($ed['coursework']['groups'] as $g): ?>
                <div class="cw-group">
                  <h4><?= e($g['name']) ?></h4>
                  <p class="cw-list"><?= e(implode('   ', $g['items'])) ?></p>
                </div>
              <?php endforeach; ?>
            </details>
          <?php endif; ?>
        </article>
      <?php endforeach; ?>

      <h3 class="h-sub"><?= e($d['sections']['other']) ?></h3>
      <?php foreach ($d['other'] as $i => $o): ?>
        <article class="entry"<?= $i === 0 ? ' style="border-top:0;margin-top:0;padding-top:0"' : '' ?>>
          <div class="entry-head">
            <h3><?= e($o['title']) ?></h3>
            <span class="period"><?= e($o['period']) ?></span>
          </div>
          <p class="org"><?= e($o['org']) ?></p>
          <p class="note"><?= e($o['note']) ?></p>
        </article>
      <?php endforeach; ?>

      <h3 class="h-sub"><?= $d['lang'] === 'en' ? 'Certifications' : 'Certificaciones' ?></h3>
      <ul class="plain">
        <?php foreach ($d['certs'] as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
      </ul>

      <h3 class="h-sub"><?= $d['lang'] === 'en' ? 'Languages' : 'Idiomas' ?></h3>
      <ul class="plain">
        <?php foreach ($d['languages'] as $l): ?><li><?= e($l) ?></li><?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section id="contact" class="wrap sec">
    <h2><?= e($d['sections']['contact']) ?></h2>
    <div class="sec-body">
      <p class="note" style="margin-top:0"><?= e($d['contact_intro']) ?></p>
      <a class="contact-mail" href="mailto:<?= e($d['profile']['email']) ?>"><?= e($d['profile']['email']) ?></a>
      <p class="contact-meta"><?= e($d['profile']['phone']) ?></p>
    </div>
  </section>

</main>

<footer>
  <div class="wrap">
    <p><?= e($d['footer']) ?></p>
    <p><a href="/api/cv.json?lang=<?= e($d['lang']) ?>">/api/cv.json</a></p>
  </div>
</footer>

</body>
</html>
