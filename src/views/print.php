<?php /** @var array $d */ ?>
<!DOCTYPE html>
<html lang="<?= e($d['lang']) ?>">
<head>
<meta charset="utf-8">
<title><?= e($d['profile']['name']) ?> — <?= e($d['role']) ?></title>
<link rel="stylesheet" href="/assets/print.css">
</head>
<body>

<header class="band">
  <div class="band-top">
    <img src="<?= e($d['profile']['photo']) ?>" alt="">
    <div>
      <h1><?= e($d['profile']['name']) ?></h1>
      <p class="role"><?= e($d['role']) ?></p>
      <p class="subrole"><?= e($d['subrole']) ?></p>
      <p class="meta"><?= e($d['location']) ?> — <?= e($d['available']) ?></p>
      <p class="links">
        <span><?= e($d['profile']['email']) ?></span>
        <span><?= e($d['profile']['phone']) ?></span>
        <span>github.com/Ryukert</span>
        <span>ORCID 0009-0003-2361-4945</span>
      </p>
    </div>
  </div>

  <div class="trace">
    <svg viewBox="0 0 1200 150" preserveAspectRatio="none">
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
</header>

<main>

  <section class="sec first">
    <h2><?= e($d['nav']['about']) ?></h2>
    <p class="lede"><?= e($d['summary']) ?></p>
  </section>

  <section class="sec">
    <h2><?= e($d['sections']['exp']) ?></h2>
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
          <ul><?php foreach ($g['items'] as $it): ?><li><?= e($it) ?></li><?php endforeach; ?></ul>
        <?php endforeach; ?>
      </article>
    <?php endforeach; ?>
  </section>

  <section class="sec">
    <h2><?= e($d['sections']['pubs']) ?></h2>
    <ol class="pubs">
      <?php foreach ($d['publications'] as $p): ?>
        <li>
          <div>
            <span class="ptitle"><?= e($p['title'][$d['lang']]) ?></span>
            <p class="cite"><?= e($p['authors']) ?> (<?= (int) $p['year'] ?>). <?= e($p['venue']) ?>.
              <span class="tag"><?= e($p['role'][$d['lang']]) ?></span></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
    <p class="talk"><b><?= e($d['talk']['label']) ?>.</b> <?= e($d['talk']['text']) ?></p>
  </section>

  <section class="sec">
    <h2><?= e($d['sections']['skills']) ?></h2>
    <dl>
      <?php foreach ($d['skills'] as $group => $items): ?>
        <div class="skillrow">
          <dt><?= e($group) ?></dt>
          <dd><?= e(implode(', ', $items)) ?></dd>
        </div>
      <?php endforeach; ?>
    </dl>
  </section>

  <section class="sec">
    <h2><?= e($d['sections']['projects']) ?></h2>
    <?php foreach ($d['projects'] as $pr): ?>
      <?php [$title, $desc] = $d['project_text'][$pr['key']]; ?>
      <article class="project">
        <h3><?= e($title) ?></h3>
        <p class="stack"><?= e($pr['stack']) ?></p>
        <p class="desc"><?= e($desc) ?></p>
        <?php if ($pr['url']): ?><span class="repo"><?= e(short_repo($pr['url'])) ?></span><?php endif; ?>
      </article>
    <?php endforeach; ?>
  </section>

  <section class="sec">
    <h2><?= e($d['sections']['areas']) ?></h2>
    <p class="areas-intro"><?= e($d['areas']['intro']) ?></p>
    <p class="legend">
      <span><i class="on"></i><?= e($d['areas']['legend']['applied']) ?></span>
      <span><i></i><?= e($d['areas']['legend']['coursework']) ?></span>
    </p>
    <div class="areas">
      <?php foreach ($d['areas']['items'] as $a): ?>
        <article class="area<?= $a['applied'] ? ' is-applied' : '' ?>">
          <span class="dot"></span>
          <div>
            <h3><?= e($a['name']) ?></h3>
            <?php if (!empty($a['evidence'])): ?><p class="evidence"><?= e($a['evidence']) ?></p><?php endif; ?>
            <p class="area-courses"><?= e(implode('   ', $a['courses'])) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sec">
    <h2><?= e($d['sections']['edu']) ?></h2>
    <?php foreach ($d['education'] as $ed): ?>
      <article class="entry">
        <div class="entry-head">
          <h3><?= e($ed['title']) ?></h3>
          <span class="period"><?= e($ed['period']) ?></span>
        </div>
        <p class="org"><?= e($ed['org']) ?></p>
        <?php if (!empty($ed['stats'])): ?>
          <p class="stats"><?php foreach ($ed['stats'] as $s): ?><span><?= e($s) ?></span><?php endforeach; ?></p>
        <?php endif; ?>
        <p class="note"><?= e($ed['note']) ?></p>
        <?php if (!empty($ed['coursework'])): ?>
          <?php if (!empty($ed['coursework']['note'])): ?>
            <p class="cw-note"><?= e($ed['coursework']['note']) ?></p>
          <?php endif; ?>
          <?php foreach ($ed['coursework']['groups'] as $g): ?>
            <div class="cw-group">
              <h4><?= e($g['name']) ?></h4>
              <p class="cw-list"><?= e(implode('   ', $g['items'])) ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>

    <h3 class="h-sub"><?= e($d['sections']['other']) ?></h3>
    <?php foreach ($d['other'] as $o): ?>
      <article class="entry">
        <div class="entry-head">
          <h3><?= e($o['title']) ?></h3>
          <span class="period"><?= e($o['period']) ?></span>
        </div>
        <p class="org"><?= e($o['org']) ?></p>
        <p class="note"><?= e($o['note']) ?></p>
      </article>
    <?php endforeach; ?>

    <h3 class="h-sub"><?= $d['lang'] === 'en' ? 'Certifications' : 'Certificaciones' ?></h3>
    <ul class="plain"><?php foreach ($d['certs'] as $c): ?><li><?= e($c) ?></li><?php endforeach; ?></ul>

    <h3 class="h-sub"><?= $d['lang'] === 'en' ? 'Languages' : 'Idiomas' ?></h3>
    <ul class="plain"><?php foreach ($d['languages'] as $l): ?><li><?= e($l) ?></li><?php endforeach; ?></ul>
  </section>

  <p class="foot">
    <span><?= e($d['profile']['email']) ?></span>
    <span><?= e(site_url($d['lang'] === 'en' ? '/en' : '/')) ?></span>
  </p>

</main>
</body>
</html>
