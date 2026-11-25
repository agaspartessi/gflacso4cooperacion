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

## Beta4 — carga explícita de ajustes Raw (4 de noviembre de 2026)

Se registran callbacks propios para ambos campos. Los valores se consultan mediante `get_config('theme_gflacso4cooperacion', ...)`. Si la cadena heredada ya incluye el valor, el callback hijo no lo repite; si no lo incluye, lo incorpora. La consulta de la cadena usa una guarda por objeto y campo para excluir el propio aporte durante la comprobación. No se modifican los ajustes ni los estilos institucionales.

Motivo: en testing ambos campos no se aplicaban, incluso con código mínimo, mientras en Boost sí. El fallo exacto de esa instalación no pudo reproducirse con el checkout oficial de Moodle; esta versión refuerza la carga ante ausencia del aporte del padre o settings incompletos en el objeto del theme. No se presenta esa hipótesis como una causa confirmada del campus.

Verificaciones: carga real de configuración y callbacks con las clases oficiales de Moodle y PHP, marcadores en el CSS compilado por scssphp, ausencia de duplicados con herencia nativa, settings incompletos y sin callbacks del padre. Pendiente confirmar el resultado en testing. `tools/check_compatibility.php` incorpora los casos sin callbacks heredados y con settings incompletos.

## Beta5 — causa confirmada del Raw descartado por el navegador

En dos selectores de post.scss, `:not(:has(p, span, h1, h2, h3, h4, h5, h6, img, div))` interactuaba con los `@extend h1..h6` de Bootstrap. El compilador PHP devolvía éxito, pero generaba alternativas `.h1`…`.h6` con paréntesis sin cerrar. El navegador dejaba de registrar reglas a partir del primer selector defectuoso, incluido el Raw final. Se confirmó en la CSSOM del campus: su última regla era la inmediatamente anterior al selector defectuoso, aunque la respuesta CSS contenía el Raw más adelante.

Se usan H1…H6 dentro de esos dos selectores. En documentos HTML conservan la coincidencia de etiquetas, pero no activan las extensiones Sass de Bootstrap. Se retira el refuerzo de callbacks de beta4, que no resolvía el problema, recuperando la carga original de los ajustes mediante Boost. No se cambian los valores guardados ni el diseño.

## Integración de archivos de Iván — 24/11/2026

Versión `2.0.0-beta6` (`2026112400`). Se integran `pre.scss` y
`post-coop-20-11-26.scss` como `scss/pre.scss` y `scss/post.scss`.
Se mantienen los selectores H1–H6 que evitan el fallo de scssphp y los estilos
del login personalizado. No se cambian ajustes Raw guardados en Moodle.

Observaciones para revisión visual:
- El primario Sass pasa a `#136d52`, mientras `--color-area` sigue en `#258e6e`.
- Los derivados de área usan colores relativos `oklch(from ...)`, que requieren
  soporte del navegador; no se incluyen alternativas para navegadores antiguos.
- Se retira un bloque móvil de Onetopic (hasta 767.98 px): revisar pestañas y
  actividades en celular antes de desplegar en producción.
- Se eliminan seis URLs de iconos C4L que apuntaban al theme académico con una
  revisión fija; comprobar esos componentes en los cursos.

Validación: compilación con el compilador PHP scssphp de Moodle 5.3 en los
presets default, plain, subido, fallback y configurado, incluyendo marcadores
Raw initial/Raw y la comprobación de paréntesis de selectores anidados.
La integración no incluye instalación ni validación visual en el campus.

## Integración PRE, POST y RAW de Iván — 25/11/2026

Versión `2.0.0-beta7` (`2026112500`). Se sustituye el PRE por
`pre_25-11-26.scss` y se usa `post-coop-25-11-26.scss` como base. El archivo
`RAW-para fusionar con POST.scss` se integra por secciones (botones, login,
navegación, cajones, formatos de curso, contenidos, tarjetas y cuestionarios),
consolidando reglas coincidentes y evitando repetir el bloque móvil Onetopic
y los ajustes C4L/stickyfooter ya presentes en el POST.

Correcciones técnicas respecto de los adjuntos:
- `color.scale($primary, $alpha: -75%)` falla en el parser scssphp de Moodle
  5.3. Se utiliza `scale-color($primary, $alpha: -75%) !default`.
- Las variables CSS `--bs-body-bg` y `--bs-tooltip-color` interpolan
  `#{$white}` para no emitir una variable Sass literal al CSS.
- La animación de Multitopic referenciaba `desplegarMenu`, sin definición;
  se reutiliza `desplegarMenuHorizontal`, que sí está definida.
- Las animaciones de Onetopic se limitan al escritorio; el bloque móvil se
  conserva después y se respetan las preferencias de movimiento reducido
  sin dejar las pestañas con opacidad cero.
- La navegación del libro cambia a -100 px en escritorio, conservando la
  regla móvil existente de -0.5 rem. Se mantiene H1–H6 en :not(:has()).
- Los banners H3/H4 nuevos se ubican después de los antiguos para conservar
  la prioridad del RAW al incorporarlos al archivo.

Observaciones para Iván / validación visual pendiente:
- La paleta base del POST ahora coincide con el primario #136d52 del PRE;
  se incorporan alternativas HEX/RGBA y color-mix antes de oklch relativo.
- El RAW vuelve a fijar posiciones/alturas y ancho de cajones (285 px),
  incluyendo top de 62/100/110 px. Comprobar con breadcrumb, uno/ambos
  cajones abiertos, cursos Tiles y anchos de tablet.
- Los bloques móviles usan hasta 600 px y los de escritorio desde 768 px:
  revisar también el intervalo 601–767 px.
- `.container, .card { container-type: inline-size; }` tiene alcance global:
  revisar las cuadrículas y el contenido de tarjetas de todo el campus.
- No se modifica el Raw SCSS guardado en Moodle ni se instala el paquete
  en testing. Si ese Raw contiene los mismos ajustes migrados, conviene
  revisarlo después de instalar para evitar mantener dos fuentes de estilos.

Validación: cinco escenarios con el compilador PHP scssphp de Moodle 5.3
(default, plain, preset subido, fallback y configurado), marcadores Raw
inicial/final, selectores anidados balanceados y variables Sass resueltas.
La validación visual y funcional del campus sigue pendiente.
