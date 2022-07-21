<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.svg" type="image/svg+xml" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?> data-page-state="loading">
    <header class="l-header">
      <?php if(is_front_page()): ?>
        <h1 class="l-header__logo">
          <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/src/img/img_header_logo.png" alt=""></a>
        </h1>
      <?php else: ?>
        <div class="l-header__logo">
          <a href="/"><img src="<?php echo get_template_directory_uri(); ?>/src/img/img_header_logo.png" alt=""></a>
        </div>
      <?php endif; ?>
      <?php
        $header_nav = array(
          'theme_location'  => 'header_nav',
          'container'       => '',
          'items_wrap'      => '<nav class="l-header__navigation">%3$s</nav>',
          'depth'           => 2,
          'walker'          => new custom_walker_header_nav
        );
        wp_nav_menu($header_nav);
      ?>
      <button class="l-header__navigation-icon" id="js-navigation-open" aria-label="メニューを開く">Menu</button>
    </header>
    <main class="l-contents">