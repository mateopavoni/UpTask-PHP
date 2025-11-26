<div class="contenedor reestablecer">
    <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Reestablece tu password</p>
        <?php include_once __DIR__ . "/../templates/alertas.php" ?>

        <?php if($mostrar) { ?>
            <form class="formulario" method="POST">
                <div class="campo">
                    <label for="password">Nueva password</label>
                    <input
                        type="password"
                        id="password"
                        placeholder="Tu nueva password"
                        name="password"
                    />
                </div>
                <div class="campo">
                    <label for="password2">Repetir nueva password</label>
                    <input
                        type="password"
                        id="password2"
                        placeholder="Repite tu nueva password"
                        name="password2"
                    />
                </div>
                <input type="submit" class="boton" value="Reestablecer Contraseña" />
            </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una aquí</a>
            <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div>
</div>