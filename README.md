# Proyecto LAL-D

Repositorio del proyecto para la Associació LAL-D (DAW, Institut Thos i Codina).
Dos partes: la mejora del WordPress público (plugin del mapa) y la intranet privada en Laravel.

Contexto, requisitos y estado: ver `CLAUDE.md` y `docs/ROADMAP.md`.

## Estructura

```
lal-d/
├── CLAUDE.md               Contexto del proyecto (se carga en cada sesión de Claude Code).
├── docs/                   Documentación (ROADMAP con requisitos y estado).
├── wordpress/
│   └── plugin-mapa/        Código del plugin del mapa de casos.
└── intranet/               Aplicación Laravel (pendiente de crear).
```

El **núcleo de WordPress no se versiona** aquí: solo nuestro plugin.

## Poner en marcha el entorno

1. **WordPress completo (archivos + base de datos):** se comparte aparte, una sola vez,
   con un paquete de **All-in-One WP Migration** (`.wpress`). Al importarlo se obtiene el
   sitio idéntico, con contenido y datos del mapa. No está en el repo por tamaño.
2. **Conectar el plugin al WordPress:** el plugin vive en `wordpress/plugin-mapa/`. Para que
   WordPress lo ejecute, en `wp-content/plugins/` hay que apuntar a él. Dos formas:
   - Enlace simbólico (recomendado): `ln -s .../lal-d/wordpress/plugin-mapa .../wp-content/plugins/mapa-casos`
   - O copiar la carpeta a `wp-content/plugins/mapa-casos` (hay que recopiar al cambiar código).
3. **Intranet Laravel:** irá en `intranet/`. Instrucciones cuando exista.

## Entorno de desarrollo

Fedora + XAMPP (Apache, PHP, MariaDB). En local, `http://localhost/LAL-D/wordpress/`.
