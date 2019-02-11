<?
require_once 'common.php';
?>
  <style>
      body { 
    background-image: url('/img/loginback.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center; 
}

        .login-form {
            width: 300px;
            position: fixed;
            top: 50%;
            margin-top: -200px;
            left: 50%;
            margin-left: -150px;
            background-color: #ffffff;
            opacity: 0;
            -webkit-transform: scale(.8);
            transform: scale(.8);
        }
    </style>
<body>
    <div class="login-form padding20 block-shadow">
        <form method="POST">
            <h1 class="text-light">Desactivada</h1>
            <hr class="thin"/>
            <div class="padding20">
         	Esta cuenta a sido desactivada contacte al administrador<br><br></div>
            <div class="form-actions">
                <a href="/" class="button primary">Continuar</a>
            </div>
        </form>
    </div>

<script>
    $(function(){
        var form = $(".login-form");
        form.css({
            opacity: 1,
            "-webkit-transform": "scale(1)",
            "transform": "scale(1)",
            "-webkit-transition": ".5s",
            "transition": ".5s"
        });
    });
</script>
</body>