<?php
get_header();

echo '<h1>' . __( "Darn it! This page 404'd", 'lingo' ) . '</h1>';
echo '<br />';
echo '<img src="' . get_template_directory_uri() . '/images/404.jpg" alt="" />';

get_footer();
