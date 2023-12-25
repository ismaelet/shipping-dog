{% block main %}

<div id="bgr" class="bgr" <?= background_image('bgr.jpg', $view) ?>></div>

<div id="content" class="grid hei100 just-cen">

	<div id="title-box" class="txt-cen">

		<h1 id="title" class="txt-upp txt-wht txt-bold pado1">Errore 404</h1>
		<p id="stitle" class="txt-15rem txt-wht mart10px">Pagina non trovata</p>

		<a href="<?= client_url('/') ?>">
			<p class="button txt-wht txt-1rem txt-upp pado2 inlb mart2 br-c1-h">Torna alla home</p>
		</a>

	</div>

</div>

{% end main %}