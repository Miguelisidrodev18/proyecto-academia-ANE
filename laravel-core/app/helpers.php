<?php

use App\Models\Configuracion;

// ═══════════════════════════════════════════════════════════════
// WHATSAPP — NÚMERO
// ═══════════════════════════════════════════════════════════════

/**
 * Número de WhatsApp del admin (desde BD, fallback .env).
 * Devuelve 9 dígitos sin prefijo.
 */
function wa_number(): string
{
    return Configuracion::get('whatsapp_number', config('app.whatsapp_number', ''));
}

/**
 * URL wa.me con número normalizado (+51) y mensaje opcional.
 */
function wa_url(string $mensaje = ''): string
{
    $numero = preg_replace('/\D/', '', wa_number());
    if (strlen($numero) === 9) {
        $numero = '51' . $numero;
    }
    if (!$numero) {
        return '#';
    }
    return 'https://wa.me/' . $numero . ($mensaje ? '?text=' . rawurlencode($mensaje) : '');
}

// ═══════════════════════════════════════════════════════════════
// MENSAJES ADMIN → ALUMNO  (plantillas del modal de matrículas)
// ═══════════════════════════════════════════════════════════════

/**
 * Reemplaza marcadores en una plantilla.
 */
function wa_reemplazar(string $template, array $vars): string
{
    $keys   = array_map(fn($k) => '{' . $k . '}', array_keys($vars));
    $values = array_values($vars);
    return str_replace($keys, $values, $template);
}

/**
 * Plantilla "Recordatorio de pago" (admin → alumno).
 */
function wa_plantilla_recordatorio(string $nombre = '', string $saldo = '', string $dias = ''): string
{
    $tpl = Configuracion::get(
        'wa_msg_recordatorio',
        'Hola {nombre}, te recordamos que tienes un pago pendiente en Academia Nueva Era por S/. {saldo}. Por favor regulariza tu pago para no perder el acceso a tus clases.'
    );
    return wa_reemplazar($tpl, compact('nombre', 'saldo', 'dias'));
}

/**
 * Plantilla "Renovación" (admin → alumno).
 */
function wa_plantilla_renovacion(string $nombre = '', string $saldo = '', string $dias = ''): string
{
    $tpl = Configuracion::get(
        'wa_msg_renovacion',
        'Hola {nombre}, tu membresía en Academia Nueva Era ha vencido. ¡Renueva tu plan y sigue disfrutando de todas tus clases! Contáctanos para coordinar.'
    );
    return wa_reemplazar($tpl, compact('nombre', 'saldo', 'dias'));
}

/**
 * Plantilla "Próximo vencimiento" (admin → alumno).
 */
function wa_plantilla_vencimiento(string $nombre = '', string $saldo = '', string $dias = ''): string
{
    $tpl = Configuracion::get(
        'wa_msg_vencimiento',
        'Hola {nombre}, te informamos que solo te quedan {dias} días de acceso en Academia Nueva Era. ¡Renueva a tiempo para no perder el acceso!'
    );
    return wa_reemplazar($tpl, compact('nombre', 'saldo', 'dias'));
}

// ═══════════════════════════════════════════════════════════════
// MENSAJES ALUMNO → ADMIN  (pre-cargados en botones del alumno)
// ═══════════════════════════════════════════════════════════════

/**
 * Mensaje que manda el alumno cuando su membresía caducó.
 */
function wa_alumno_caducada(string $nombre = ''): string
{
    $tpl = Configuracion::get(
        'wa_alumno_caducada',
        'Hola, mi membresía de Academia Nueva Era ha caducado. Quisiera renovar mi pago para seguir gozando de todos los beneficios.'
    );
    return wa_reemplazar($tpl, ['nombre' => $nombre]);
}

/**
 * Mensaje que manda el alumno cuando su acceso está suspendido.
 */
function wa_alumno_suspendida(string $nombre = ''): string
{
    $tpl = Configuracion::get(
        'wa_alumno_suspendida',
        'Hola, mi acceso en Academia Nueva Era está suspendido. Quisiera regularizar mi pago para recuperar el acceso a mis clases.'
    );
    return wa_reemplazar($tpl, ['nombre' => $nombre]);
}

/**
 * Alias para compatibilidad: mensaje de renovación genérico (alumno → admin).
 */
function wa_mensaje_renovacion(string $nombre = '', string $saldo = ''): string
{
    return wa_alumno_caducada($nombre);
}

/**
 * Plantilla "Bienvenida" — envía credenciales de acceso al alumno recién matriculado.
 */
function wa_plantilla_bienvenida(string $nombre = '', string $email = '', string $password = ''): string
{
    $url = rtrim(config('app.url'), '/');
    $tpl = Configuracion::get(
        'wa_msg_bienvenida',
        "¡Hola {nombre}! 👋 Bienvenido(a) a Academia Nueva Era Estudiantil.\n\nEstos son tus accesos a la plataforma:\n📧 Usuario: {email}\n🔑 Contraseña temporal: {password}\n🌐 Ingresa en: {url}\n\n⚠️ Por seguridad, te recomendamos cambiar tu contraseña la primera vez que ingreses.\n\nCualquier duda, escríbenos. 🎓"
    );
    return wa_reemplazar($tpl, ['nombre' => $nombre, 'email' => $email, 'password' => $password, 'url' => $url]);
}
