<?php
/**
 * Plugin Name: Mapa de casos LAL-D
 * Description: Mapas interactivos (España y mundo) con el número de casos, y tabla de prevalencia.
 * Version: 0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Lista de países (código ISO => nombre), generada desde @svg-maps/world.
require_once plugin_dir_path( __FILE__ ) . 'paises.php';

/* =========================================================================
 * 1. FUENTES DE DATOS (listas fijas, definidas en un solo sitio)
 * ========================================================================= */

// Las 17 comunidades. Clave = id (coincide con el <path> de spain.svg).
function mapa_casos_comunidades() {
    return array(
        'andalusia'         => 'Andalucía',
        'aragon'            => 'Aragón',
        'asturias'          => 'Asturias',
        'balearic-islands'  => 'Islas Baleares',
        'basque-country'    => 'País Vasco',
        'canary-islands'    => 'Canarias',
        'cantabria'         => 'Cantabria',
        'castile-and-leon'  => 'Castilla y León',
        'castile-la-mancha' => 'Castilla-La Mancha',
        'catalonia'         => 'Cataluña',
        'extremadura'       => 'Extremadura',
        'galicia'           => 'Galicia',
        'la-rioja'          => 'La Rioja',
        'madrid'            => 'Madrid',
        'murcia'            => 'Murcia',
        'navarre'           => 'Navarra',
        'valencia'          => 'Comunidad Valenciana',
    );
}

// Población por comunidad (dato fijo de referencia para la tabla).
// Incluye Ceuta y Melilla, que la tabla muestra aunque el mapa no las tenga.
// Ordenado de mayor a menor población, como en la tabla original.
function mapa_casos_poblacion() {
    return array(
        array( 'id' => 'andalusia',         'nombre' => 'Andalucía',            'poblacion' => 8713242 ),
        array( 'id' => 'catalonia',         'nombre' => 'Cataluña',             'poblacion' => 8186259 ),
        array( 'id' => 'madrid',            'nombre' => 'Madrid',               'poblacion' => 7167412 ),
        array( 'id' => 'valencia',          'nombre' => 'Comunidad Valenciana', 'poblacion' => 5491582 ),
        array( 'id' => 'galicia',           'nombre' => 'Galicia',              'poblacion' => 2726314 ),
        array( 'id' => 'castile-and-leon',  'nombre' => 'Castilla y León',      'poblacion' => 2412242 ),
        array( 'id' => 'canary-islands',    'nombre' => 'Canarias',             'poblacion' => 2268035 ),
        array( 'id' => 'basque-country',    'nombre' => 'País Vasco',           'poblacion' => 2248789 ),
        array( 'id' => 'castile-la-mancha', 'nombre' => 'Castilla-La Mancha',   'poblacion' => 2140796 ),
        array( 'id' => 'murcia',            'nombre' => 'Murcia',               'poblacion' => 1598436 ),
        array( 'id' => 'aragon',            'nombre' => 'Aragón',               'poblacion' => 1375040 ),
        array( 'id' => 'balearic-islands',  'nombre' => 'Islas Baleares',       'poblacion' => 1255061 ),
        array( 'id' => 'extremadura',       'nombre' => 'Extremadura',          'poblacion' => 1054677 ),
        array( 'id' => 'asturias',          'nombre' => 'Asturias',             'poblacion' => 1020009 ),
        array( 'id' => 'navarre',           'nombre' => 'Navarra',              'poblacion' => 688372 ),
        array( 'id' => 'cantabria',         'nombre' => 'Cantabria',            'poblacion' => 596189 ),
        array( 'id' => 'la-rioja',          'nombre' => 'La Rioja',             'poblacion' => 329522 ),
        array( 'id' => 'melilla',           'nombre' => 'Melilla',              'poblacion' => 87127 ),
        array( 'id' => 'ceuta',             'nombre' => 'Ceuta',                'poblacion' => 83740 ),
    );
}

/* =========================================================================
 * 2. ADMINISTRACIÓN: menú con dos apartados aislados (España y Mundo)
 * ========================================================================= */

add_action( 'admin_menu', 'mapa_casos_menu' );

function mapa_casos_menu() {
    // Menú principal.
    add_menu_page(
        'Casos LAL-D',
        'Casos LAL-D',
        'manage_options',
        'mapa-casos',
        'mapa_casos_pagina',
        'dashicons-location-alt'
    );
    // Submenú "España" (reutiliza el slug del menú principal).
    add_submenu_page( 'mapa-casos', 'España', 'España', 'manage_options', 'mapa-casos', 'mapa_casos_pagina' );
    // Submenú "Mundo" (apartado aislado, con su propio slug y almacenamiento).
    add_submenu_page( 'mapa-casos', 'Mundo', 'Mundo', 'manage_options', 'mapa-casos-mundo', 'mapa_casos_pagina_mundo' );
}

/* ---- Apartado NACIONAL (España) ---- */

function mapa_casos_pagina() {
    $guardado = false;
    if ( isset( $_POST['casos'] ) ) {
        $guardado = mapa_casos_guardar();
    }

    $comunidades = mapa_casos_comunidades();
    $datos       = get_option( 'mapa_casos_datos', array() );

    echo '<div class="wrap">';
    echo '<h1>Casos LAL-D — España</h1>';

    if ( $guardado ) {
        echo '<div class="notice notice-success"><p>Datos guardados.</p></div>';
    }

    echo '<p>Número de casos conocidos por comunidad autónoma.</p>';

    echo '<form method="post">';
    wp_nonce_field( 'mapa_casos_guardar' );
    echo '<table class="form-table">';

    foreach ( $comunidades as $id => $nombre ) {
        $valor = isset( $datos[ $id ] ) ? $datos[ $id ] : 0;
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $nombre ) . '</label></th>';
        echo '<td><input type="number" min="0" step="1" id="' . esc_attr( $id ) . '" name="casos[' . esc_attr( $id ) . ']" value="' . esc_attr( $valor ) . '"></td>';
        echo '</tr>';
    }

    echo '</table>';
    submit_button( 'Guardar' );
    echo '</form>';
    echo '</div>';
}

function mapa_casos_guardar() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return false;
    }
    check_admin_referer( 'mapa_casos_guardar' );

    $enviados = (array) $_POST['casos'];
    $limpios  = array();
    foreach ( mapa_casos_comunidades() as $id => $nombre ) {
        $valor = isset( $enviados[ $id ] ) ? absint( $enviados[ $id ] ) : 0;
        $limpios[ $id ] = $valor;
    }

    update_option( 'mapa_casos_datos', $limpios );
    return true;
}

/* ---- Apartado INTERNACIONAL (Mundo), aislado del nacional ---- */

function mapa_casos_pagina_mundo() {
    $guardado = false;
    if ( isset( $_POST['casos_mundo'] ) ) {
        $guardado = mapa_casos_guardar_mundo();
    }

    $paises = mapa_casos_paises();
    $datos  = get_option( 'mapa_casos_datos_mundo', array() );

    echo '<div class="wrap">';
    echo '<h1>Casos LAL-D — Mundo</h1>';

    if ( $guardado ) {
        echo '<div class="notice notice-success"><p>Datos guardados.</p></div>';
    }

    echo '<p>Casos conocidos por país. Escribe en el buscador para encontrar un país rápidamente.</p>';
    echo '<p><input type="search" id="mapa-casos-buscador" placeholder="Buscar país…" style="padding:6px;width:280px;max-width:100%;"></p>';

    echo '<form method="post">';
    wp_nonce_field( 'mapa_casos_guardar_mundo' );
    echo '<table class="form-table" id="mapa-casos-tabla-mundo">';

    foreach ( $paises as $code => $nombre ) {
        $valor = isset( $datos[ $code ] ) ? $datos[ $code ] : 0;
        echo '<tr data-nombre="' . esc_attr( strtolower( $nombre ) ) . '">';
        echo '<th scope="row"><label for="pais-' . esc_attr( $code ) . '">' . esc_html( $nombre ) . '</label></th>';
        echo '<td><input type="number" min="0" step="1" id="pais-' . esc_attr( $code ) . '" name="casos_mundo[' . esc_attr( $code ) . ']" value="' . esc_attr( $valor ) . '"></td>';
        echo '</tr>';
    }

    echo '</table>';
    submit_button( 'Guardar' );
    echo '</form>';
    echo '</div>';

    // Buscador: oculta las filas cuyo nombre de país no contiene lo escrito.
    echo '<script>
    ( function () {
        var input = document.getElementById( "mapa-casos-buscador" );
        var filas = document.querySelectorAll( "#mapa-casos-tabla-mundo tr" );
        input.addEventListener( "input", function () {
            var q = input.value.toLowerCase().trim();
            filas.forEach( function ( fila ) {
                var nombre = fila.getAttribute( "data-nombre" ) || "";
                fila.style.display = ( nombre.indexOf( q ) !== -1 ) ? "" : "none";
            } );
        } );
    } )();
    </script>';
}

function mapa_casos_guardar_mundo() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return false;
    }
    check_admin_referer( 'mapa_casos_guardar_mundo' );

    $enviados = (array) $_POST['casos_mundo'];
    $limpios  = array();
    foreach ( mapa_casos_paises() as $code => $nombre ) {
        $valor = isset( $enviados[ $code ] ) ? absint( $enviados[ $code ] ) : 0;
        // Guardamos solo los países con casos, para no llenar la base de datos
        // de ceros (hay 256 países).
        if ( $valor > 0 ) {
            $limpios[ $code ] = $valor;
        }
    }

    update_option( 'mapa_casos_datos_mundo', $limpios );
    return true;
}

/* =========================================================================
 * 3. SHORTCODES (web pública)
 * ========================================================================= */

// Construye el HTML de un mapa a partir de un SVG y sus datos.
function mapa_casos_render_mapa( $archivo_svg, $tipo, $texto, $fuente_nombre ) {
    $ruta = plugin_dir_path( __FILE__ ) . $archivo_svg;
    if ( ! file_exists( $ruta ) ) {
        return '<p>No se ha encontrado el mapa.</p>';
    }
    $svg = file_get_contents( $ruta );

    $es_mundo = ( 'mundo' === $tipo );

    $html  = '<div class="mapa-casos" data-tipo="' . esc_attr( $tipo ) . '">';

    // Mapa completo, donde se hace clic. En el mundo, con controles de zoom.
    $html .= '<div class="mapa-casos__mapa">';
    $html .= $svg;
    if ( $es_mundo ) {
        $html .= '<div class="mapa-casos__controles">';
        $html .= '<button type="button" class="mapa-casos__zoom" data-zoom="in" aria-label="Acercar">+</button>';
        $html .= '<button type="button" class="mapa-casos__zoom" data-zoom="out" aria-label="Alejar">&minus;</button>';
        $html .= '<button type="button" class="mapa-casos__zoom" data-zoom="reset" aria-label="Restablecer vista">&#8635;</button>';
        $html .= '</div>';
    }
    $html .= '</div>';

    // Texto de ayuda bajo el mapa.
    $ayuda = $es_mundo
        ? 'Acerca con la rueda o los botones, arrastra para moverte y haz clic en un país para ampliarlo.'
        : 'Haz clic en una comunidad para ampliarla y ver sus casos.';
    $html .= '<p class="mapa-casos__ayuda">' . esc_html( $ayuda ) . '</p>';

    $html .= '<p class="mapa-casos__credito">Mapa basado en ';
    $html .= '<a href="https://github.com/VictorCazanave/svg-maps" target="_blank" rel="noopener">' . esc_html( $fuente_nombre ) . '</a> (MapSVG), ';
    $html .= '<a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener">CC BY 4.0</a>.</p>';

    // Overlay: al seleccionar, la región ampliada se centra en la pantalla
    // sobre su fondo, que crece con ella, con el contador debajo.
    $html .= '<div class="mapa-casos__overlay" hidden>';
    $html .= '<div class="mapa-casos__backdrop"></div>';
    $html .= '<div class="mapa-casos__tarjeta" role="dialog" aria-modal="true" aria-live="polite">';
    $html .= '<button type="button" class="mapa-casos__cerrar" aria-label="Cerrar">&times;</button>';
    $html .= '<div class="mapa-casos__figura" aria-hidden="true"></div>';
    $html .= '<p class="mapa-casos__nombre"></p>';
    $html .= '<p class="mapa-casos__contador"></p>';
    $html .= '</div>';
    $html .= '</div>';

    $html .= '</div>'; // .mapa-casos
    return $html;
}

// [mapa_casos] -> mapa de España.
add_shortcode( 'mapa_casos', 'mapa_casos_shortcode' );
function mapa_casos_shortcode() {
    return mapa_casos_render_mapa( 'spain.svg', 'espana', 'Haz clic en una comunidad para ver sus casos.', '@svg-maps/spain' );
}

// [mapa_casos_mundo] -> mapa del mundo.
add_shortcode( 'mapa_casos_mundo', 'mapa_casos_shortcode_mundo' );
function mapa_casos_shortcode_mundo() {
    return mapa_casos_render_mapa( 'world.svg', 'mundo', 'Haz clic en un país para ver sus casos.', '@svg-maps/world' );
}

// [tabla_casos] -> tabla de prevalencia, calculada desde los datos del mapa.
add_shortcode( 'tabla_casos', 'mapa_casos_tabla_shortcode' );
function mapa_casos_tabla_shortcode() {
    $datos = get_option( 'mapa_casos_datos', array() );
    $filas = mapa_casos_poblacion();

    $total_pob = 0;
    $total_casos = 0;

    $html  = '<div class="tabla-casos-wrap">';
    $html .= '<table class="tabla-casos">';
    $html .= '<thead><tr>';
    $html .= '<th>Comunidad autónoma</th><th>Población 2026</th><th>Casos diagnosticados</th>';
    $html .= '<th>Prevalencia 1:40.000</th><th>Prevalencia 1:300.000</th>';
    $html .= '</tr></thead><tbody>';

    foreach ( $filas as $fila ) {
        $pob   = $fila['poblacion'];
        $casos = isset( $datos[ $fila['id'] ] ) ? (int) $datos[ $fila['id'] ] : 0;
        // Las dos columnas de prevalencia se calculan desde la población.
        $p40  = (int) round( $pob / 40000 );
        $p300 = (int) round( $pob / 300000 );

        $total_pob   += $pob;
        $total_casos += $casos;

        $html .= '<tr>';
        $html .= '<td>' . esc_html( $fila['nombre'] ) . '</td>';
        $html .= '<td>' . number_format_i18n( $pob ) . '</td>';
        $html .= '<td>' . (int) $casos . '</td>';
        $html .= '<td>' . number_format_i18n( $p40 ) . '</td>';
        $html .= '<td>' . number_format_i18n( $p300 ) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody><tfoot><tr>';
    $html .= '<td>TOTAL</td>';
    $html .= '<td>' . number_format_i18n( $total_pob ) . '</td>';
    $html .= '<td>' . (int) $total_casos . '</td>';
    $html .= '<td>' . number_format_i18n( (int) round( $total_pob / 40000 ) ) . '</td>';
    $html .= '<td>' . number_format_i18n( (int) round( $total_pob / 300000 ) ) . '</td>';
    $html .= '</tr></tfoot></table>';
    $html .= '</div>';

    return $html;
}

/* =========================================================================
 * 4. ASSETS (CSS/JS) solo en páginas que usan algún shortcode nuestro
 * ========================================================================= */

add_action( 'wp_enqueue_scripts', 'mapa_casos_assets' );

function mapa_casos_assets() {
    if ( ! is_singular() ) {
        return;
    }
    $post = get_post();
    if ( ! $post ) {
        return;
    }

    $tiene_espana = has_shortcode( $post->post_content, 'mapa_casos' );
    $tiene_mundo  = has_shortcode( $post->post_content, 'mapa_casos_mundo' );
    $tiene_tabla  = has_shortcode( $post->post_content, 'tabla_casos' );

    if ( ! $tiene_espana && ! $tiene_mundo && ! $tiene_tabla ) {
        return;
    }

    // El CSS se usa para los mapas y para la tabla.
    wp_enqueue_style( 'mapa-casos', plugins_url( 'mapa-casos.css', __FILE__ ), array(), '0.5' );

    // El JS solo hace falta si hay algún mapa (la tabla es solo HTML).
    if ( $tiene_espana || $tiene_mundo ) {
        wp_enqueue_script( 'mapa-casos', plugins_url( 'mapa-casos.js', __FILE__ ), array(), '0.5', true );
    }

    if ( $tiene_espana ) {
        $datos   = get_option( 'mapa_casos_datos', array() );
        $payload = array();
        foreach ( mapa_casos_comunidades() as $id => $nombre ) {
            $payload[ $id ] = array(
                'nombre' => $nombre,
                'casos'  => isset( $datos[ $id ] ) ? (int) $datos[ $id ] : 0,
            );
        }
        wp_localize_script( 'mapa-casos', 'mapaCasosData', $payload );
    }

    if ( $tiene_mundo ) {
        $datos   = get_option( 'mapa_casos_datos_mundo', array() );
        $payload = array();
        foreach ( mapa_casos_paises() as $code => $nombre ) {
            $payload[ $code ] = array(
                'nombre' => $nombre,
                'casos'  => isset( $datos[ $code ] ) ? (int) $datos[ $code ] : 0,
            );
        }
        wp_localize_script( 'mapa-casos', 'mapaCasosDataMundo', $payload );
    }
}
