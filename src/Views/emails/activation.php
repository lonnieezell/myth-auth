<p>This is activation email for your account on <?= site_url() ?>.</p>

<p>To activate your account use this URL.</p>

<p><a href="<?= url_to('activate-account') . '?token=' . $hash ?>">Activate account</a>.</p>

<br>

<p>If you did not registered on this website, you can safely ignore this email.</p>
