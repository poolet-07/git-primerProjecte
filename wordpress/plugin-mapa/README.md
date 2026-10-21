# Mapa de casos LAL-D

Plugin de WordPress para la Associació LAL-D (deficiencia de lipasa ácida
lisosomal). Muestra el número de casos conocidos de LAL-D mediante:

- Un **mapa interactivo de España** por comunidad autónoma.
- Un **mapa interactivo del mundo** por país.
- Una **tabla de prevalencia** estimada por comunidad, calculada a partir de la
  población y de los casos diagnosticados.

Los datos no provienen de ninguna fuente pública (LAL-D no está en el ReeR y el
registro internacional es privado): los introduce a mano la asociación desde el
panel de administración.

## Uso

### Panel de administración

Menú **Casos LAL-D**, con dos apartados aislados entre sí:

- **España**: un campo numérico por cada una de las 17 comunidades autónomas.
- **Mundo**: todos los países con un buscador para localizarlos; solo se guardan
  los países con casos (> 0).

Solo se editan números; no se pueden añadir, borrar ni renombrar regiones.

### Shortcodes (web pública)

| Shortcode            | Qué inserta                                             |
| -------------------- | ------------------------------------------------------- |
| `[mapa_casos]`       | Mapa interactivo de España.                             |
| `[mapa_casos_mundo]` | Mapa interactivo del mundo.                             |
| `[tabla_casos]`      | Tabla de prevalencia por comunidad (calculada en vivo). |

Al pulsar (ratón o teclado) una comunidad o país, se muestra esa región
ampliada y, debajo, el contador de casos.

## Estructura

```
mapa-casos/
├── mapa-casos.php   Punto de entrada: admin, shortcodes, carga de datos.
├── paises.php       Lista código ISO => nombre (generada desde @svg-maps/world).
├── spain.svg        Mapa de España (17 comunidades).
├── world.svg        Mapa del mundo (256 países/territorios).
├── mapa-casos.css   Estilos de los mapas y de la tabla.
└── mapa-casos.js    Interacción de los mapas (clic/teclado + panel de detalle).
```

## Almacenamiento

Los datos se guardan en la tabla de opciones de WordPress:

- `mapa_casos_datos`: casos por comunidad (`id => número`).
- `mapa_casos_datos_mundo`: casos por país (`código ISO => número`).

## Atribución (CC BY 4.0)

Los mapas SVG se han construido a partir de los paquetes de **svg-maps**, que
se basan en **MapSVG** y se distribuyen bajo licencia
[Creative Commons Attribution 4.0](https://creativecommons.org/licenses/by/4.0/):

- [@svg-maps/spain](https://github.com/VictorCazanave/svg-maps) — CC BY 4.0
- [@svg-maps/world](https://github.com/VictorCazanave/svg-maps) — CC BY 4.0

La atribución aparece también visible bajo cada mapa en la web.

## Licencia del código

GPL-2.0-or-later, como WordPress.
