# Icono Virtual - Landing Page

## 🚀 Descripción

Landing page de alto impacto con estética Dark Mode Antigravity para Icono Virtual. Diseñada para captar leads y mostrar servicios de IA de manera profesional y futurista.

## 📁 Estructura del Proyecto

```
iconovirtual/
├── index.php              # Página principal con integración PHP
├── process.php            # Procesamiento de formularios
├── css/
│   └── style.css         # Estilos externos (Dark Mode Antigravity)
├── js/
│   └── main.js           # JavaScript para animaciones e interacciones
└── data/
    ├── content.json      # Contenido dinámico (Hero, Servicios, Formulario)
    ├── tracking.json     # Configuración de códigos de tracking
    └── leads.json        # Almacenamiento de leads (generado automáticamente)
```

## ⚙️ Configuración

### 1. Contenido del Sitio

Edita `data/content.json` para modificar:
- Título y subtítulo del Hero
- Servicios (título, descripción, iconos)
- Textos del formulario
- Número de WhatsApp y mensaje predeterminado

### 2. Códigos de Tracking

Edita `data/tracking.json` para configurar:

**Google Analytics:**
```json
"google_analytics": {
  "enabled": true,
  "tracking_id": "G-XXXXXXXXXX"
}
```

**Google Tag Manager:**
```json
"google_tag_manager": {
  "enabled": true,
  "container_id": "GTM-XXXXXXX"
}
```

**Meta Pixel:**
```json
"meta_pixel": {
  "enabled": true,
  "pixel_id": "1234567890"
}
```

### 3. Configuración de Email (Opcional)

En `process.php`, línea 45, cambia `$sendEmail = false;` a `$sendEmail = true;` y configura tu email:

```php
$to = 'info@iconovirtual.com'; // Tu email aquí
```

## 🎨 Características de Diseño

- **Dark Mode Antigravity:** Fondo azul profundo con gradientes morados
- **Glassmorphism:** Tarjetas con efecto de cristal translúcido
- **Animaciones Flotantes:** Elementos con efecto de levitación
- **Partículas Dinámicas:** Sistema de partículas de luz
- **Responsive Design:** Optimizado para todos los dispositivos
- **Neon Effects:** Efectos de brillo (glow) en elementos clave

## 🔧 Requisitos

- PHP 7.0 o superior
- Servidor web (Apache/Nginx)
- Permisos de escritura en la carpeta `data/`

## 🚀 Instalación

1. Copia todos los archivos a tu directorio web (htdocs en XAMPP)
2. Asegúrate de que la carpeta `data/` tenga permisos de escritura
3. Configura `data/tracking.json` con tus códigos de tracking
4. Personaliza `data/content.json` según tus necesidades
5. Accede a `http://localhost/iconovirtual/` en tu navegador

## 📊 Gestión de Leads

Los leads se almacenan automáticamente en `data/leads.json` con:
- Timestamp
- Nombre
- Email
- Servicio de interés
- IP del visitante
- User Agent

## 🎯 Eventos de Tracking

El formulario envía automáticamente eventos a:
- **Meta Pixel:** Evento `Lead`
- **Google Analytics:** Evento `generate_lead`

## 📱 WhatsApp

El botón flotante de WhatsApp se configura en `data/content.json`:
- `number`: Número en formato internacional (sin +)
- `message`: Mensaje predeterminado

## 🎨 Personalización de Estilos

Todas las variables CSS están en `css/style.css` bajo `:root`:
- Colores
- Gradientes
- Espaciado
- Tipografía
- Efectos

## 📝 Notas

- El sitio está completamente en español
- Todos los estilos son externos (no inline)
- El contenido es dinámico vía JSON
- Los tracking codes se cargan condicionalmente
- Sistema de notificaciones integrado en JavaScript

## 🔒 Seguridad

- Validación de datos en cliente y servidor
- Sanitización de inputs
- Protección contra XSS
- Validación de email con regex

## 📞 Soporte

Para modificar el diseño, edita `css/style.css`
Para cambiar funcionalidad, edita `js/main.js`
Para ajustar el procesamiento de formularios, edita `process.php`
