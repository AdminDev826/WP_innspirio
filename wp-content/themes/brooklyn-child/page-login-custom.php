<?php
/*
* Template Name: Custom Login Page
*/

wp_head();

?>
<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo get_bloginfo('template_url') ?>/inc/libs/font-awesome-4.6.3/css/font-awesome.min.css">
<body class="login-page">
<?php if ( have_posts() ) : ?>

    <?php /* Start the Loop */ ?>

    <?php while ( have_posts() ) : the_post(); ?>

        <?php

        the_content();
        ?>

    <?php endwhile; ?>

<?php else : ?>

    <?php get_template_part( 'no-results', 'index' ); ?>

<?php endif; ?>
</body>
