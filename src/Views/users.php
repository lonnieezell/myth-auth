<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">
	<table class="table">
		<thead>
			<tr>
				<th scope="col"><?=lang('Auth.email')?></th>
				<th scope="col"><?=lang('Auth.username')?></th>
				<th scope="col"><?=lang('Auth.createdAt')?></th>
				<th scope="col"><?=lang('Auth.active')?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user) { ?>
				<tr>
					<td><?= $user->email ?></td>
					<td><?= $user->username ?></td>
					<td><?= $user->created_at ?></td>
					<td><?= $user->active ? lang('Auth.yes') : lang('Auth.no') ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<?= $this->endSection() ?>
