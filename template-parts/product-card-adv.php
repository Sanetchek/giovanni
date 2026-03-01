<?php
  $banner = $args['banner'];

  $image = $banner['image'];
  $title = $banner['title'];
  $white_text = $banner['enable_white_title'] ? 'white' : '';
  $link = $banner['link'];
  $label = $banner['link_label'];
?>

<li class="product-card-big banner">
<?php if ($image) : ?>
  <a href="<?php echo esc_url($link); ?>" class="adv-link">
    <?php
    $data = [
      'thumb' => [745, 516],
      'args' => [
        'class' => 'adv-image',
      ],
    ];
    echo liteimage( $image, $data );
    ?>

    <div class="adv-info">
      <h2 class="adv-title <?php echo esc_attr($white_text); ?>"><?php echo esc_html($title); ?></h2>
      <button type="button" class="adv-link-label <?php echo esc_attr($white_text); ?>"><?php echo esc_html($label); ?></button>
    </div>
  </a>
  <?php else: ?>
    <p><?= __('No Image for that banner', 'giovanni') ?></p>
  <?php endif ?>
</li>
