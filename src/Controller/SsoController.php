<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Llave SSO exclusiva de MED, generada en /admin/aplicaciones de la intranet
 * (64 caracteres hexadecimales = 32 bytes). Se muestra una sola vez al
 * generarla. Preferible leerla del entorno (getenv('INTRANET_SSO_LLAVE') o el
 * .env de Symfony) que dejarla en el archivo; nunca versionarla ni enviarla
 * por correo.
 */
define('INTRANET_SSO_LLAVE', '14080881ef43be21ee31c41861a066d58886b57cff6fb3f86c1f4df9e16f11b4');

/** Slug de la aplicación MED en el directorio de la intranet (claim `app`). */
define('INTRANET_SSO_APP_SLUG', 'med-modulo-de-evaluacion-docente');

/** Valor esperado del claim `opcion` (parámetro personalizado en la intranet). */
define('INTRANET_SSO_OPCION', 'login_sso');

/** Página principal de MED: destino de todo rechazo cuando no se muestra el motivo. */
define('MED_URL_PRINCIPAL', 'https://med.unad.edu.co');


define('SSO_DEPURAR', false);

/**
 * Autoload del polyfill paragonie/sodium_compat 1.x (solo se usa si faltan las
 * funciones nativas). Con Composer en Symfony basta con el autoload general.
 */
define('MED_SODIUM_COMPAT', __DIR__ . '/../vendor/autoload.php');

if (!function_exists('sodium_crypto_secretbox_open') && is_file(MED_SODIUM_COMPAT)) {
    require MED_SODIUM_COMPAT;
}

class SsoController extends AbstractController
{
    /**
     * @Route("/sso", name="sso",  methods={"POST"})
     */
    public function index(Request $request): ?Response
    {
        $SSO_ORIGENES_PERMITIDOS = array(
            'https://intranetplus.unad.edu.co'
        );

        $SSO_IPS_PERMITIDAS = array( '172.16.0.0/16',
        );

        // La rama SSO se decide por la presencia del campo `sso`, nunca por otro campo:
        // la identificación, `opcion` y `app` viven DENTRO del token cifrado.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['sso'])) {
            ssoMedRechazar('Esta dirección solo acepta el POST con el campo «sso» que envía la intranet. Método: '
                . $_SERVER['REQUEST_METHOD'] . '. Campos POST: ' . (implode(', ', array_keys($_POST)) ?: '(ninguno)'));
        }
        $origenPeticion = ssoMedOrigenPeticion();
        if (!ssoMedOrigenPermitido($SSO_ORIGENES_PERMITIDOS, $origenPeticion)) {
            ssoMedRechazar('Origen no permitido: «' . ($origenPeticion !== '' ? $origenPeticion : 'sin cabeceras Origin/Referer')
                . '». Permitidos: ' . implode(', ', $SSO_ORIGENES_PERMITIDOS)
                . '. Si el clic sí se hizo desde la intranet, agrega ese origen a $SSO_ORIGENES_PERMITIDOS.');

            $ipCliente = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            if (!ssoMedIpPermitida($SSO_IPS_PERMITIDAS, $ipCliente)) {
                ssoMedRechazar('IP del cliente no permitida: «' . $ipCliente . '». Permitidas: '
                    . implode(', ', $SSO_IPS_PERMITIDAS) . '.');
            }
        }

        $resultado = ssoMedVerificarToken(INTRANET_SSO_LLAVE, (string)$_POST['sso']);

        if (!is_array($resultado)) {
            ssoMedRechazar($resultado);
        }

        $claims = $resultado;
        $identificacion = trim((string)$claims['identificacion']);


        //return $this->render('Security/sso.html.twig', array(
        //   'resultado' => $resultado
        //));

        ingresoAction($identificacion, $request);
        return null;
    }
}

/**
 * Descifra y valida el token emitido por la intranet.
 *
 * Devuelve los claims si el token es auténtico, vigente y corresponde a MED.
 * Si no, devuelve una cadena con el motivo del rechazo — pensado para la fase
 * de pruebas; en producción el motivo NO debe mostrarse al usuario (ayudaría a
 * quien intente falsificar un token).
 *
 * Esta función es la parte reutilizable: el responsable de MED puede llevarla
 * tal cual a un servicio de Symfony y usar el claim `identificacion` para
 * buscar el usuario y abrir la sesión.
 *
 * @param string $llaveHex Llave de MED, 64 caracteres hexadecimales
 * @param string $token Contenido del campo POST `sso`
 * @return array|string Claims descifrados, o el motivo del rechazo
 */
function ssoMedVerificarToken($llaveHex, $token)
{
    if (!function_exists('sodium_crypto_secretbox_open')) {
        return 'La extensión sodium no está disponible en PHP ' . PHP_VERSION
            . '. Con PHP < 7.2 instala paragonie/sodium_compat 1.x (MED_SODIUM_COMPAT).';
    }

    if ($llaveHex === '<CAMBIAR>' || $llaveHex === '') {
        return 'INTRANET_SSO_LLAVE no está configurada: pega la llave generada en /admin/aplicaciones.';
    }

    $llave = @hex2bin($llaveHex);
    if ($llave === false || strlen($llave) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
        return 'INTRANET_SSO_LLAVE no es válida: debe tener 64 caracteres hexadecimales (32 bytes).';
    }

    if ($token === '') {
        return 'El campo «sso» llegó vacío.';
    }

    // base64url -> base64 estándar
    $relleno = strlen($token) % 4;
    if ($relleno > 0) {
        $token .= str_repeat('=', 4 - $relleno);
    }
    $datos = base64_decode(strtr($token, '-_', '+/'), true);

    $minimo = SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES;
    if ($datos === false || strlen($datos) < $minimo) {
        return 'El token no es base64url válido o es demasiado corto (' . strlen($token) . ' caracteres).';
    }

    $nonce = substr($datos, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $cifrado = substr($datos, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    // La extensión nativa devuelve false si el token no es auténtico; el
    // polyfill puede lanzar SodiumException en su lugar. Ambos casos = rechazo.
    try {
        $payload = sodium_crypto_secretbox_open($cifrado, $nonce, $llave);
    } catch (Exception $e) {
        $payload = false;
    }

    if ($payload === false) {
        return 'No se pudo descifrar: la llave configurada no coincide con la de la intranet,'
            . ' el token fue alterado, o fue emitido para otra aplicación.';
    }

    $claims = json_decode($payload, true);
    if (!is_array($claims)) {
        return 'El contenido descifrado no es JSON.';
    }

    if (!isset($claims['exp'])) {
        return 'El token no trae el claim «exp».';
    }
    if ((int)$claims['exp'] < time()) {
        return 'Token expirado: exp=' . date('Y-m-d H:i:s', (int)$claims['exp'])
            . ', ahora=' . date('Y-m-d H:i:s') . '. Revisa la hora de ambos servidores.';
    }

    if (!isset($claims['app']) || $claims['app'] !== INTRANET_SSO_APP_SLUG) {
        return 'Claim «app» = «' . (isset($claims['app']) ? $claims['app'] : '(ausente)')
            . '», se esperaba «' . INTRANET_SSO_APP_SLUG . '». Revisa el slug en INTRANET_SSO_APP_SLUG.';
    }

    if (!isset($claims['opcion']) || $claims['opcion'] !== INTRANET_SSO_OPCION) {
        return 'Claim «opcion» = «' . (isset($claims['opcion']) ? $claims['opcion'] : '(ausente)')
            . '», se esperaba «' . INTRANET_SSO_OPCION . '». Agrega el parámetro personalizado'
            . ' opcion=' . INTRANET_SSO_OPCION . ' en /admin/aplicaciones.';
    }

    if (!isset($claims['identificacion']) || trim((string)$claims['identificacion']) === '') {
        return 'El token no trae el claim «identificacion». Agrégalo en /admin/aplicaciones →'
            . ' "Parámetros enviados al SSO". Claims recibidos: ' . implode(', ', array_keys($claims));
    }

    return $claims;
}

/**
 * Origen (esquema://host[:puerto]) desde el que llegó la petición, tomado de
 * la cabecera `Origin` o, en su defecto, de `Referer`. Normalizado en
 * minúsculas y sin puerto estándar (80 para http, 443 para https).
 *
 * @return string Origen normalizado, o '' si no llegó ninguna cabecera útil
 */
function ssoMedOrigenPeticion()
{
    $candidatos = array();
    if (!empty($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] !== 'null') {
        $candidatos[] = $_SERVER['HTTP_ORIGIN'];
    }
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $candidatos[] = $_SERVER['HTTP_REFERER'];
    }

    foreach ($candidatos as $url) {
        $origen = ssoMedNormalizarOrigen($url);
        if ($origen !== '') {
            return $origen;
        }
    }
    return '';
}

/**
 * Reduce una URL a su origen normalizado: esquema://host[:puerto], en
 * minúsculas, sin ruta, y sin el puerto cuando es el estándar del esquema.
 *
 * @param string $url URL o origen tal como llegó
 * @return string Origen normalizado, o '' si no es una URL http(s) válida
 */
function ssoMedNormalizarOrigen($url)
{
    $partes = @parse_url(trim((string)$url));
    if (!is_array($partes) || empty($partes['scheme']) || empty($partes['host'])) {
        return '';
    }

    $esquema = strtolower($partes['scheme']);
    if ($esquema !== 'http' && $esquema !== 'https') {
        return '';
    }

    $origen = $esquema . '://' . strtolower($partes['host']);
    if (!empty($partes['port'])) {
        $estandar = ($esquema === 'https') ? 443 : 80;
        if ((int)$partes['port'] !== $estandar) {
            $origen .= ':' . (int)$partes['port'];
        }
    }
    return $origen;
}

/**
 * Indica si el origen de la petición está en la lista de sitios permitidos.
 *
 * @param string[] $permitidos Valores de $SSO_ORIGENES_PERMITIDOS
 * @param string $origen Resultado de ssoMedOrigenPeticion()
 * @return bool
 */
function ssoMedOrigenPermitido(array $permitidos, $origen)
{
    if ($origen === '') {
        return false;
    }
    foreach ($permitidos as $permitido) {
        if (ssoMedNormalizarOrigen($permitido) === $origen) {
            return true;
        }
    }
    return false;
}

/**
 * Indica si una IPv4 está en la lista de IPs/rangos permitidos.
 * Lista vacía = sin restricción (devuelve true).
 *
 * @param string[] $permitidas IPs exactas o rangos CIDR ('172.16.0.0/16')
 * @param string $ip IP del cliente (REMOTE_ADDR)
 * @return bool
 */
function ssoMedIpPermitida(array $permitidas, $ip)
{
    if (count($permitidas) === 0) {
        return true;
    }
    $ipNum = ip2long($ip);
    if ($ipNum === false) {
        return false;
    }
    foreach ($permitidas as $regla) {
        $regla = trim((string)$regla);
        if (strpos($regla, '/') === false) {
            if (ip2long($regla) === $ipNum) {
                return true;
            }
            continue;
        }
        list($red, $bits) = explode('/', $regla, 2);
        $redNum = ip2long($red);
        $bits = (int)$bits;
        if ($redNum === false || $bits < 0 || $bits > 32) {
            continue;
        }
        $mascara = $bits === 0 ? 0 : (~0 << (32 - $bits)) & 0xFFFFFFFF;
        if (($ipNum & $mascara) === ($redNum & $mascara)) {
            return true;
        }
    }
    return false;
}

/**
 * Escapa texto para HTML.
 *
 * @param string $texto
 * @return string
 */
function ssoMedHtml($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

function ssoMedPagina($titulo, $cuerpoHtml)
{
    http_response_code(200);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="es"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . ssoMedHtml($titulo) . '</title></head><body>';
    echo '<h1>' . ssoMedHtml($titulo) . '</h1>';
    echo $cuerpoHtml;
    echo '<p><a href="' . ssoMedHtml(MED_URL_PRINCIPAL) . '">Ir a la página principal de MED</a></p>';
    echo '</body></html>';
    exit;
}

/**
 * Niega el acceso. Con SSO_DEPURAR muestra el motivo técnico; sin él, un
 * mensaje genérico que no revela nada sobre el token ni la configuración.
 *
 * @param string $motivo Detalle técnico; solo se muestra con SSO_DEPURAR
 * @return void
 */
function ssoMedRechazar($motivo)
{
    if (!SSO_DEPURAR) {
        ssoMedPagina('No fue posible iniciar sesión',
            '<p>No se pudo validar el acceso desde la intranet. Intenta de nuevo desde el directorio de servicios'
            . ' o ingresa directamente a MED.</p>');
    }

    ssoMedPagina('SSO MED — acceso rechazado',
        '<p><strong>Motivo:</strong> ' . ssoMedHtml($motivo) . '</p>'
        . ssoMedInfoPeticion()
        . '<p>PHP ' . ssoMedHtml(PHP_VERSION) . ' — sodium '
        . (function_exists('sodium_crypto_secretbox_open') ? 'disponible' : 'NO disponible') . '</p>');
}
function ingresoAction($cedula_usuario, $request) {
    $pass = $request->server->get('MED_PKW');
    $formulario = "<form method='post' name='datos' action='/login_check'>";
    $formulario .= "<input id='username' type='hidden' name='_username' value=$cedula_usuario />";
    $formulario .= "<input id='password' type='hidden' name='_password' value=$pass />";
    $formulario .= "</form>";
    $formulario .= "<script>document.forms[0].submit(); </script>";
    echo $formulario;
}
