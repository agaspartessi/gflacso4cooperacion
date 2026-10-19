# Campus Cooperación para Moodle 5.3

Base Cooperación: `0843e340c96e09b654fc8e2663115d8ff176f4c4`.
Referencia Uruguay: rama `moodle-5.3`, release `2.0.0-beta6`.
Referencia Moodle: `MOODLE_503_STABLE`, commit `344c92d5bb70f37306829d7739e2710305a39c3e`.

## Cambios

- Cajones: se heredan dimensiones, anclas, scroll, transiciones y cabeceras de Boost 5.3. Se retiran las posiciones antiguas y los desplazamientos por breadcrumb.
- Los botones de apertura quedan en los bordes; conservan el violeta de Cooperación, con hover verde. Cierre y foco utilizan la estructura de Boost.
- Ancho limitado compartido con los cajones: 1000px. Altura de navbar: 62px. Se adapta el ancho de Tiles para que los mosaicos expandidos respeten las anclas de Boost.
- Navegación MDS: pestañas rectangulares, texto blanco sobre el header institucional azul, hover y selección de Cooperación. Triángulo verde debajo de la etiqueta activa.
- Footer: se retiran cálculos de ancho y desplazamiento por cajón y se conserva el contenido institucional de Argentina. El mapa se obtiene del propio theme.
- Login: `templates/core/login_layout.mustache` recupera la estructura anterior, con mapa verde y formulario a la derecha en escritorio, adaptable a móvil. No renderiza el panel de imagen predeterminada de Boost 5.3. El formulario sigue siendo el de core para conservar autenticación, recuperación, proveedores externos y MFA. Puede mostrar los textos nuevos de Moodle 5.3.
- Se preservan imágenes, logos, footer, configuración, paleta y estilos de contenidos de Cooperación. No se copian los recursos ni colores de Uruguay.
- SCSS: Boost incorpora marca y Raw initial SCSS; luego variables propias, preset seleccionado y estilos de Cooperación; al final Raw SCSS y fondos de Boost. Sin duplicar ajustes. Plain añade los tokens design-system que necesita Moodle 5.3.

## Validación realizada

- Compilación con Dart Sass de los presets default y plain usando el SCSS real del Boost de referencia.
- Sintaxis PHP mediante php-parser y sintaxis de verificadores JavaScript mediante Node.
- `git diff --check`.

No se ejecutó Moodle ni se accedió a la base de datos del campus. Este entorno no dispone de PHP y la descarga de Chromium falló; no se pudo ejecutar el compilador PHP de Moodle ni la revisión visual en navegador. Los verificadores heredados y adaptados quedan disponibles en `tools/` para un entorno con PHP y Chromium. No se presentan como pruebas ejecutadas.

## Instalación y pruebas pendientes

1. Descargar la rama `moodle-5.3`. Renombrar la carpeta a `gflacso4cooperacion`.
2. Sustituir el theme únicamente en testing: `<moodle>/public/theme/gflacso4cooperacion`.
3. Administración del sitio > Notificaciones: registrar la versión `2026101901`.
4. Purgar todas las cachés y recargar con Ctrl+F5.
5. Revisar Raw initial SCSS y Raw SCSS: sus reglas finales pueden reintroducir posiciones antiguas de cajones, login y navbar.
6. Comprobar login, recuperación, instrucciones de acceso y autenticación externa/MFA si se usan; escritorio y celular.
7. Revisar Tablero, Mis cursos, menús y footer; cursos representativos con cajones cerrados, abiertos individualmente y juntos. En Tiles, expandir y volver. Comprobar Onetopic, Multitopic y demás formatos utilizados.
8. Revisar roles estudiante/docente, modo edición, bloques y calificaciones.

Versión BETA hasta completar esas pruebas. El modo oscuro experimental no está adaptado a la identidad institucional.

## Reproducir verificaciones con PHP y navegador

```bash
php tools/check_compatibility.php /ruta/a/moodle /tmp/coop-css
npm install --no-save playwright mustache
npx playwright install chromium
node tools/check_drawers.cjs /ruta/a/moodle /tmp/coop-css/moodle-default.css /tmp/coop-drawers
node tools/check_navigation.cjs /tmp/coop-drawers
node tools/check_login.cjs /ruta/a/moodle /tmp/coop-css/moodle-default.css /tmp/coop-login
```

El nombre del CSS depende del nombre de carpeta del checkout de Moodle. Los servicios externos se simulan; estos checks no sustituyen las pruebas del campus.

## Beta2 — formulario de ingreso como en 5.1

Se añade `templates/core/loginform.mustache`, partiendo del formulario real de 5.3 y conservando sus tokens, mensajes, CAPTCHA, proveedores externos y JavaScript nativo. Se retiran el saludo, subtítulo e iconos nuevos; las etiquetas siguen presentes para lectores de pantalla. Se recuperan campos compactos, logo enmarcado, recuperación debajo del botón y panel de registro. El texto predeterminado de registro usa `loginsteps` y las instrucciones administrativas se conservan cuando existen. El panel crece con su contenido y los botones admiten varias líneas en móvil.

Verificados: compilación Dart Sass default/plain y ocho escenarios de renderizado Mustache (registro, instrucciones y formulario habilitados/deshabilitados), tokens y acceso externo. Continúa pendiente la verificación visual en navegador y en el campus.
