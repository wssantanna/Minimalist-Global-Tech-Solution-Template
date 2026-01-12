<?php
/**
 * Custom search form template - Fullscreen Modal
 *
 * @package Theme_WordPress
 */
?>

<button type="button" class="btn btn-link text-light p-0" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="<?php echo esc_attr_x( 'Abrir busca', 'button aria-label', 'theme-wordpress' ); ?>">
	<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16" aria-hidden="true">
		<path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
	</svg>
</button>

<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content bg-dark bg-opacity-95 position-relative">
			<button type="button" class="btn btn-link text-white text-decoration-none position-absolute top-0 end-0 m-3 fs-5 z-3" data-bs-dismiss="modal" aria-label="<?php echo esc_attr_x( 'Fechar', 'close button', 'theme-wordpress' ); ?>">
				<?php echo esc_html_x( 'Fechar', 'close button text', 'theme-wordpress' ); ?>
			</button>
			<div class="modal-body d-flex align-items-center justify-content-center z-0">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-12 col-md-10 col-lg-8 col-xl-6">
							<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input
									type="search"
									class="form-control form-control-lg bg-dark text-white border-0 border-bottom border-2 border-light rounded-0 px-3 pb-3 placeholder__text-white"
									id="searchInput"
									placeholder="<?php echo esc_attr_x( 'O que você está procurando?', 'placeholder', 'theme-wordpress' ); ?>"
									value="<?php echo get_search_query(); ?>"
									name="s"
									aria-label="<?php echo esc_attr_x( 'Campo de busca', 'input aria-label', 'theme-wordpress' ); ?>"
								/>
								<div class="form-text text-white-50 text-center mt-3">
									<small><?php echo esc_html_x( 'Pressione ENTER para buscar ou ESC para fechar', 'keyboard hint', 'theme-wordpress' ); ?></small>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const searchModal = document.getElementById('searchModal');
	const searchInput = document.getElementById('searchInput');

	if (searchModal && searchInput) {
		searchModal.addEventListener('shown.bs.modal', function () {
			searchInput.focus();
		});
	}
});
</script>
