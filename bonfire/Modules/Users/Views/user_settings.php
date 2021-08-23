<?php $this->extend('master') ?>

<?php $this->section('main') ?>
    <x-page-head>
        <x-module-title><i class="fas fa-user"></i> Users</x-module-title>
        <h2>Settings</h2>
    </x-page-head>

    <x-admin-box>
        <form action="/admin/settings/users" method="post">
            <?= csrf_field() ?>

            <fieldset>
                <legend>Registration</legend>

                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="allowRegistration"
                                   value="1" id="allow-registration"
                                <?php if(old('allowRegistration', setting('Auth.allowRegistration'))) : ?> checked <?php endif ?>
                            >
                            <label class="form-check-label" for="allow-registration">
                                Allow users to register themselves on the site
                            </label>
                        </div>
                    </div>
                    <div class="col px-5">
                        <p class="text-muted small">If unchecked, an admin will need to create users.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="emailActivation"
                                   value='Shield\Authentication\Actions\EmailActivate' id="email-activation"
                                <?php if(old('emailActivation', setting('Auth.actions')['register']) == 'Shield\Authentication\Actions\EmailActivate') : ?>
                                    checked
                                <?php endif ?>
                            >
                            <label class="form-check-label" for="allow-registration">
                                Force email verification after registration?
                            </label>
                        </div>
                    </div>
                    <div class="col px-5">
                        <p class="text-muted small">If checked, will send a code via email for them to confirm.</p>
                    </div>
                </div>
            </fieldset>

            <hr>

            <fieldset x-data="{remember: <?= old('allowRemember', setting('Auth.sessionConfig')['allowRemembering']) ? 1 : 0 ?>}">
                <legend>Login</legend>

                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="allowRemember"
                                   value="1" id="allow-remember"
                                <?php if(old('allowRemember', setting('Auth.sessionConfig')['allowRemembering'])) : ?> checked <?php endif ?>
                                   x-on:click="remember = ! remember"
                            >
                            <label class="form-check-label" for="allow-registration">
                                Allow users to be "remembered"
                            </label>
                        </div>
                    </div>
                    <div class="col px-5">
                        <p class="text-muted small">This makes it so users do not have to login every visit.</p>
                    </div>
                </div>
                <div class="row mb-4" x-show="remember">
                    <div class="form-group col-12 col-sm-4">
                        <label for="rememberLength" class="form-label">Remember Users for how long?</label>
                        <select name="rememberLength" class="form-select">
                            <option value="0">How long to remember...</option>
                            <?php if(isset($rememberOptions) && count($rememberOptions)) : ?>
                                <?php foreach($rememberOptions as $title => $seconds) : ?>
                                    <option value="<?= $seconds ?>"
                                        <?php if (old('rememberDuration', setting('Auth.sessionConfig')['rememberLength']) == $seconds) : ?> selected <?php endif?>
                                    >
                                        <?= $title ?>
                                    </option>
                                <?php endforeach ?>
                            <?php endif ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="email2FA"
                                   value="Shield\Authentication\Actions\Email2FA" id="email-activation"
                                <?php if(old('email2FA', setting('Auth.actions')['login']) == 'Shield\Authentication\Actions\Email2FA') : ?>
                                    checked
                                <?php endif ?>
                            >
                            <label class="form-check-label" for="allow-registration">
                                Force 2FA check after login?
                            </label>
                        </div>
                    </div>
                    <div class="col px-5">
                        <p class="text-muted small">If checked, will send a code via email for them to confirm.</p>
                    </div>
                </div>
            </fieldset>

            <hr>

            <fieldset>
                <legend>Passwords</legend>

                <div class="row">
                    <div class="form-group col-12 col-sm-4">
                        <label for="minimumPasswordLength" class="form-label">Minimum Password Length</label>
                        <input type="number" name="minimumPasswordLength" class="form-control" min="8" step="1"
                               value="<?= old('minimumPasswordLength', setting('Auth.minimumPasswordLength')) ?>">
                        <?php if(has_error('minimumPasswordLength')) : ?>
                            <p class="text-danger"><?= error('minimumPasswordLength') ?></p>
                        <?php endif ?>
                    </div>
                    <div class="col px-5">
                        <p class="text-muted small mt-5">A minimum value of 8 is suggested. 10 is recommended.</p>
                    </div>
                </div>

                <br>

                <label for="passwordValidators" class="form-label">Password Validators</label>
                <p class="text-muted">These rules determine how secure a password must be.</p>

                <div class="row">
                    <!-- Password Validators -->
                    <div class="form-group col-6 col-sm-4">

                        <!-- Composition Validator -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="validators[]"
                                   value="Sparks\Shield\Authentication\Passwords\CompositionValidator"
                                <?php if(in_array('Sparks\Shield\Authentication\Passwords\CompositionValidator',
                                    old('validators', setting('Auth.passwordValidators')))) : ?>
                                    checked
                                <?php endif ?>
                            >
                            <label class="form-check-label">
                                Composition Validator
                            </label>
                        </div>

                        <!-- Nothing Personal Validator -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="validators[]"
                                   value="Sparks\Shield\Authentication\Passwords\NothingPersonalValidator"
                                <?php if(in_array('Sparks\Shield\Authentication\Passwords\NothingPersonalValidator',
                                    old('validators', setting('Auth.passwordValidators')))) : ?>
                                    checked
                                <?php endif ?>
                            >
                            <label class="form-check-label">
                                Nothing Personal Validator
                            </label>
                        </div>

                        <!-- Dictionary Validator -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="validators[]"
                                   value="Sparks\Shield\Authentication\Passwords\DictionaryValidator"
                                <?php if(in_array('Sparks\Shield\Authentication\Passwords\DictionaryValidator',
                                    old('validators', setting('Auth.passwordValidators')))) : ?>
                                    checked
                                <?php endif ?>
                            >
                            <label class="form-check-label">
                                Dictionary Validator
                            </label>
                        </div>

                        <!-- Pwned Validator -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="validators[]"
                                   value="Sparks\Shield\Authentication\Passwords\PwnedValidator"
                                <?php if(in_array('Sparks\Shield\Authentication\Passwords\PwnedValidator',
                                    old('validators', setting('Auth.passwordValidators')))) : ?>
                                    checked
                                <?php endif ?>
                            >
                            <label class="form-check-label">
                                Pwned Validator
                            </label>
                        </div>

                        <p class="text-muted small mt-4">Note: Unchecking these will reduce the password security requirements.</p>
                    </div>
                    <div class="col-6 px-4">
                        <ul class="text-muted small">
                            <li>Composition Validator primarily checks the password length requirements.</li>
                            <li>Nothing Personal Validator checks the password for similarities between the password,
                                the email, username, and other personal fields to ensure they are not too similar and easy to guess.</li>
                            <li>Dictionary Validator checks the password against nearly 600,000 leaked passwords.</li>
                            <li>Pwned Validator uses a <a href="https://haveibeenpwned.com/Passwords" target="_blank">third-party site</a>
                                to check the password against millions of leaked passwords.</li>
                            <li>NOTE: You only need to select only one of Dictionary and Pwned Validators.</li>
                        </ul>
                    </div>
                </div>
            </fieldset>

            <br><hr>

            <div class="text-end px-5 py-3">
                <input type="submit" value="Save Settings" class="btn btn-primary btn-lg">
            </div>
        </form>
    </x-admin-box>
<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>

</script>
<?php $this->endSection() ?>