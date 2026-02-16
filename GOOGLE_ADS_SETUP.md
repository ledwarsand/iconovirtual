# Google Ads Tracking - Guía de Configuración

## 📋 Descripción

Este proyecto ahora incluye seguimiento completo de Google Ads con conversiones automáticas. El sistema está integrado con Google Analytics, Google Tag Manager y Meta Pixel.

## 🚀 Características

- ✅ Código de seguimiento global de Google Ads (gtag.js)
- ✅ Seguimiento automático de conversiones en formularios
- ✅ Configuración centralizada en `data/tracking.json`
- ✅ Compatible con Google Analytics y Google Tag Manager
- ✅ Seguimiento de eventos de leads

## 📝 Configuración

### Paso 1: Obtener tu ID de Conversión de Google Ads

1. Inicia sesión en tu cuenta de [Google Ads](https://ads.google.com)
2. Ve a **Herramientas y configuración** > **Medición** > **Conversiones**
3. Haz clic en **+ Nueva acción de conversión**
4. Selecciona **Sitio web**
5. Configura tu conversión (por ejemplo: "Envío de formulario de contacto")
6. En la página de configuración del código, encontrarás:
   - **ID de conversión**: Tiene el formato `AW-XXXXXXXXXX`
   - **Etiqueta de conversión**: Una cadena alfanumérica única

### Paso 2: Configurar tracking.json

Abre el archivo `data/tracking.json` y actualiza la sección de Google Ads:

```json
{
    "google_ads": {
        "enabled": true,
        "conversion_id": "AW-1234567890",
        "conversion_label": "AbCdEfGhIjKlMnOp"
    }
}
```

**Importante**: 
- Cambia `"enabled": false` a `"enabled": true`
- Reemplaza `AW-XXXXXXXXXX` con tu ID de conversión real
- Reemplaza `XXXXXXXXXXXX` con tu etiqueta de conversión real

### Paso 3: Verificar la Instalación

1. Abre tu sitio web en un navegador
2. Abre las **Herramientas de desarrollador** (F12)
3. Ve a la pestaña **Consola**
4. Envía el formulario de contacto
5. Deberías ver el mensaje: `Google Ads conversion tracked`

También puedes usar la extensión [Google Tag Assistant](https://chrome.google.com/webstore/detail/tag-assistant-legacy-by-g/kejbdjndbnbjgmefkgdddjlbokphdefk) para verificar que los tags se estén disparando correctamente.

## 🔧 Archivos Modificados

### 1. `data/tracking.json`
Archivo de configuración centralizado para todos los sistemas de seguimiento.

### 2. `index.php`
- Líneas 52-69: Código de seguimiento global de Google Ads en el `<head>`
- Línea 347: Script helper de conversiones

### 3. `js/google-ads-tracking.js`
Script helper que lee la configuración y dispara eventos de conversión.

### 4. `js/main.js`
- Líneas 140-142: Llamada a la función de seguimiento de conversiones después del envío exitoso del formulario

## 📊 Eventos Rastreados

### Conversión de Lead (Formulario de Contacto)

Cuando un usuario envía el formulario de contacto, se disparan automáticamente:

1. **Google Ads**: Evento de conversión con valor de 1.0 COP
2. **Google Analytics**: Evento `generate_lead` con categoría y etiqueta
3. **Meta Pixel**: Evento `Lead` (si está habilitado)

## 🎯 Ejemplo de Uso

```javascript
// El seguimiento se dispara automáticamente al enviar el formulario
// No necesitas código adicional

// Si quieres disparar una conversión manualmente:
trackGoogleAdsConversion();
```

## 🔍 Solución de Problemas

### Las conversiones no se registran

1. **Verifica que Google Ads esté habilitado**
   ```json
   "google_ads": {
       "enabled": true,  // ← Debe ser true
       ...
   }
   ```

2. **Verifica el ID y la etiqueta de conversión**
   - El ID debe comenzar con `AW-`
   - La etiqueta es case-sensitive

3. **Revisa la consola del navegador**
   - Busca errores de JavaScript
   - Verifica que aparezca "Google Ads conversion tracked"

4. **Usa Google Tag Assistant**
   - Instala la extensión de Chrome
   - Verifica que el tag de Google Ads se esté disparando

### El código gtag.js no se carga

1. Verifica que no haya bloqueadores de anuncios activos
2. Revisa la configuración de privacidad del navegador
3. Asegúrate de que el ID de conversión sea correcto

## 📈 Mejores Prácticas

1. **Prueba en modo de prueba primero**: Google Ads permite marcar conversiones como "prueba"
2. **Configura un valor de conversión realista**: Actualiza el valor en `google-ads-tracking.js` si es necesario
3. **Monitorea regularmente**: Revisa tus conversiones en Google Ads semanalmente
4. **Usa Google Tag Manager**: Para gestión avanzada de tags, considera migrar a GTM

## 🔐 Privacidad y GDPR

Este código de seguimiento recopila datos de usuarios. Asegúrate de:

- ✅ Tener una política de privacidad actualizada
- ✅ Implementar un banner de cookies (si aplica)
- ✅ Obtener consentimiento del usuario cuando sea necesario
- ✅ Cumplir con las regulaciones locales de protección de datos

## 📞 Soporte

Para más información sobre Google Ads:
- [Centro de Ayuda de Google Ads](https://support.google.com/google-ads)
- [Guía de Seguimiento de Conversiones](https://support.google.com/google-ads/answer/1722022)

---

**Desarrollado por**: Icono Virtual S.A.S.  
**Última actualización**: Febrero 2026
