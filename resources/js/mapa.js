import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Necesario para que Vite cargue bien los iconos del marcador en producción
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow
});

document.addEventListener("DOMContentLoaded", function () {
    const mapaContenedor = document.getElementById("contenedor-mapa");

    if (!mapaContenedor) {
        return;
    }

    // Coordenadas y datos del cine
    const latitud = 36.59253131523141;
    const longitud = -6.221183282321667;
    const nombre = "Cinemax";
    const direccion = "Calle Pg Industrial Guadalete, 17, Loc Cines, 11500, Cádiz";
    const imagenMapa = window.imagenMapa || '/images/mapa_img.avif';
    const urlComoLlegar = `https://www.google.com/maps/dir/?api=1&destination=${latitud},${longitud}`;

    // Inicializamos el mapa centrado en el cine
    const mapa = L.map('contenedor-mapa', {
        zoomControl: true,
        scrollWheelZoom: true
    }).setView([latitud, longitud], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mapa);

    const marker = L.marker([latitud, longitud]).addTo(mapa);

    // HTML de la tarjeta que sale al pinchar en el marcador
    const contenidoPopup = `
        <div style="text-align:center; font-family:sans-serif; padding:5px; width:280px;">
            <h4 style="margin:0 0 8px; font-size:17px; font-weight:bold; color:#dc2626;">
                ${nombre}
            </h4>
            <p style="margin:0 0 10px; font-size:13px; color:#64748b;">
                ${direccion}
            </p>
            ${imagenMapa ? `
                <img
                    src="${imagenMapa}"
                    alt="Imagen de Cinemax"
                    style="width:100%; height:150px; object-fit:cover; border-radius:8px; margin-bottom:10px;"
                    onerror="this.style.display='none'"
                >
            ` : ''}
            <button
                type="button"
                id="btn-popup-llegar"
                style="cursor:pointer; border:none; padding:10px 15px; background-color:#dc2626; color:white; border-radius:6px; font-weight:bold; width:100%;">
                Cómo llegar
            </button>
        </div>
    `;

    marker.bindPopup(contenidoPopup, {
        maxWidth: 320,
        minWidth: 280,
        autoPan: true
    });

    // Enganchamos el clic del botón "Cómo llegar" una vez que el popup existe en el DOM
    marker.on('popupopen', function (evento) {
        const popupElemento = evento.popup.getElement();
        if (!popupElemento) return;

        const botonLlegar = popupElemento.querySelector('#btn-popup-llegar');
        if (botonLlegar) {
            botonLlegar.onclick = function (e) {
                e.preventDefault();
                e.stopPropagation();
                window.open(urlComoLlegar, '_blank', 'noopener,noreferrer');
            };
        }
    });

    // Abrimos la tarjeta automáticamente y reajustamos el tamaño del mapa por si estaba oculto al cargar
    setTimeout(() => mapa.invalidateSize(), 300);
    setTimeout(() => marker.openPopup(), 500);

    window.addEventListener('resize', () => mapa.invalidateSize());
});
