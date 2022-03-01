    </main>
    <footer class="l-footer">
      <div class="l-footer__main">
        <div class="l-footer__left">
          <a class="l-footer__logo" href="/">
            <img
              src="<?php echo get_template_directory_uri(); ?>/src/img/img_footer_logo.png"
              alt=""
              height=""
              width=""
              loading="lazy"
              decoding="async"
            >
          </a>
          <?php
            $footer_nav = array(
              'theme_location'  => 'footer_nav',
              'container'       => '',
              'items_wrap'      => '<nav class="l-footer__navigation">%3$s</nav>',
              'depth'           => 2,
              'walker'          => new custom_walker_footer_nav
            );
            wp_nav_menu($footer_nav);
          ?>
        </div>
      </div>
      <span class="l-footer__copyright" translate="no"></span>
    </footer>

    <!-- Global Navigation -->
    <nav class="p-gnav">
      <button class="p-gnav__close" id="js-navigation-close" aria-label="メニュを閉じる"></button>
      <div class="p-gnav__main">
        <?php
          $gnav = array(
            'theme_location'  => 'footer_nav',
            'container'       => '',
            'items_wrap'      => '<nav class="p-gnav__navigation">%3$s</nav>',
            'depth'           => 2,
            'walker'          => new custom_walker_gnav
          );
          wp_nav_menu($gnav);
        ?>
        <div class="p-gnav__language">
          <a class="p-gnav__language-item p-gnav__language-item--current" href="#" aria-label="日本語">JP</a>
          <a class="p-gnav__language-item" href="#" aria-label="English">EN</a>
          <a class="p-gnav__language-item" href="#" aria-label="Chinese">CH</a>
        </div>
      </div>
    </nav>

    <!-- Scripts -->
    <script src="<?php echo get_template_directory_uri(); ?>/dist/js/common.js"></script>
    <?php
      if (is_front_page()) echo '<script src="' . get_template_directory_uri() . '/dist/js/front-page.js"></script>';
      if (is_page() && !is_front_page()) echo '<script src="' . get_template_directory_uri() . '/dist/js/page.js"></script>';
      if (is_home() || is_single() || is_archive()) echo '<script src="' . get_template_directory_uri() . '/dist/js/post.js"></script>';
    ?>
    <?php wp_footer(); ?>
  </body>
</html>