<div class="contenedor reestablecer">
    <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Reestablece tu password</p>
        <form class="formulario" method="POST" action="/reestablecer">
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
                <label for="password">Repetir nueva password</label>
                <input
                    type="password"
                    id="password"
                    placeholder="Repite tu nueva password"
                    name="password"
                />
            </div>
            <input type="submit" class="boton" value="Reestablecer Contraseña" />
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una aquí</a>
            <a href="/olvide">¿Olvidaste tu contraseña? Recupera el acceso a tu cuenta aquí</a>
        </div>
    </div>
</div>