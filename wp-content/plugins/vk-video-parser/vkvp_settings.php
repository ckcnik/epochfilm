<div class="wrap">
    <h2><?php _e('Settings') ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields( 'vkvp-settings-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Client id:</th>
                <td><input type="text" name="vkvp_client_id" value="<?php echo get_option('vkvp_client_id'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Client secret:</th>
                <td><input type="text" name="vkvp_client_secret" value="<?php echo get_option('vkvp_client_secret'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Логин на кинопоиске:</th>
                <td><input type="text" name="vkvp_kinopisk_login" value="<?php echo get_option('vkvp_kinopisk_login'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Пароль на кинопоиске:</th>
                <td><input type="password" name="vkvp_kinopisk_password" value="<?php echo get_option('vkvp_kinopisk_password'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Логин на IMDB:</th>
                <td><input type="text" name="vkvp_imdb_login" value="<?php echo get_option('vkvp_imdb_login'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Пароль на IMDB:</th>
                <td><input type="password" name="vkvp_imdb_password" value="<?php echo get_option('vkvp_imdb_password'); ?>" /></td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>