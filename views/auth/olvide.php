<div class="contenedor olvide">
    <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu acceso a UpTask</p>

        <?php include_once __DIR__ . "/../templates/alertas.php" ?>
        <form class="formulario" method="POST" action="/olvide">
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    placeholder="Email de tu cuenta"
                    name="email"
                />
            </div>
            <input type="submit" class="boton" value="Recuperar Acceso" />
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una aquí</a>
            <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
        </div>
    </div>
</div>