( function () {
    'use strict';

    var NS = 'http://www.w3.org/2000/svg';

    // Cada mapa elige su conjunto de datos según data-tipo.
    function datosDe( contenedor ) {
        if ( contenedor.getAttribute( 'data-tipo' ) === 'mundo' ) {
            return window.mapaCasosDataMundo || {};
        }
        return window.mapaCasosData || {};
    }

    // Número de casos -> texto legible, con singular/plural.
    function textoCasos( casos ) {
        if ( casos === 0 ) { return 'Sin casos registrados'; }
        if ( casos === 1 ) { return '1 caso'; }
        return casos + ' casos';
    }

    function iniciar( contenedor ) {
        var svg      = contenedor.querySelector( '.mapa-casos__mapa svg' );
        var overlay  = contenedor.querySelector( '.mapa-casos__overlay' );
        var figura   = contenedor.querySelector( '.mapa-casos__figura' );
        var nombreEl = contenedor.querySelector( '.mapa-casos__nombre' );
        var contador = contenedor.querySelector( '.mapa-casos__contador' );
        var cerrar   = contenedor.querySelector( '.mapa-casos__cerrar' );
        var backdrop = contenedor.querySelector( '.mapa-casos__backdrop' );
        if ( ! svg || ! overlay || ! figura || ! nombreEl || ! contador ) {
            return;
        }

        var datos = datosDe( contenedor );

        // Sacamos el overlay al <body> para que quede por encima de todo
        // (si se queda dentro de una sección de Divi, otras secciones como la
        // tabla pueden pintarse por delante de él).
        document.body.appendChild( overlay );

        /* ---------- Overlay: región ampliada y centrada en pantalla ---------- */

        function dibujarFigura( path ) {
            var bbox = path.getBBox();
            var margen = Math.max( bbox.width, bbox.height ) * 0.08;
            var vb = ( bbox.x - margen ) + ' ' + ( bbox.y - margen ) + ' ' +
                     ( bbox.width + margen * 2 ) + ' ' + ( bbox.height + margen * 2 );

            var mini = document.createElementNS( NS, 'svg' );
            mini.setAttribute( 'viewBox', vb );
            mini.setAttribute( 'class', 'mapa-casos__figura-svg' );

            var clon = path.cloneNode( true );
            [ 'id', 'class', 'tabindex', 'role', 'aria-label' ].forEach( function ( a ) {
                clon.removeAttribute( a );
            } );
            mini.appendChild( clon );

            figura.innerHTML = '';
            figura.appendChild( mini );
        }

        function abrir( path, nombre, casos ) {
            dibujarFigura( path );
            nombreEl.textContent = nombre;
            contador.textContent = textoCasos( casos );
            contador.classList.toggle( 'mapa-casos__contador--cero', casos === 0 );

            overlay.hidden = false;
            // Forzar un reflow para que la transición se vea al añadir la clase.
            void overlay.offsetWidth;
            overlay.classList.add( 'is-visible' );
        }

        function ocultar() {
            overlay.classList.remove( 'is-visible' );
            // Esperar a que acabe la animación antes de ocultarlo del todo.
            setTimeout( function () { overlay.hidden = true; }, 280 );
        }

        if ( cerrar ) { cerrar.addEventListener( 'click', ocultar ); }
        if ( backdrop ) { backdrop.addEventListener( 'click', ocultar ); }
        document.addEventListener( 'keydown', function ( e ) {
            if ( e.key === 'Escape' && ! overlay.hidden ) { ocultar(); }
        } );

        /* ---------- Regiones del mapa ---------- */

        svg.querySelectorAll( 'path' ).forEach( function ( path ) {
            var id     = path.getAttribute( 'id' );
            var d      = datos[ id ];
            var nombre = d ? d.nombre : id;
            var casos  = d ? d.casos : 0;

            path.setAttribute( 'role', 'button' );
            path.setAttribute( 'tabindex', '0' );
            path.setAttribute( 'aria-label', nombre );
            if ( casos > 0 ) { path.classList.add( 'tiene-casos' ); }

            function seleccionar() {
                // Si se venía de arrastrar el mapa, no abrir (es un paneo).
                if ( contenedor._arrastrado ) { return; }
                var previa = svg.querySelector( 'path.seleccionada' );
                if ( previa ) { previa.classList.remove( 'seleccionada' ); }
                path.classList.add( 'seleccionada' );
                abrir( path, nombre, casos );
            }

            path.addEventListener( 'click', seleccionar );
            path.addEventListener( 'keydown', function ( e ) {
                if ( e.key === 'Enter' || e.key === ' ' ) {
                    e.preventDefault();
                    seleccionar();
                }
            } );
        } );

        /* ---------- Zoom y arrastre (solo mapa del mundo) ---------- */

        if ( contenedor.getAttribute( 'data-tipo' ) === 'mundo' ) {
            configurarZoomPan( contenedor, svg );
        }
    }

    function configurarZoomPan( contenedor, svg ) {
        var base = ( svg.getAttribute( 'viewBox' ) || '0 0 1010 666' ).split( /\s+/ ).map( Number );
        var vista = { x: base[0], y: base[1], w: base[2], h: base[3] };
        var MIN_W = base[2] / 14; // zoom máximo

        function aplicar() {
            svg.setAttribute( 'viewBox', vista.x + ' ' + vista.y + ' ' + vista.w + ' ' + vista.h );
        }

        function limitar() {
            vista.w = Math.min( base[2], Math.max( MIN_W, vista.w ) );
            vista.h = vista.w * ( base[3] / base[2] );
            vista.x = Math.min( base[0] + base[2] - vista.w, Math.max( base[0], vista.x ) );
            vista.y = Math.min( base[1] + base[3] - vista.h, Math.max( base[1], vista.y ) );
        }

        // Acerca/aleja manteniendo fijo el punto (px, py) en proporción 0..1.
        function zoom( factor, px, py ) {
            var sx = vista.x + px * vista.w;
            var sy = vista.y + py * vista.h;
            vista.w = vista.w / factor;
            limitar();
            vista.x = sx - px * vista.w;
            vista.y = sy - py * vista.h;
            limitar();
            aplicar();
        }

        // Rueda del ratón: zoom centrado en el cursor.
        svg.addEventListener( 'wheel', function ( e ) {
            e.preventDefault();
            var r = svg.getBoundingClientRect();
            var px = ( e.clientX - r.left ) / r.width;
            var py = ( e.clientY - r.top ) / r.height;
            zoom( e.deltaY < 0 ? 1.2 : 1 / 1.2, px, py );
        }, { passive: false } );

        // Botones +/-/restablecer.
        var controles = contenedor.querySelector( '.mapa-casos__controles' );
        if ( controles ) {
            controles.addEventListener( 'click', function ( e ) {
                var boton = e.target.closest( '[data-zoom]' );
                if ( ! boton ) { return; }
                var accion = boton.getAttribute( 'data-zoom' );
                if ( accion === 'in' ) { zoom( 1.4, 0.5, 0.5 ); }
                else if ( accion === 'out' ) { zoom( 1 / 1.4, 0.5, 0.5 ); }
                else { vista = { x: base[0], y: base[1], w: base[2], h: base[3] }; aplicar(); }
            } );
        }

        // Arrastre para desplazarse. OJO: no capturamos el puntero en un clic
        // simple (eso redirigiría el click al svg y no se seleccionaría el
        // país); solo capturamos cuando de verdad hay arrastre.
        var inicio = null;   // posición del pointerdown
        var ultimo = null;   // última posición durante el arrastre
        var paneando = false;
        var capturado = false;

        svg.addEventListener( 'pointerdown', function ( e ) {
            if ( e.button && e.button !== 0 ) { return; }
            inicio = { x: e.clientX, y: e.clientY, id: e.pointerId };
            ultimo = { x: e.clientX, y: e.clientY };
            paneando = false;
            capturado = false;
            contenedor._arrastrado = false;
        } );

        svg.addEventListener( 'pointermove', function ( e ) {
            if ( ! inicio ) { return; }

            // ¿Se ha movido lo bastante para considerarlo arrastre?
            if ( ! paneando &&
                 Math.abs( e.clientX - inicio.x ) + Math.abs( e.clientY - inicio.y ) > 4 ) {
                paneando = true;
                contenedor._arrastrado = true;
                svg.classList.add( 'mapa-casos__mapa--arrastrando' );
                try { svg.setPointerCapture( inicio.id ); capturado = true; } catch ( err ) {}
            }
            if ( ! paneando ) { return; }

            var r = svg.getBoundingClientRect();
            vista.x -= ( e.clientX - ultimo.x ) * ( vista.w / r.width );
            vista.y -= ( e.clientY - ultimo.y ) * ( vista.h / r.height );
            limitar();
            aplicar();
            ultimo = { x: e.clientX, y: e.clientY };
        } );

        function finArrastre() {
            if ( ! inicio ) { return; }
            if ( capturado ) { try { svg.releasePointerCapture( inicio.id ); } catch ( err ) {} }
            inicio = null;
            if ( paneando ) {
                paneando = false;
                svg.classList.remove( 'mapa-casos__mapa--arrastrando' );
                // Dejar pasar el click que llega tras soltar y luego limpiar.
                setTimeout( function () { contenedor._arrastrado = false; }, 0 );
            } else {
                contenedor._arrastrado = false;
            }
        }
        svg.addEventListener( 'pointerup', finArrastre );
        svg.addEventListener( 'pointercancel', finArrastre );
    }

    document.addEventListener( 'DOMContentLoaded', function () {
        document.querySelectorAll( '.mapa-casos' ).forEach( iniciar );
    } );
} )();
