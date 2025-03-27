<?php
get_header();
?>

	<main id="primary" class="site-main">

		<button class="btn btn__orange btn__delete"><img src="<?php echo get_template_directory_uri() . '/assets/img/icons/delete.svg' ?>" alt="delete button" loading="lazy"></button>
		<button class="btn btn__orange btn__edit"><img src="<?php echo get_template_directory_uri() . '/assets/img/icons/edit.svg' ?>" alt="edit button" loading="lazy"></button>
		<button class="btn btn__green"><?php _e('Diabetes world') ?></button>
		<button class="btn btn__border_green">Schedule appointment</button>
		<button class="btn btn__border_black">Schedule appointment</button>

		<span class="label-top">
			<input type="tel" id="input" required />
			<label for="input">input</label>
		</span>

		<span class="label-top reg phone">
			<input type="tel" id="phone" required />
			<label for="phone">ןופלט רפסמ</label>
		</span>

		<span class="label-top reg user">
			<input type="text" id="user" required />
			<label for="user">אלמ םש</label>
		</span>

		<span class="label-top reg card">
			<input type="text" id="card" required />
			<label for="card">תוהז תדועת</label>
		</span>

		<span class="label-top reg email">
			<input type="email" id="email" required />
			<label for="email">ל"אוד תבותכ</label>
		</span>

	</main><!-- #main -->

<?php
get_footer();
