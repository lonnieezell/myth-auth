<?= view('Myth\Auth\Views\_header') ?>

<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header">Forgot Your Password?</h2>
                <div class="card-body">

                    <?= view('Myth\Auth\Views\_message_block') ?>

                    <p>No problem! Enter your email below and we will send instructions to reset your password.</p>

                    <form action="<?= route_to('forgot') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control <?php if(session('errors.email')) : ?>is-invalid<?php endif ?>"
                                   name="email" aria-describedby="emailHelp" placeholder="Enter email">
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        </div>

                        <br>

                        <button type="submit" class="btn btn-primary btn-block">Send Instructions</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?= view('Myth\Auth\Views\_footer') ?>
