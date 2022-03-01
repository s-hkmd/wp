<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
    <!-- Web Fonts -->
    <script>
      (function(d) {
        var config = {
          kitId: 'vla1ume',
          scriptTimeout: 3000,
          async: true
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    </script>
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