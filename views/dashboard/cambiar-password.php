
<?php include_once __DIR__ . "/header-dashboard.php"; ?>
    <div class="contenedor-sm">
        <?php  include_once __DIR__ . "/../templates/alertas.php" ?>

        <a href="/perfil" class="enlace">Volver a perfil</a>

        <form class="formulario" method="POST" action="/cambiar-password">
            <div class="campo">
                <label for="password_actual">Password actual</label>
                <input
                    type="password"
                    id="password_actual"
                    name="password_actual"
                    placeholder="Tu password actual"
                />
            </div>

            <div class="campo">
                <label for="password_nuevo">Nuevo password</label>
                <input
                    type="password"
                    id="password_nuevo"
                    name="password_nuevo"
                    placeholder="Tu nuevo password"
                />
            </div>

            <div class="campo">
                <label for="password2">Repetir password</label>
                <input
                    type="password"
                    id="password2"
                    name="password2"
                    placeholder="Repetir tu nuevo password"
                />
            </div>

            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
<?php include_once __DIR__ . "/footer-dashboard.php"; ?>