# Contexto de destino: proyecto Intranet LAL-D

Tú conoces el estado actual del proyecto: el código, lo que ya está hecho y desde dónde empezamos. Lo que te falta es **a dónde tenemos que llegar y en qué orden**. Este documento te da eso: los requisitos completos del enunciado, el calendario, las decisiones técnicas ya tomadas y lo que queda fuera del alcance.

**Tu primera tarea no es programar.** Revisa el repositorio (y el WordPress local, si tienes acceso) y compara lo que existe con este documento. Entrégame un informe de situación:

1. Qué requisitos de la lista de abajo están hechos, cuáles a medias y cuáles sin empezar.
2. Qué decisiones técnicas de este documento contradicen algo que ya esté construido. Dímelo; no lo cambies sin preguntar.
3. Cuál es la siguiente tarea concreta según el plan, y qué necesitas para empezarla.

Guarda este documento en el repo como `docs/ROADMAP.md` para tenerlo como referencia permanente, y actualiza el estado de cada requisito a medida que avancemos.

---

## 1. El proyecto en una línea

Proyecto de clase (DAW, Institut Thos i Codina) para la Associació LAL-D, una asociación de pacientes de una enfermedad rara (deficiencia de lipasa ácida lisosomal). Tiene dos fases:

- **Fase 1:** mejorar su web pública en WordPress (SEO + mapa interactivo de casos + enlace a la intranet). No se sustituye la web.
- **Fase 2:** una intranet privada nueva e independiente en **Laravel + Eloquent + MySQL + Blade + HTML/CSS/JS**.

Somos **2 personas**. El WordPress se trabaja en local: `http://localhost/LAL-D/wordpress/`.

## 2. Calendario y situación

| Sprint | Fecha de entrega |
|---|---|
| 1 | 19/10/2026 (no entregamos nada; solo empezamos el mapa) |
| 2 | 02/11/2026 |
| 3 | 16/11/2026 |
| 4 | 30/11/2026 |
| 5 | 14/12/2026 |
| **Final** | **17/12/2026**: todo terminado y presentado |

- El sprint 1 se perdió, así que el sprint 2 tiene que absorber su trabajo.
- El 28/10 hay un examen de otra asignatura. Esa semana hay menos capacidad.
- Del 15 al 17/12 es margen para retrasos. No se planifica trabajo nuevo ahí.

## 3. Requisitos completos

### Fase 1: WordPress

**F1.1 SEO.** Analizar la web actual: estructura de contenidos, títulos, meta descripciones, jerarquía de encabezados, URLs, enlazado interno, alt de imágenes, estructura semántica, indexación, sitemap, rendimiento, adaptación a móviles y accesibilidad. Aplicar las mejoras que sean viables dentro de la estructura actual de WordPress.
- Se documenta en cinco partes: **situación inicial, problemas detectados, propuestas de mejora, actuaciones hechas y resultados obtenidos.**
- Antes de cambiar nada, hay que guardar una medición base (Lighthouse/PageSpeed y capturas). Sin ella no se pueden demostrar los resultados.

**F1.2 Mapa interactivo.** Un mapa mundial por países y un mapa de España por CCAA, con el número de casos de cada zona.
- Solo datos agregados. Nunca nombres, ubicaciones individuales, direcciones, contactos ni información clínica.
- Fácil de interpretar y funcional en ordenador, tablet y móvil. En móvil no existe el hover: el dato tiene que aparecer al tocar la región.
- Referencia: ahora la asociación hace a mano una imagen del mapa de España con el número de casos por CCAA y el total, más una tabla con población, casos diagnosticados y "posibles casos" estimados con una prevalencia de 1:40.000 y de 1:300.000. **Opcional, si hay tiempo:** guardar la población de cada CCAA y calcular la estimación automáticamente, para que la asociación solo introduzca los diagnosticados.

**F1.3 Actualización de los mapas sin tocar código.** Una persona autorizada tiene que poder seleccionar un país o CCAA, introducir o modificar el número de casos, guardarlo y ver el mapa actualizado.

**F1.4 Acceso visible a la intranet** desde la web pública. Es solo un enlace: no se integran los usuarios de WordPress con los de Laravel.

### Fase 2: Intranet Laravel

**F2.1 Roles:** administrador, metge (profesional sanitario) y família.

**F2.2 Administrador:** crea, modifica, activa y desactiva usuarios; gestiona famílies, metges, la pertenencia al Comitè d'Experts, presentacions mèdiques, publicacions científiques, trobades de famílies, documentos, fotografías y sol·licituds. **Es el único que puede activar o desactivar la pertenencia de un metge al Comitè.**

**F2.3 Metge.** Campos mínimos: nombre, email, contraseña, hospital, teléfono y pertenencia al Comitè (el metge no puede modificar este campo). El teléfono es solo un dato de contacto.

**F2.4 Comitè d'Experts.** Los miembros acceden a la documentación reservada. Los no miembros ven que el apartado existe, pero al entrar reciben un mensaje que dice que está reservado a miembros y les invita a formar parte.

**F2.5 Família.** Una cuenta por familia, no por paciente (si el paciente es menor, la usan los padres o tutores). Campos: nombre del paciente, fecha de nacimiento del paciente, email, teléfono, población, contraseña, consentimiento para aparecer en fotografías, consentimiento para recibir comunicaciones por WhatsApp y consentimiento para el tratamiento de datos.

**F2.6 Presentacions mèdiques.** Fecha, título, ponente principal, documento de la presentación (normalmente PDF), fotografías, acta y orden del día.
- Al subir un documento, el admin elige su tipo con un **desplegable**.
- Solo los miembros del Comitè pueden consultar y descargar esta documentación.

**F2.7 Publicacions científiques.** Año, investigador principal, título, URL y revista.
- Los metges las consultan y filtran.
- El admin selecciona una o varias publicaciones y uno o varios metges, y les envía un **email**.

**F2.8 Trobades de famílies.** Fecha, ciudad, descripción, PDF de la presentación y fotografías. El admin crea, modifica y gestiona los registros y sus documentos.

**F2.9 Sol·licituds de serveis.** La família crea una solicitud de suport psicològic, suport social o assessorament clínic, con un comentario. Se guarda en la base de datos.
- Estados: **Pendent / Tramitada al professional / Tancada**. El admin consulta y cambia el estado.
- La família consulta sus propias solicitudes y su estado.

**F2.10 Fotografías.** El admin sube fotos asociadas a presentacions y trobades, y puede consultar el consentimiento de las familias. No hace falta comprobarlo automáticamente: el admin consulta y decide.

**F2.11 Recuperación de contraseña por email**, segura y sin revelar la contraseña anterior.

### Transversales

- **JavaScript:** captura de eventos, validación de formularios, interacción con la interfaz, filtros, confirmación de acciones y actualización dinámica cuando haga falta.
- **Figma antes de implementar:** pantallas principales de admin, metge, família y del mapa. Requisitos: responsive, coherencia visual, usabilidad, accesibilidad, navegación clara, mensajes de error y de confirmación, y diferencia clara entre lo que puede hacer cada rol.
- **Backend:** base de datos creada con **migraciones**; consultas con **Eloquent**; solo **datos ficticios** durante el desarrollo.
- **Validación** en cliente (JS) y en servidor (Laravel). **Hashing seguro** de contraseñas.
- **RGPD:** analizar, junto con el ciclo de Administració, la recogida de datos, los consentimientos, el tratamiento, el almacenamiento, el acceso, la conservación, la seguridad y los derechos de los usuarios. Documentar los requisitos identificados, las medidas a tener en cuenta y las medidas técnicas aplicadas. **Nunca datos reales de pacientes.**
- **Proceso:** Git + GitHub, Trello, tests, refactorización, documentación de decisiones y presentación final.

## 4. Fuera del alcance: no construir

Lo dice el enunciado. Si algo de esto aparece, es trabajo perdido:
- Notificaciones por teléfono o WhatsApp a los metges.
- Notificaciones internas de publicaciones.
- Asignar una sol·licitud a un profesional desde la intranet. Se hace por teléfono o email; los profesionales no tienen cuenta.
- Notificaciones automáticas a la família cuando cambia el estado de una solicitud.
- Comprobación automática del consentimiento antes de subir una foto.
- Integración de usuarios entre WordPress y Laravel.

## 5. Decisiones técnicas

Son propuestas de diseño, no requisitos del enunciado. Si algo de lo ya construido las contradice, avísame antes de cambiarlo.

### WordPress (mapa)
- **Plugin propio**, nunca editando el tema instalado: una actualización del tema borraría los cambios.
- Regiones identificadas por códigos **ISO**: 3166-1 para países y 3166-2 para CCAA (p. ej. `ES-CT`). Los mismos códigos en el SVG, en la base de datos y en el formulario.
- Datos guardados en la base de datos de WordPress (opción o tabla propia), **no** en un JSON editado a mano: un error de sintaxis rompería el mapa.
- Página de administración propia (`add_menu_page`) con un formulario: selector de región, número de casos y guardar. Con nonce, comprobación de capability y saneamiento de la entrada.
- El mapa se inserta con un **shortcode**. Los datos llegan al JS desde PHP (`wp_localize_script` o JSON inline).
- Cubrir **los dos mapas**: mundial y España por CCAA.

### Laravel (intranet)
- **Laravel Breeze** (stack Blade) para el login, el registro gestionado por el admin y la recuperación de contraseña. **Mailtrap** para los emails en desarrollo.
- **Una sola tabla `users`** con `role` (admin/metge/familia) y `active`, más tablas de perfil `doctors` (hospital, teléfono, `is_committee_member`) y `families` (datos del paciente y los tres consentimientos). No tres tablas de login separadas.
- Control de acceso con **middleware de rol + policies**. El Comitè se comprueba con una policy, no escondiendo enlaces.
- **Documentos y fotos polimórficos** (`morphMany`): una tabla `documents` con un campo `type` (presentació, acta, ordre del dia, altre) y una tabla `photos`, ambas compartidas por presentacions y trobades. Se construye una vez y se usa en los dos módulos.
- **Archivos en el disco privado** (`local`), nunca en `public`. Se descargan a través de un controlador que comprueba permisos. Si van a `public`, cualquiera con la URL los descarga.
- Validación en servidor con **FormRequests**.
- Email de publicaciones con una clase **Mailable**.
- **Seeders + factories** con datos ficticios para todos los roles y módulos.
- **Feature tests de cada módulo en su propio sprint**, no todos al final.

## 6. Plan de sprints (2 personas)

Reparto por bloques para no pisarnos. **A** = WordPress y después módulos de contenido. **B** = base de datos, autenticación y usuarios. Ajustadlo si hace falta.

**Sprint 2 → 02/11** (recupera el sprint 1; semana de examen)
- A: terminar el mapa (mundial + España, panel de edición, prueba en móvil). Medición SEO base y documento de "situació inicial".
- B: modelo ER cerrado, proyecto Laravel con MySQL, migraciones, modelos, relaciones, seeders.
- Ambos: enviar las preguntas de RGPD al ciclo de Administració esta semana. Figma: pantallas del mapa y del admin.

**Sprint 3 → 16/11**
- A: aplicar las mejoras SEO, enlace a la intranet en WordPress, documentar las actuaciones. Figma: pantallas de metge y família.
- B: Breeze, middleware de roles, layout Blade por rol, CRUD de usuarios (metges con el interruptor del Comitè, famílies con los consentimientos), activar/desactivar, recuperación de contraseña, sol·licituds de serveis. Tests de todo ello.

**Sprint 4 → 30/11**
- A: presentacions mèdiques, trobades de famílies, consulta del consentimiento al subir fotos.
- B: sistema polimórfico de documentos y fotos con almacenamiento privado (antes que los módulos de A, o en paralelo al principio del sprint), acceso al Comitè con el mensaje de invitación, publicacions con filtros y envío por email.
- Tests de cada módulo.

**Sprint 5 → 14/12**
- JavaScript (validaciones, confirmaciones, filtros dinámicos), revisión de responsive y accesibilidad, refactorización, tests pendientes.
- Documentación: resultados SEO (medición final contra la base), RGPD (requisitos y medidas aplicadas) y decisiones técnicas. Preparar la presentación.

**15–17/12:** solo margen.

## 7. Pendiente de confirmar

- Cómo pasan los cambios del WordPress local a la web real de la asociación, y si hace falta una copia real del sitio (archivos + base de datos) en lugar de una instalación vacía.
- Dónde se despliega la intranet para la presentación.
- Las respuestas del ciclo de Administració sobre RGPD.

## 8. Cómo trabajar conmigo

Estoy aprendiendo desarrollo web, y este proyecto es para aprender, no solo para entregar.
- Explica el porqué de cada decisión, partiendo del problema que resuelve, no del nombre de la técnica.
- Ve por partes: un concepto nuevo cada vez. No me entregues un módulo entero de golpe.
- Si estoy haciendo algo de una forma que obligará a rehacerlo después (estructura, arquitectura), avísame en el momento.
- Si una tarea no está en este documento o choca con la sección 4, dímelo antes de hacerla.

---

## Estado de los requisitos (se actualiza a medida que avanzamos)

> Leyenda: ✅ hecho · 🟡 a medias · ⬜ sin empezar

### Fase 1 — WordPress
- 🟡 **F1.1 SEO** — sin empezar (falta medición base).
- 🟡 **F1.2 Mapa interactivo** — mapas de España y mundo funcionando, con tabla de prevalencia (opcional) calculada. Pendiente: prueba real en móvil/tablet; decisión sobre códigos ISO (ver más abajo).
- ✅ **F1.3 Actualizar mapas sin código** — panel de administración España/Mundo con nonce, capability y saneamiento.
- ⬜ **F1.4 Enlace a la intranet** — pendiente (la intranet aún no existe).

### Fase 2 — Laravel
- ⬜ **F2.1–F2.11** — sin empezar (el proyecto Laravel aún no existe).

### Transversales
- 🟡 JavaScript (hecho lo del mapa) · ⬜ Figma · ⬜ Migraciones/Eloquent · ⬜ Validación cliente+servidor · ⬜ RGPD · 🟡 Git (repo local del plugin, sin GitHub) · ⬜ Trello/tests.

### Decisiones sobre puntos que estaban abiertos (ver CLAUDE.md)
- **Códigos ISO:** DECIDIDO — no se remapea España a ISO 3166-2. Mundo usa ISO 3166-1 alfa-2; España usa los ids del SVG (`catalonia`, `andalusia`…). El objetivo (mismo id en SVG, BD y formulario) ya se cumple. El panel debe mostrar nombres, nunca el id.
- **Repositorio:** DECIDIDO — un único repo `lal-d/` con `wordpress/plugin-mapa/`, `intranet/` y `docs/`; no se versiona el núcleo de WordPress; subir a GitHub con el compañero como colaborador. PENDIENTE: el repo actual está dentro del plugin y hay que reubicarlo a esa estructura.

### Desviaciones menores pendientes
- El panel de administración del mundo y el overlay público muestran los nombres de país en **inglés** (vienen del paquete SVG). La decisión pide nombres en castellano/catalán. Corregir cuando toque.
