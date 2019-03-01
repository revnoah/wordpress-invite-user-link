<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

    <div>
			<header>
				<h1 class="page-title">Finish Signup</h1>
			</header>
			<div class="page-content">
				<p class="description text-muted">
					Your account is almost ready to go. Just finish filling out this 
					info to get started.
				</p>
				<?php
					// Include the page content template.
					invite_user_link_get_template_part('invite-user-link');
				?>
			</div>
		</div>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>