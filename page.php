<?php 
  $page = get_page(get_the_ID());
  $search = array('-', '_');
  $replace = array(' ', ' ');
  $slug = str_replace($search, $replace, $page->post_name);
  get_header();
?>

<div class="c-under-kv" id="js-under-kv">
  <div class="c-under-kv__page-title">
    <h1 class="c-under-kv__title-large" id="js-under-kv-title"><?php echo the_title(); ?></h1>
    <span class="c-under-kv__title-small"><?php echo ucwords($slug); ?></span>
  </div>
  <ul class="c-under-kv__bread">
    <li class="c-under-kv__bread-item"><a href="<?php echo home_url('/'); ?>">Top</a></li>
      <?php foreach(array_reverse(get_post_ancestors($post->ID)) as $parid): ?>
        <li class="c-under-kv__bread-item"><a href="<?php echo get_page_link($parid);?>"><?php echo ucwords(str_replace($search, $replace, get_page($parid)->post_name)); ?></a></li>
      <?php endforeach; ?>
    <li class="c-under-kv__bread-item c-under-kv__bread-item--current"><?php echo ucwords($slug); ?></li>
  </ul>
</div>

<?php the_content(); ?>

<?php get_footer(); ?>