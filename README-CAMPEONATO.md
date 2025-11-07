


# .🧾 Integración de Google Vision AI en Laravel (Validación de Documentos)

## * Objetivo del módulo
Este módulo permite validar las fichas de jugadores mediante **Google Cloud Vision AI**, verificando que todos los documentos (ficha verde, cédula, ficha médica, autorización de menor, etc.) contengan la **misma cédula** o información coherente.

---

##  1. Requisitos previos
- PHP >= 8.1
- Laravel >= 10
- Composer instalado
- Cuenta en **Google Cloud Platform (GCP)**
- Proyecto activo con la **API de Vision habilitada**

---

##  2. Configurar credenciales de Google Vision

1. Ingresar a [Google Cloud Console](https://console.cloud.google.com/).
2. Crear un nuevo **Proyecto** (por ejemplo: `gestion-campeonato`).
3. Activar la **Vision API**:
    - Ir a: `APIs y Servicios -> Biblioteca`
    - Buscar **Cloud Vision API**
    - Presionar **Habilitar**
4. Crear una **Cuenta de servicio**:
    - `IAM y administración -> Cuentas de servicio -> Crear cuenta`
    - Asignarle rol: `Proyecto > Editor`
5. Generar una **clave JSON**:
    - En la cuenta de servicio -> pestaña **Claves -> Agregar clave -> Crear clave nueva -> JSON**
    - Descargar el archivo, por ejemplo:
      ```
      C:\credenciales\vision-service-account.json
      ```

---

## 3. Instalar dependencias
```bash
composer require google/cloud-vision
```

---

##  4. Configurar variable de entorno
En tu archivo `.env`:
```env
GOOGLE_APPLICATION_CREDENTIALS="C:\credenciales\vision-service-account.json"
```

---

##  5. Configurar certificados SSL

1. Descargar desde [https://curl.se/ca/cacert.pem](https://curl.se/ca/cacert.pem)
2. Guardarlo, por ejemplo:
   ```
   C:\php\extras\ssl\cacert.pem
   ```
3. Editar `php.ini`:
   ```ini
   curl.cainfo = "C:\php\extras\ssl\cacert.pem"
   openssl.cafile = "C:\php\extras\ssl\cacert.pem"
   ```
4. Reiniciar Apache o PHP-FPM.

---

##  6. Configuración temporal para entorno local

```php
putenv('GOOGLE_CLOUD_DISABLE_GRPC=true');
putenv('GRPC_DEFAULT_SSL_ROOTS_FILE_PATH=');
putenv('GRPC_SSL_CIPHER_SUITES=DEFAULT');

$vision = new ImageAnnotatorClient([
    'transport' => 'rest',
]);
```

---

## З 7. Ejemplo de función de análisis

```php
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;

public function analizarFicha($rutaAbsoluta)
{
    $errores = [];

    try {
        putenv('GOOGLE_CLOUD_DISABLE_GRPC=true');
        putenv('GRPC_DEFAULT_SSL_ROOTS_FILE_PATH=');
        putenv('GRPC_SSL_CIPHER_SUITES=DEFAULT');

        $vision = new ImageAnnotatorClient(['transport' => 'rest']);

        $image = (new Image())->setContent(file_get_contents($rutaAbsoluta));
        $feature = (new Feature())->setType(Feature::Type::TEXT_DETECTION);
        $annotateRequest = (new AnnotateImageRequest())
            ->setImage($image)
            ->setFeatures([$feature]);
        $batchRequest = new BatchAnnotateImagesRequest();
        $batchRequest->setRequests([$annotateRequest]);

        $response = $vision->batchAnnotateImages($batchRequest);
        $responses = $response->getResponses();

        foreach ($responses as $res) {
            if ($res->getError()->getMessage()) {
                $errores[] = "Error de API: ".$res->getError()->getMessage();
            } else {
                $annotations = $res->getTextAnnotations();
                if (count($annotations) === 0) {
                    $errores[] = "No se detectó texto en la imagen.";
                } else {
                    $contenido = strtolower($annotations[0]->getDescription());
                    if (!str_contains($contenido, 'liga') && !str_contains($contenido, 'ufi')) {
                        $errores[] = "No se encontró el texto 'Liga' ni 'UFI' en la ficha.";
                    }
                }
            }
        }

        $vision->close();
    } catch (\Exception $e) {
        $errores[] = "Excepción detectada al usar Google Vision API: ".$e->getMessage();
    }

    return $errores;
}
```

---

## 8. Rutas y Controladores
`routes/web.php`:
```php
Route::post('/fichas', [FichaController::class, 'store'])->name('fichas.store');
```

---

##  9. Checklist final para servidor
| Elemento | Estado | Notas |
|-----------|---------|-------|
| PHP 8.1+ | ✅| |
| Extensiones: `curl`, `openssl`, `grpc`, `json` | ✅| |
| Certificado `cacert.pem` configurado | ✅| |
| Archivo de credenciales JSON de Google | ✅| |
| Vision API habilitada | ✅| |
| Permisos en `storage/app/public` | ✅| |

---

## 10. Futuras mejoras
- Validar coincidencia exacta de número de cédula.
- Verificar coherencia entre varios documentos.
- Guardar en base de datos los resultados del análisis.
- Generar reportes PDF automáticos.
