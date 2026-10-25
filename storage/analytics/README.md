# Visitas de las landing pages

## Paneles publicos

- GH Hoteles: `/GHHoteles.com.ve/visitas.php`
- Jesus Santana: `/jsantana/visitas.php`

Anteponer el subdirectorio de la instalacion, si existe (en WAMP normalmente
`/sistemaNFC`). Cada panel esta fijado a su landing; no acepta un identificador
del cliente por URL. No requiere contrasena. `noindex` desaconseja su indexacion,
pero no restringe el acceso.

En Windows tambien funciona `ghhoteles.com.ve` en minusculas. En Linux respetar
el nombre versionado de la carpeta: `GHHoteles.com.ve`.

## Conteo

Cada peticion **GET** que ejecuta el `index.php` de una landing suma una visita,
incluidas recargas, accesos propios y robots. HEAD, POST, archivos estaticos y
consultas a `visitas.php` no suman. No se utilizan cookies ni se guardan IP,
agentes de usuario o datos personales.

Son cargas de pagina, no visitantes unicos, conversiones ni retorno monetario.
No se pueden recuperar visitas previas a la instalacion. Los ultimos 7 y 30 dias
son dias calendario incluyendo hoy, en `America/Caracas`. Los dias sin visitas
se muestran con cero. La consulta del panel no registra una visita.

### Ajuste historico manual de GH Hoteles

GH Hoteles tiene **78** visitas de ajuste historico manual configuradas por
fecha en `LandingVisitService::INITIAL_VIEWS`:

| Fecha | Porcentaje solicitado | Visitas asignadas |
| --- | --- | --- |
| 2026-10-24 | 60% | 47 |
| 2026-10-23 | 15% | 12 |
| 2026-10-22 | 25% | 19 |

Se toman las partes enteras (46, 11, 19) y se asignan las dos unidades restantes
a los mayores residuos decimales (0.8 y 0.7), conservando exactamente 78.
Es una distribucion manual solicitada, no mediciones automaticas recuperadas.

El ajuste se suma a los datos registrados de cada fecha, sin reemplazarlos:

- El total historico conserva las 78 visitas.
- Hoy solo incluye un ajuste si coincide con su fecha; el 25/10 no incluye ninguno.
- Los indicadores de 7/30 dias y el desglose diario incluyen solo las fechas
  dentro de su ventana. Cada tarjeta indica el ajuste manual incluido.
- En 7 dias, el ajuste baja a 59 el 29/10/2026, a 47 el 30/10 y a 0 el 31/10.
- En 30 dias, baja a 59 el 21/11/2026, a 47 el 22/11 y a 0 el 23/11.
- Jesus Santana no tiene ajuste y conserva su conteo original.
- Las fechas de primera/ultima visita y el total registrado automaticamente
  siguen refiriendose exclusivamente a mediciones del contador.

No se insertan las 78 visitas en SQLite ni se modifica el historial existente.
Publicar este cambio solo requiere desplegar el servicio y la vista; no hay
migraciones ni comandos de inicializacion. Consultar varias veces o volver a
desplegar no duplica el ajuste: se suma una sola vez al calcular los resultados.
Ante un error de SQLite, se mantiene el aviso HTTP 503; no se muestra el ajuste
como si la consulta hubiera funcionado.

## Instalacion y persistencia

Requiere PHP 8.3 o posterior con **PDO SQLite** (`pdo_sqlite`) y permisos de
escritura del usuario de PHP sobre esta carpeta. No necesita cambiar MySQL,
ejecutar migraciones ni instalar paquetes Composer. La base `visits.sqlite`
y su tabla se crean automaticamente al primer acceso. Los incrementos son
atomicos y esperan hasta 5 segundos por bloqueos de escrituras concurrentes.

Desplegar los dos `index.php`, los dos `visitas.php`, el servicio
`app/Services/LandingVisitService.php`, la vista `app/Views/landing/visits.php`,
el helper existente `app/Helpers/functions.php` y esta carpeta, **incluido
`.htaccess`**. Mantener la estructura relativa del proyecto.

Apache debe permitir `Require all denied` en `.htaccess` (mod_authz_core y
AllowOverride AuthConfig). Verificar que pedir
`/storage/analytics/visits.sqlite` por HTTP devuelve **403**. En Nginx u otro
servidor, configurar una denegacion equivalente para `/storage/analytics/`
antes de publicar.

No borrar ni reemplazar la base en despliegues: contiene el historial de ambos
clientes y esta excluida de Git. Incluirla en los respaldos persistentes; usar
una copia consistente de SQLite o detener escrituras durante el respaldo.
Para varias instancias del servidor, no usar bases locales independientes:
se necesita un almacenamiento centralizado antes de escalar.

Las landing envian `Cache-Control: no-store`. Excluir sus `index.php` de caches
de pagina completa de CDN/proxy; una respuesta servida sin ejecutar PHP no
puede contarse. Restaurar una pagina desde el historial del navegador sin
peticion al servidor tampoco cuenta.

Si SQLite falla, la landing sigue funcionando y el error se registra mediante
`error_log` de PHP con el prefijo `[landing-visits]`. El panel devuelve HTTP 503
y un aviso, nunca un cero que aparente exito. Las cargas que no pudieron
guardarse durante una averia no se recuperan automaticamente.

## Validacion

Desde la raiz del proyecto:

```powershell
php vendor\bin\phpunit tests\LandingVisitServiceTest.php --do-not-cache-result
```

Las pruebas usan bases en memoria o temporales, sin alterar las cifras reales.
