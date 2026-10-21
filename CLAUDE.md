# CLAUDE.md: proyecto Intranet LAL-D

Este archivo se carga en cada sesión. Contiene el contexto que no está en el código.

Requisitos, calendario y estado de cada requisito: `docs/ROADMAP.md`. Léelo al empezar y mantenlo actualizado.
Lo de aquí debajo: historia del proyecto, decisiones ya tomadas y su motivo, y cómo trabajar conmigo.

## Historia del enunciado

Hubo dos enunciados. El primero proponía una web nueva con cuatro apartados (SEO, agenda, contacto con tres emails, financiación). El oficial lo sustituyó: se mantiene el WordPress y se hace una intranet Laravel aparte. La agenda, el contacto y la financiación ya no forman parte del proyecto. No los recuperes.

Ya entregamos una propuesta escrita en catalán basada en el enunciado oficial. En ella aparecían Leaflet, Mailtrap, Breeze, Google Search Console y PageSpeed como herramientas previstas. Leaflet se descartó después: el mapa se hizo con SVG propio, y está bien así.

Somos 2 personas. En el sprint 1 (19/10) no entregamos nada. Fecha final real: 17/12/2026.

## Decisiones tomadas (no reabrir sin motivo nuevo)

### Mapa (WordPress)

- Plugin propio con SVG, sin tocar el tema. Datos guardados en la base de datos de WordPress y editados desde un panel con nonce, capability y saneamiento. Nunca un JSON editado a mano, porque un error de sintaxis rompería el mapa entero.
- Identificadores: el mapa mundial usa ISO 3166-1 alfa-2. El de España usa los ids del SVG (`catalonia`, `andalusia`…). **No se remapea a ISO**: el objetivo era que SVG, base de datos y formulario usaran el mismo id, y eso ya se cumple. El panel debe mostrar los nombres en castellano/catalán, nunca el id.
- No existe ninguna fuente pública para actualizar los casos automáticamente:
  - El LAL Deficiency Registry (NCT01633489, Alexion) está cerrado y solo publica datos agregados en artículos.
  - El ReeR español no incluye LAL-D y no tiene API.
  - Los datos los introduce la asociación a mano. No vuelvas a investigarlo.
- Privacidad: solo cifras agregadas por zona. Decidimos no mostrar rangos (1–5, 6–10…); se muestra el número.
- La tabla de prevalencia (población × 1:40.000 y 1:300.000) es un extra opcional que ya está hecho.

### Repositorio

Un único repo `lal-d/` con `wordpress/plugin-mapa/`, `intranet/` (Laravel) y `docs/`. No se versiona el núcleo de WordPress. Hay que subirlo a GitHub con mi compañero como colaborador.

### Intranet (Laravel)

Lo detalla la sección 5 del ROADMAP. Resumen:

- Breeze (Blade) + Mailtrap.
- Una sola tabla `users` con `role` y `active`, más perfiles `doctors` y `families`.
- Middleware de rol + policies.
- Documentos y fotos polimórficos, compartidos por presentacions y trobades.
- Archivos en el disco privado, servidos por un controlador que comprueba permisos.
- FormRequests para validar.
- Tests de cada módulo en su propio sprint.
- Antes de escribir migraciones, propónme el modelo ER y espera a que lo valide.

## Prioridad actual

La fase 1 va adelantada y la fase 2 no existe. El cuello de botella es la intranet. No dediques más tiempo a pulir el mapa: pruébalo en móvil/tablet, haz la medición SEO base con Lighthouse antes de tocar nada más del WordPress, y pasa a Laravel.

## Pendiente de confirmar (con el profesor o la asociación)

- Si el WordPress local es una copia real del sitio o una instalación vacía. Si es vacía, la medición SEO no sirve y hay que pedir la copia (archivos + volcado de la base de datos, con Duplicator o All-in-One WP Migration).
- Cómo llegan los cambios del local a la web real.
- Dónde se despliega la intranet para la presentación.
- Las respuestas del ciclo de Administració sobre RGPD. Las preguntas hay que enviarlas ya.

## Entorno

- Fedora con Apache, PHP y MariaDB. Si algo falla con permisos de archivos, revisa SELinux antes que nada; en Fedora suele ser la causa.
- El escritorio de wp-admin en local tarda mucho en cargar o no termina nunca. Probablemente espera a servidores externos.

## Cómo trabajar conmigo

Estoy aprendiendo desarrollo web; el proyecto es para aprender, no solo para entregar.

- Sin relleno. Directo al contenido.
- Un concepto nuevo cada vez. Antes de explicar algo, cuenta cuántos conceptos nuevos incluye y dámelos por partes, uno a uno.
- Que reconozca una palabra no significa que entienda el mecanismo. Explica desde el problema que resuelve, no desde el nombre de la técnica.
- Yo marco el ritmo. Responde a lo que pregunto y para. No pases al siguiente paso hasta que te lo diga.
- Si mi respuesta es parcialmente correcta, guíame hacia lo que falta en vez de dármelo hecho.
- Avísame en el momento si estoy haciendo algo de una forma que obligará a rehacerlo después (estructura, arquitectura).
- No pospongas lo que se puede avanzar ahora, ni dejes explicaciones "para cuando llegue X".
- Si dejo algo a medias en cuanto aparece fricción, dímelo claramente en vez de darlo por bueno.
- Nada de halagos.
- Si una tarea no está en el ROADMAP o choca con su sección 4 ("fuera del alcance"), dímelo antes de hacerla.
