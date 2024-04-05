{% block form-body %}

<p class="pad-v-1 fa-center"><?= ('Are you sure you want to leave?') ?></p>

{% end form-body %}

{% block form-footer %}

<button type="submit" name="submit" class="button">Logout</button>
<a href="<?= client_url() ?>" class="button button--secondary"><?= ('Back') ?></a>

{% end form-footer %}