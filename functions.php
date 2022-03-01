<?php
/**
 * headにmetaタグを出力
 */
add_action('wp_head', 'create_meta');

function create_meta() {
  if (is_front_page() || is_home() || is_singular()) {
    global $post;
    $meta_title = '';
    $meta_desc = '';
    $ogp_url = '';
    $ogp_img = '';
    $insert = '';

    if (is_front_page() || is_home()) {
      //トップページ
      $meta_title = get_bloginfo('name');
      $meta_desc = get_bloginfo('description');
      $ogp_url = home_url();
    } elseif (is_singular()) {
      //投稿ページ＆固定ページ
      setup_postdata($post);
      $meta_title = $post->post_title . ' | ' . get_bloginfo('name');
      $meta_desc = mb_substr(get_the_excerpt(), 0, 100);
      $ogp_url = get_permalink();
      wp_reset_postdata();
    }

    //og:type
    $ogp_type = (is_front_page() || is_home()) ? 'website' : 'article';

    //og:image
    if (is_singular() && has_post_thumbnail()) {
      $ps_thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
      $ogp_img = $ps_thumb[0];
    } else {
      $ogp_img = get_template_directory_uri() . '/src/img/ogimage.jpg';
    }

    //出力するOGPタグ
    $insert .= '<title>'.esc_attr($meta_title).'</title>' . "\n";
    $insert .= '<meta property="og:image" content="'.esc_url($ogp_img).'" />' . "\n";
    $insert .= '<meta property="og:image:secure_url" content="'.esc_url($ogp_img).'" />' . "\n";
    $insert .= '<meta property="twitter:image" content="'.esc_url($ogp_img).'" />' . "\n";
    echo $insert;
  }
}

/**
 * CSS読み込み
 */
function load_css() {
  wp_enqueue_style('style', get_template_directory_uri() . '/dist/css/style.css?t=' . date('Y-m-d H:i:s'));
}
add_action('wp_enqueue_scripts', 'load_css');

/**
 * アイキャッチサムネイル
 */
add_theme_support('post-thumbnails');
add_image_size('thumb100',100,100,true);
add_image_size('thumb110',110,110,true);

/**
 * search-〇〇.phpを使用するための記述
 */
add_filter('template_include','custom_search_template');
function custom_search_template($template){
  if (is_search()) {
    $post_types = get_query_var('post_type');
    foreach ((array) $post_types as $post_type)
    $templates[] = 'search-{$post_type}.php';
    $templates[] = 'search.php';
    $template = get_query_template('search',$templates);
  }
  return $template;
}

/**
 * 検索結果に投稿ページのみ表示
 */
function SearchFilter($query) {
  if ($query->is_search) {
    $query->set('post_type', array('post', 'page'));
  }
  return $query;
}
add_filter('pre_get_posts','SearchFilter');

/**
 * ユーザーが検索したワードをハイライト
 */
function wps_highlight_results($text) {
  if (is_search()) {
    $sr = get_query_var('s');
    $keys = explode(" ",$sr);
    $text = preg_replace('/(' . implode('|', $keys) . ')/iu', '<span class="search-highlight">' . $sr . '</span>', $text);
  }
  return $text;
}

if (!is_admin()) {
  add_filter('the_title', 'wps_highlight_results');
  add_filter('the_content', 'wps_highlight_results');
}

/**
 * RSS
 */
add_theme_support('automatic-feed-links');

/**
 * コンテンツエディタ周り
 */
// クラシックエディタ(ビジュアルエディタ)にCSSを反映
add_theme_support('editor-style');
add_editor_style('./dist/css/editor-style.css');

// エディタのiframe内のbody要素にクラスを追加
function custom_editor_settings($initArray){
  global $typenow;
  global $post;
  $parent_id = $post->post_parent;
  if (get_post_meta($parent_id, '_wp_page_template', true) === 'page-templates/treatment-archive.php') {
    $initArray['body_class'] = 'editor-area-page c-treatment-contents';
  } elseif ($typenow == 'page') { // 固定ページの場合
    $initArray['body_class'] = 'editor-area-page';
  } else { // 投稿ページの場合
    $initArray['body_class'] = 'editor-area-post p-article-detail__body c-wp-contents';
  }
  return $initArray;
}
add_filter('tiny_mce_before_init', 'custom_editor_settings');

// キャッシュを削除
function extend_tiny_mce_before_init($mce_init){
  $mce_init['cache_suffix'] = 'v=' . time();
  return $mce_init;    
}
add_filter('tiny_mce_before_init', 'extend_tiny_mce_before_init');

// エディタにカスタムスタイルを追加
function tiny_style_formats($settings) {
  $style_formats = array(
    array(
      'title' => '見出し1',
      'block' => 'h2',
    ),
    array(
      'title' => '見出し2',
      'block' => 'h3',
    ),
    array(
      'title' => '見出し3',
      'block' => 'h4',
    ),
    array(
      'title' => '段落テキスト',
      'block' => 'p',
    ),
    array(
      'title' => 'ポイント',
      'block' => 'p',
      'classes' => 'point',
    ),
    array(
      'title' => '囲みテキスト1',
      'block' => 'p',
      'classes' => 'frame1',
    ),
    array(
      'title' => '囲みテキスト2',
      'block' => 'p',
      'classes' => 'frame2',
    ),
    array(
      'title' => 'アンダーライン1',
      'inline' => 'span',
      'classes' => 'under_line1',
    ),
    array(
      'title' => 'アンダーライン2',
      'inline' => 'span',
      'classes' => 'under_line2',
    ),
    array(
      'title' => 'リンクボタン',
      'inline' => 'a',
      'classes' => 'c-button',
      'attributes' => array(
        'href' => '#'
      ),
      'wrapper' => true,
    ),
    array(
      'title' => 'スタイルをリセット',
      'selector' => '*',
      'remove' => 'all',
    ),
  );
  $settings['style_formats'] = json_encode($style_formats);
  return $settings;
}
add_filter('tiny_mce_before_init', 'tiny_style_formats');

// ビジュアルエディタを非表示にする
// function disable_visual_editor($wp_rich_edit) {
//   $posttype = get_post_type();
//   if ($posttype === 'page') {
//     return false;
//   } else {
//     return $wp_rich_edit;
//   }
// }
// add_filter('user_can_richedit', 'disable_visual_editor');

/**
 * 画像に重ねる文字の色
 */
define('HEADER_TEXTCOLOR', '');

/**
 * 画像に重ねる文字を非表示にする
 */
define('NO_HEADER_TEXT',true);

/**
 * 投稿用ファイルを読み込む
 */
get_template_part('functions/create-thread');

/**
 * カスタム背景
 */
add_theme_support( 'custom-background' );

/**
 * ページネーション
 */
add_shortcode('pagination', 'pagination_short_code');
function pagination_short_code() {
  global $wp_query;
  $big = 999999999;
  $pages = paginate_links(array(
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format' => '?paged=%#%',
    'current' => max(1, get_query_var('paged')),
    'total' => $wp_query->max_num_pages,
    'end_size' => 1,
    'mid_size' => 3,
    'prev_text' => 'Prev',
    'next_text' => 'Next',
    'type' => 'array'
  ));

  if (is_array($pages)) {
    echo '<ul class="c-pagination">';
    foreach ($pages as $page) {
      echo '<li class="c-pagination__item">' . "$page" . '</li>';
    }
    echo '</ul>';
  }
}

/**
 * 更新日の追加
 */
function get_mtime($format) {
  $mtime = get_the_modified_time('Ymd');
  $ptime = get_the_time('Ymd');
  if ($ptime > $mtime) {
    return get_the_time($format);
  } elseif ($ptime === $mtime) {
    return null;
  } else {
    return get_the_modified_time($format);
  }
}

/**
 * ショートコードを外す
 */
function stinger_noshotcode($content) {
  if (!preg_match( '/\[.+?\]/', $content, $matches)) {
    return $content;
  }
  $content = str_replace($matches[0], '', $content);
  return $content;
}

/**
 * 自動生成のpタグを削除
 */
function wpautop_filter($content) {
  global $post;
  $remove_filter = false;

  $arr_types = array('page'); //ここを変更
  $post_type = get_post_type($post->ID);
  if (in_array($post_type, $arr_types)) $remove_filter = true;

  if ($remove_filter) {
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
  }

  return $content;
}
add_filter('the_content', 'wpautop_filter', 9);

/**
 * ショートコード生成
 */
function my_php_Include($params = array()) {
  extract(shortcode_atts(array('file' => 'default'), $params));
  ob_start();
  include(STYLESHEETPATH . "/$file.php");
  return ob_get_clean();
}
add_shortcode('myphp', 'my_php_Include');

/**
 * 自動生成CSSを削除
 */
add_action('wp_enqueue_scripts', function() {
  wp_deregister_style('wp-block-library');
});

/**
 * パスワード保護している記事を一覧から除外
 */
function customize_main_query($query) {
  if (!is_admin() || $query->is_main_query()) {
    if ($query->is_archive()) {
      $query->set('has_password', false);
    }
  }
}
add_action('pre_get_posts', 'customize_main_query');

/**
 * タームの説明を取得する際に自動生成されるpタグを除外
 */
remove_filter('term_description','wpautop');

/**
 * カスタムメニューを有効化
 */
function register_my_menus() { 
  register_nav_menus(array(
    'header_nav' => 'Header',
    'footer_nav'  => 'Footer',
  ));
}
add_action('after_setup_theme', 'register_my_menus');


/**
 * カスタムメニューのliタグを制御
 */
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
  return is_array($var) ? array_intersect($var, array()) : '';
}

/**
 * ヘッダーナビゲーション
 */
class custom_walker_header_nav extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth = 0, $args = null) {
    $output .= '<div class="l-header__navigation-child">';
  }

  function end_lvl(&$output, $depth = 0, $args = null) {
    $output .= '</div>';
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $caption = $item->title;
    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';

    if ($depth === 0) {
      $output .= '<div class="l-header__navigation-group">';
      $output .= '<a class="l-header__navigation-parent" href="' . $item->url . '" ' . $attributes . '>' . $caption .'</a>';
    } else {
      $output .= '<a class="l-header__navigation-item" href="' . $item->url . '" ' . $attributes . '>' . $caption .'</a>';
    }
    
    return apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  function end_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    if ($depth === 0) {
      $output .= '</div></div>';
    }
  }
}

/**
 * フッターナビゲーション
 */
class custom_walker_footer_nav extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth = 0, $args = null) {
    $output .= '<div class="l-footer__navigation-child">';
  }

  function end_lvl(&$output, $depth = 0, $args = null) {
    $output .= '</div>';
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $caption = $item->title;
    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';

    if ($depth === 0) {
      $output .= '<div class="l-footer__navigation-group">';
      $output .= '<a class="l-footer__navigation-parent" href="' . $item->url . '" ' . $attributes . '>' . $caption .'</a>';
    } else {
      $output .= '<a class="l-footer__navigation-item" href="' . $item->url . '" ' . $attributes . '>' . $caption .'</a>';
    }
    
    return apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  function end_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    if ($depth === 0) {
      $output .= '</div>';
    }
  }
}

/**
 * グローバルナビゲーション
 */
class custom_walker_gnav extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth = 0, $args = null) {
    $output .= '<div class="p-gnav__navigation-child">';
  }

  function end_lvl(&$output, $depth = 0, $args = null) {
    $output .= '</div>';
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $caption = $item->title;
    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';

    if ($depth === 0) {
      $output .= '<div class="p-gnav__navigation-group">';
      $output .= '<a class="p-gnav__navigation-parent" href="' . $item->url . '" ' . $attributes . '>' . $caption .'</a>';
    } else {
      $output .= '<a class="p-gnav__navigation-item" href="' . $item->url . '" ' . $attributes . '>' . $caption .'</a>';
    }
    
    return apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  function end_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    if ($depth === 0) {
      $output .= '</div>';
    }
  }
}

?>