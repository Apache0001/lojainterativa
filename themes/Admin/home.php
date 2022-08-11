<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>| Admin |</title>
  <link rel="stylesheet" href="<?= asset('/css/style.css') ?>">
</head>
<body>
<!-- ajax load -->
<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <div class="ajax_load_box_title">Aguarde, carrengando...</div>
    </div>
</div>
<main class="main_content">
  <div class="main_content_box">
      <div class="login">
          <!-- form -->
            <form class="form" action="<?= route('/login') ;?>" method="post" autocomplete="off">
                <!-- csrf -->
                <?= csrf_input();?>
                <div class="form_social">
                    <a href="" class="btn btn-facebook">Facebook Login</a>
                    <a href="" class="btn btn-google">Google Login</a>
                </div>
                <div class="login_form_callback">
                </div>

                <label>
                    <span class="field">E-mail:</span>
                    <input value="" type="email" name="email" placeholder="Informe seu e-mail:"/>
                </label>
                <label>
                    <span class="field">Senha:</span>
                    <input autocomplete="" type="password" name="passwd" placeholder="Informe sua senha:"/>
                </label>

                <div class="form_actions">
                    <button class="btn btn-green">Logar-se</button>
                    <a href="" title="Recuperar Senha">Recuperar Senha</a>
                </div>
            </form>
          <!-- end form -->
            <div class="form_register_action">
                <p>Ainda não tem conta?</p>
                <a href="" class="btn btn-blue">Cadastre-se Aqui</a>
            </div>
      </div>
  </div>
</main>

<!-- script -->
<script src="<?= asset("/js/jquery.js"); ?>"></script>
<script src="<?= asset("/js/form.js"); ?>"></script>


</body>
</html>
