# 🚀 Guía Rápida - Google Ads Tracking

## ⚡ Activación en 3 Pasos

### 1️⃣ Obtén tus credenciales de Google Ads
- Ve a Google Ads → Conversiones → Nueva conversión
- Copia tu **ID de conversión** (formato: `AW-1234567890`)
- Copia tu **Etiqueta de conversión** (cadena alfanumérica)

### 2️⃣ Edita `data/tracking.json`
```json
"google_ads": {
    "enabled": true,                    // ← Cambia a true
    "conversion_id": "AW-1234567890",   // ← Tu ID real
    "conversion_label": "AbC123XyZ"     // ← Tu etiqueta real
}
```

### 3️⃣ ¡Listo! 
El seguimiento está activo. Prueba enviando el formulario de contacto.

---

## 📋 Checklist de Verificación

- [ ] Google Ads habilitado (`"enabled": true`)
- [ ] ID de conversión correcto (comienza con `AW-`)
- [ ] Etiqueta de conversión correcta
- [ ] Formulario de contacto funciona
- [ ] Mensaje "Google Ads conversion tracked" en consola
- [ ] Conversiones aparecen en Google Ads (puede tardar 24-48h)

---

## 🎯 ¿Qué se rastrea?

✅ **Envío de formulario de contacto** → Conversión automática  
✅ **Valor**: 1.0 COP por conversión  
✅ **Datos**: Servicio seleccionado, timestamp  

---

## 🔧 Archivos del Sistema

```
iconovirtual/
├── data/
│   └── tracking.json              # ← Configuración aquí
├── js/
│   ├── google-ads-tracking.js     # Helper de conversiones
│   └── main.js                    # Integración con formulario
├── index.php                      # Código gtag.js en <head>
└── GOOGLE_ADS_SETUP.md           # Documentación completa
```

---

## 🆘 Problemas Comunes

**❌ No veo conversiones en Google Ads**
- Las conversiones pueden tardar 24-48 horas en aparecer
- Verifica que `enabled: true` en tracking.json
- Usa Google Tag Assistant para verificar

**❌ Error en consola del navegador**
- Verifica que el ID comience con `AW-`
- Revisa que no haya bloqueadores de anuncios
- Comprueba la sintaxis del JSON

**❌ El formulario no envía**
- Revisa la consola para errores de JavaScript
- Verifica que process.php esté funcionando
- Comprueba la conexión a internet

---

## 📞 Recursos

- 📖 [Documentación completa](GOOGLE_ADS_SETUP.md)
- 🔗 [Google Ads Help](https://support.google.com/google-ads)
- 🛠️ [Google Tag Assistant](https://tagassistant.google.com)

---

**✨ Sistema implementado por Icono Virtual S.A.S.**
