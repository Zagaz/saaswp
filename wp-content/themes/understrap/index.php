<?php
$name_error = '';
$email_error = '';
$success_message = '';

if (is_front_page()) {
    is_user_logged_in() ? get_header('logged') : get_header('blank');
} else {
    is_user_logged_in() ? get_header('logged') : get_header('blank');

    if (isset($_POST['submit'])) {
        $name  = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');

        if (empty($name)) {
            $name_error = 'Por favor, preencha seu nome.';
        }

        if (!is_email($email)) {
            $email_error = 'Por favor, forneça um e-mail válido.';
        } elseif (email_exists($email)) {
            $email_error = 'Este e-mail já está registrado.';
        }

        if (empty($name_error) && empty($email_error)) {
            $random_password = wp_generate_password(6, false, false);

            $user_id = wp_create_user($email, $random_password, $email);

            if (!is_wp_error($user_id)) {
                wp_update_user([
                    'ID'           => $user_id,
                    'display_name' => $name,
                    'first_name'   => $name
                ]);

                $user = new WP_User($user_id);
                $user->set_role('subscriber');

                $subject = 'Confirmação de Cadastro';
                $message = "Olá $name,\n\nSeu cadastro foi realizado com sucesso!\n\nLogin: $email\nSenha: $random_password\n\nAcesse o site aqui: " . home_url();

                if (wp_mail($email, $subject, $message)) {
                    $success_message = 'User registered successfully.';
                } else {
                    $email_error = 'Erro ao enviar o e-mail de confirmação.';
                }
            } else {
                $email_error = 'Erro ao criar o usuário: ' . $user_id->get_error_message();
            }
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-6">
            <?php if (!empty($success_message)) : ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <div class="registration-form <?php echo !empty($success_message) ? 'd-none' : ''; ?>">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo esc_attr($_POST['name'] ?? ''); ?>" />
                        <span class="text-danger"><?php echo $name_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="text" class="form-control" id="email" name="email" 
                               value="<?php echo esc_attr($_POST['email'] ?? ''); ?>" />
                        <span class="text-danger"><?php echo $email_error; ?></span>
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Cadastrar</button>
                </form>
            </div>
        </div>

        <div class="col-6">
            <div class="login-form">
                <?php 
                $args = array(
                    'echo'              => true,
                    'redirect'          => (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                    'form_id'           => 'loginform',
                    'label_username'    => __('Username or Email Address'),
                    'label_password'    => __('Password'),
                    'label_remember'    => __('Remember Me'),
                    'label_log_in'      => __('Log In'),
                    'id_username'       => 'user_login',
                    'id_password'       => 'user_pass',
                    'id_remember'       => 'rememberme',
                    'id_submit'         => 'wp-submit',
                    'remember'          => true,
                    'value_username'    => '',
                    'value_remember'    => false,
                    'required_username' => false,
                    'required_password' => false,
                );
                wp_login_form($args);
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
