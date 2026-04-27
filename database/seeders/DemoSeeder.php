<?php

namespace Database\Seeders;

use App\Models\Bug;
use App\Models\CasoPrueba;
use App\Models\EjecucionPrueba;
use App\Models\HistorialBug;
use App\Models\PasoPrueba;
use App\Models\Proyecto;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. ROLES ────────────────────────────────────────────────────────────
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();
        $rolTester = Rol::where('nombre', 'Tester')->first();
        $rolDev = Rol::where('nombre', 'Desarrollador')->first();

        // ─── 2. USUARIOS ─────────────────────────────────────────────────────────
        $admin = Usuario::updateOrCreate(
            ['email' => 'admin@calidad.com'],
            [
                'nombre'   => 'Admin',
                'apellido' => 'Principal',
                'password' => Hash::make('12345678'),
                'rol_id'   => $rolAdmin->id,
                'estado'   => 'activo',
            ]
        );

        $tester1 = Usuario::updateOrCreate(
            ['email' => 'sofia.tester@calidad.com'],
            [
                'nombre'   => 'Sofía',
                'apellido' => 'Ramírez',
                'password' => Hash::make('12345678'),
                'rol_id'   => $rolTester->id,
                'estado'   => 'activo',
            ]
        );

        $tester2 = Usuario::updateOrCreate(
            ['email' => 'carlos.tester@calidad.com'],
            [
                'nombre'   => 'Carlos',
                'apellido' => 'Mendoza',
                'password' => Hash::make('12345678'),
                'rol_id'   => $rolTester->id,
                'estado'   => 'activo',
            ]
        );

        $dev1 = Usuario::updateOrCreate(
            ['email' => 'luis.dev@calidad.com'],
            [
                'nombre'   => 'Luis',
                'apellido' => 'Torres',
                'password' => Hash::make('12345678'),
                'rol_id'   => $rolDev->id,
                'estado'   => 'activo',
            ]
        );

        $dev2 = Usuario::updateOrCreate(
            ['email' => 'ana.dev@calidad.com'],
            [
                'nombre'   => 'Ana',
                'apellido' => 'Vargas',
                'password' => Hash::make('12345678'),
                'rol_id'   => $rolDev->id,
                'estado'   => 'activo',
            ]
        );

        $dev3 = Usuario::updateOrCreate(
            ['email' => 'pedro.dev@calidad.com'],
            [
                'nombre'   => 'Pedro',
                'apellido' => 'Núñez',
                'password' => Hash::make('12345678'),
                'rol_id'   => $rolDev->id,
                'estado'   => 'activo',
            ]
        );

        // ─── 3. PROYECTOS ────────────────────────────────────────────────────────
        $p1 = Proyecto::updateOrCreate(
            ['nombre' => 'Sistema de Facturación'],
            [
                'descripcion' => 'Módulo de generación y gestión de facturas electrónicas para clientes corporativos.',
                'estado'      => 'activo',
                'created_by'  => $admin->id,
            ]
        );

        $p2 = Proyecto::updateOrCreate(
            ['nombre' => 'Portal de Clientes'],
            [
                'descripcion' => 'Portal web de autogestión para consulta de cuenta, historial y soporte.',
                'estado'      => 'activo',
                'created_by'  => $admin->id,
            ]
        );

        $p3 = Proyecto::updateOrCreate(
            ['nombre' => 'App Móvil de Inventario'],
            [
                'descripcion' => 'Aplicación Android/iOS para control de stock en almacenes.',
                'estado'      => 'activo',
                'created_by'  => $admin->id,
            ]
        );

        // ─── 4. MIEMBROS DE PROYECTO ─────────────────────────────────────────────
        $p1->miembros()->syncWithoutDetaching([
            $tester1->id => ['fecha_asignacion' => now()->subDays(20)->toDateString()],
            $dev1->id    => ['fecha_asignacion' => now()->subDays(20)->toDateString()],
            $dev2->id    => ['fecha_asignacion' => now()->subDays(18)->toDateString()],
        ]);

        $p2->miembros()->syncWithoutDetaching([
            $tester2->id => ['fecha_asignacion' => now()->subDays(15)->toDateString()],
            $dev2->id    => ['fecha_asignacion' => now()->subDays(15)->toDateString()],
            $dev3->id    => ['fecha_asignacion' => now()->subDays(14)->toDateString()],
        ]);

        $p3->miembros()->syncWithoutDetaching([
            $tester1->id => ['fecha_asignacion' => now()->subDays(10)->toDateString()],
            $tester2->id => ['fecha_asignacion' => now()->subDays(10)->toDateString()],
            $dev3->id    => ['fecha_asignacion' => now()->subDays(10)->toDateString()],
        ]);

        // ─── 5. BUGS ─────────────────────────────────────────────────────────────
        // Proyecto 1 — Facturación

        $bug1 = Bug::updateOrCreate(
            ['titulo' => 'Error al generar PDF de factura con caracteres especiales'],
            [
                'proyecto_id'       => $p1->id,
                'descripcion'       => 'Al generar una factura cuyo cliente tiene tildes o la letra ñ en su razón social, el PDF se corrompe y no se puede abrir.',
                'pasos_reproducir'  => "1. Crear factura para cliente 'Señor García S.A.'\n2. Hacer clic en 'Generar PDF'\n3. Intentar abrir el archivo descargado.",
                'resultado_esperado'=> 'El PDF se genera correctamente mostrando los caracteres especiales.',
                'resultado_actual'  => 'El archivo PDF queda corrupto y Adobe Reader muestra error de lectura.',
                'severidad'         => 'alta',
                'estado'            => 'cerrado',
                'reportado_por'     => $tester1->id,
                'asignado_a'        => $dev1->id,
                'fecha_reporte'     => now()->subDays(18),
                'fecha_resolucion'  => now()->subDays(10),
                'tiempo_resolucion_horas' => 192,
            ]
        );

        $bug2 = Bug::updateOrCreate(
            ['titulo' => 'Cálculo incorrecto de IVA en facturas con descuento'],
            [
                'proyecto_id'       => $p1->id,
                'descripcion'       => 'Cuando se aplica un descuento porcentual, el IVA se calcula sobre el subtotal bruto en lugar del subtotal con descuento, generando montos incorrectos.',
                'pasos_reproducir'  => "1. Crear factura con 3 ítems.\n2. Aplicar descuento del 15%.\n3. Verificar el monto de IVA calculado.",
                'resultado_esperado'=> 'IVA = (subtotal − descuento) × 0.13',
                'resultado_actual'  => 'IVA = subtotal × 0.13 (ignora el descuento)',
                'severidad'         => 'alta',
                'estado'            => 'en proceso',
                'reportado_por'     => $tester1->id,
                'asignado_a'        => $dev2->id,
                'fecha_reporte'     => now()->subDays(12),
                'fecha_resolucion'  => null,
                'tiempo_resolucion_horas' => null,
            ]
        );

        $bug3 = Bug::updateOrCreate(
            ['titulo' => 'Botón "Nueva Factura" no visible en pantallas menores a 1280px'],
            [
                'proyecto_id'       => $p1->id,
                'descripcion'       => 'En resoluciones de 1024×768 y 1280×720, el botón de nueva factura queda oculto detrás del panel lateral.',
                'pasos_reproducir'  => "1. Abrir el sistema con resolución 1024×768.\n2. Navegar a Facturación → Listado.\n3. Observar la zona superior derecha.",
                'resultado_esperado'=> 'El botón "Nueva Factura" siempre es visible.',
                'resultado_actual'  => 'El botón queda detrás del sidebar y no se puede hacer clic.',
                'severidad'         => 'media',
                'estado'            => 'abierto',
                'reportado_por'     => $tester2->id,
                'asignado_a'        => null,
                'fecha_reporte'     => now()->subDays(5),
                'fecha_resolucion'  => null,
                'tiempo_resolucion_horas' => null,
            ]
        );

        // Proyecto 2 — Portal de Clientes

        $bug4 = Bug::updateOrCreate(
            ['titulo' => 'La sesión no expira después del tiempo configurado'],
            [
                'proyecto_id'       => $p2->id,
                'descripcion'       => 'El portal mantiene la sesión activa incluso después de 60 minutos de inactividad, ignorando la configuración de SESSION_LIFETIME.',
                'pasos_reproducir'  => "1. Iniciar sesión en el portal.\n2. No realizar ninguna acción por 70 minutos.\n3. Intentar navegar a otra sección.",
                'resultado_esperado'=> 'El sistema redirige al login y muestra mensaje "Sesión expirada".',
                'resultado_actual'  => 'El usuario sigue autenticado sin problema.',
                'severidad'         => 'alta',
                'estado'            => 'en proceso',
                'reportado_por'     => $tester2->id,
                'asignado_a'        => $dev3->id,
                'fecha_reporte'     => now()->subDays(8),
                'fecha_resolucion'  => null,
                'tiempo_resolucion_horas' => null,
            ]
        );

        $bug5 = Bug::updateOrCreate(
            ['titulo' => 'Descarga de estado de cuenta falla para contratos antiguos'],
            [
                'proyecto_id'       => $p2->id,
                'descripcion'       => 'Al intentar descargar el estado de cuenta de contratos creados antes del 2022, el sistema devuelve error 500.',
                'pasos_reproducir'  => "1. Iniciar sesión con cuenta con contrato anterior a 2022.\n2. Ir a Mi Cuenta → Estado de Cuenta.\n3. Seleccionar rango de fechas 2021-2022.\n4. Clic en Descargar.",
                'resultado_esperado'=> 'Se descarga el PDF con el estado de cuenta histórico.',
                'resultado_actual'  => 'Error 500: "Undefined column fecha_inicio"',
                'severidad'         => 'alta',
                'estado'            => 'cerrado',
                'reportado_por'     => $tester2->id,
                'asignado_a'        => $dev2->id,
                'fecha_reporte'     => now()->subDays(14),
                'fecha_resolucion'  => now()->subDays(7),
                'tiempo_resolucion_horas' => 168,
            ]
        );

        $bug6 = Bug::updateOrCreate(
            ['titulo' => 'Campo de búsqueda no filtra por número de contrato'],
            [
                'proyecto_id'       => $p2->id,
                'descripcion'       => 'El buscador del portal solo filtra por nombre de cliente, ignorando el número de contrato aunque se ingrese explícitamente.',
                'pasos_reproducir'  => "1. Ir a Soporte → Mis Contratos.\n2. Escribir el número de contrato exacto en el buscador.\n3. Observar resultados.",
                'resultado_esperado'=> 'Se muestra el contrato correspondiente al número ingresado.',
                'resultado_actual'  => 'No se muestra ningún resultado aunque el contrato exista.',
                'severidad'         => 'media',
                'estado'            => 'abierto',
                'reportado_por'     => $tester1->id,
                'asignado_a'        => null,
                'fecha_reporte'     => now()->subDays(3),
                'fecha_resolucion'  => null,
                'tiempo_resolucion_horas' => null,
            ]
        );

        // Proyecto 3 — App Móvil

        $bug7 = Bug::updateOrCreate(
            ['titulo' => 'Crash al escanear código de barras en modo oscuro'],
            [
                'proyecto_id'       => $p3->id,
                'descripcion'       => 'En dispositivos Android con modo oscuro habilitado, la cámara de escaneo de códigos de barras cierra la aplicación sin previo aviso.',
                'pasos_reproducir'  => "1. Activar modo oscuro en Android.\n2. Abrir la app.\n3. Ir a Inventario → Escanear producto.\n4. Apuntar la cámara a cualquier código de barras.",
                'resultado_esperado'=> 'La app lee el código y muestra el producto correspondiente.',
                'resultado_actual'  => 'La app se cierra abruptamente (crash sin log visible).',
                'severidad'         => 'alta',
                'estado'            => 'en proceso',
                'reportado_por'     => $tester1->id,
                'asignado_a'        => $dev3->id,
                'fecha_reporte'     => now()->subDays(6),
                'fecha_resolucion'  => null,
                'tiempo_resolucion_horas' => null,
            ]
        );

        $bug8 = Bug::updateOrCreate(
            ['titulo' => 'Stock negativo al registrar salida sin validación'],
            [
                'proyecto_id'       => $p3->id,
                'descripcion'       => 'Es posible registrar una salida de stock superior a la cantidad disponible, generando cantidades negativas en el inventario.',
                'pasos_reproducir'  => "1. Seleccionar producto con stock = 5.\n2. Registrar salida de 10 unidades.\n3. Confirmar la operación.",
                'resultado_esperado'=> 'El sistema muestra error "Stock insuficiente. Disponible: 5".',
                'resultado_actual'  => 'La salida se registra y el stock queda en -5.',
                'severidad'         => 'alta',
                'estado'            => 'abierto',
                'reportado_por'     => $tester2->id,
                'asignado_a'        => null,
                'fecha_reporte'     => now()->subDays(2),
                'fecha_resolucion'  => null,
                'tiempo_resolucion_horas' => null,
            ]
        );

        $bug9 = Bug::updateOrCreate(
            ['titulo' => 'Listado de productos no ordena correctamente por nombre'],
            [
                'proyecto_id'       => $p3->id,
                'descripcion'       => 'El orden alfabético del listado de productos mezcla mayúsculas y minúsculas, poniendo "zapato" antes de "Ácido" por el valor ASCII.',
                'pasos_reproducir'  => "1. Ir a Inventario → Productos.\n2. Tocar el encabezado de columna 'Nombre' para ordenar.",
                'resultado_esperado'=> 'Orden alfabético insensible a mayúsculas/minúsculas y con soporte de tildes.',
                'resultado_actual'  => 'El orden es por valor ASCII: Z antes de á, a antes de Á.',
                'severidad'         => 'baja',
                'estado'            => 'abierto',
                'reportado_por'     => $tester1->id,
                'asignado_a'        => null,
                'fecha_reporte'     => now()->subDays(1),
                'fecha_resolucion'  => null,
                'tiempo_resolucion_horas' => null,
            ]
        );

        // ─── 6. HISTORIAL DE BUGS ────────────────────────────────────────────────
        // Bug 1 (cerrado)
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug1->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => null],
            ['usuario_id' => $tester1->id, 'comentario' => 'Bug registrado en el sistema.', 'fecha_cambio' => now()->subDays(18)]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug1->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => 'abierto', 'comentario' => 'Bug asignado a Luis Torres.'],
            ['usuario_id' => $tester1->id, 'fecha_cambio' => now()->subDays(17)]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug1->id, 'estado_nuevo' => 'en proceso', 'estado_anterior' => 'abierto'],
            ['usuario_id' => $dev1->id, 'comentario' => 'Revisando el generador de PDF. El problema es el encoding UTF-8 en la librería DOMPDF.', 'fecha_cambio' => now()->subDays(15)]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug1->id, 'estado_nuevo' => 'cerrado', 'estado_anterior' => 'en proceso'],
            ['usuario_id' => $dev1->id, 'comentario' => 'Corregido. Se forzó UTF-8 en la configuración de DOMPDF y se actualizó la fuente a DejaVu con soporte completo de caracteres especiales.', 'fecha_cambio' => now()->subDays(10)]
        );

        // Bug 2 (en proceso)
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug2->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => null],
            ['usuario_id' => $tester1->id, 'comentario' => 'Bug registrado en el sistema.', 'fecha_cambio' => now()->subDays(12)]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug2->id, 'estado_nuevo' => 'en proceso', 'estado_anterior' => 'abierto'],
            ['usuario_id' => $dev2->id, 'comentario' => 'Localizé el error en el método calcularImpuestos(). Trabajando en el fix.', 'fecha_cambio' => now()->subDays(9)]
        );

        // Bug 3 (abierto)
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug3->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => null],
            ['usuario_id' => $tester2->id, 'comentario' => 'Bug registrado en el sistema.', 'fecha_cambio' => now()->subDays(5)]
        );

        // Bug 4 (en proceso)
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug4->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => null],
            ['usuario_id' => $tester2->id, 'comentario' => 'Bug registrado en el sistema.', 'fecha_cambio' => now()->subDays(8)]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug4->id, 'estado_nuevo' => 'en proceso', 'estado_anterior' => 'abierto'],
            ['usuario_id' => $dev3->id, 'comentario' => 'Revisando la configuración de sesiones. Parece que el middleware de expiración no está aplicado en todas las rutas.', 'fecha_cambio' => now()->subDays(6)]
        );

        // Bug 5 (cerrado)
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug5->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => null],
            ['usuario_id' => $tester2->id, 'comentario' => 'Bug registrado en el sistema.', 'fecha_cambio' => now()->subDays(14)]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug5->id, 'estado_nuevo' => 'en proceso', 'estado_anterior' => 'abierto'],
            ['usuario_id' => $dev2->id, 'comentario' => 'El problema era una columna renombrada en la migración de 2022. Corrigiendo la query.', 'fecha_cambio' => now()->subDays(11)]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug5->id, 'estado_nuevo' => 'cerrado', 'estado_anterior' => 'en proceso'],
            ['usuario_id' => $dev2->id, 'comentario' => 'Resuelto. Se actualizó la query para usar el nombre de columna correcto y se agregó un índice de compatibilidad.', 'fecha_cambio' => now()->subDays(7)]
        );

        // Bugs 6, 8, 9 (abiertos sin asignación previa)
        foreach ([
            [$bug6, $tester1->id],
            [$bug8, $tester2->id],
            [$bug9, $tester1->id],
        ] as [$bug, $reporterId]) {
            HistorialBug::updateOrCreate(
                ['bug_id' => $bug->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => null],
                ['usuario_id' => $reporterId, 'comentario' => 'Bug registrado en el sistema.', 'fecha_cambio' => $bug->fecha_reporte]
            );
        }

        // Bug 7 (abierto inicial + en proceso)
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug7->id, 'estado_nuevo' => 'abierto', 'estado_anterior' => null],
            ['usuario_id' => $tester1->id, 'comentario' => 'Bug registrado en el sistema.', 'fecha_cambio' => $bug7->fecha_reporte]
        );
        HistorialBug::updateOrCreate(
            ['bug_id' => $bug7->id, 'estado_nuevo' => 'en proceso', 'estado_anterior' => 'abierto'],
            ['usuario_id' => $dev3->id, 'comentario' => 'Reproducido en Pixel 6 con modo oscuro. Analizando el callback de la cámara.', 'fecha_cambio' => now()->subDays(4)]
        );

        // ─── 7. CASOS DE PRUEBA ──────────────────────────────────────────────────
        // Proyecto 1

        $cp1 = CasoPrueba::updateOrCreate(
            ['nombre' => 'CP-001: Generación de factura con datos completos'],
            [
                'descripcion'        => 'Verificar que el sistema genera correctamente una factura con todos los campos obligatorios completos.',
                'condiciones'        => 'Usuario autenticado con rol Facturador. Debe existir al menos un cliente y un producto activo en el sistema.',
                'resultado_esperado' => 'La factura se genera, se asigna número correlativo, se almacena en la BD y se ofrece descarga en PDF sin errores.',
                'proyecto_id'        => $p1->id,
                'creado_por'         => $tester1->id,
            ]
        );

        $cp2 = CasoPrueba::updateOrCreate(
            ['nombre' => 'CP-002: Validación de campos obligatorios en factura'],
            [
                'descripcion'        => 'Verificar que el sistema impide guardar una factura si faltan campos requeridos.',
                'condiciones'        => 'Usuario autenticado. Formulario de nueva factura abierto.',
                'resultado_esperado' => 'El sistema muestra mensajes de error específicos por cada campo faltante y no guarda el registro.',
                'proyecto_id'        => $p1->id,
                'creado_por'         => $tester1->id,
            ]
        );

        $cp3 = CasoPrueba::updateOrCreate(
            ['nombre' => 'CP-003: Cálculo de IVA con descuento aplicado'],
            [
                'descripcion'        => 'Verificar que el IVA se calcula correctamente sobre el subtotal neto (después del descuento).',
                'condiciones'        => 'Factura con al menos 2 ítems. Porcentaje de descuento: 15%. IVA configurado al 13%.',
                'resultado_esperado' => 'IVA = (suma de ítems × (1 - 0.15)) × 0.13. Total = subtotal bruto - descuento + IVA.',
                'proyecto_id'        => $p1->id,
                'creado_por'         => $tester2->id,
            ]
        );

        // Proyecto 2

        $cp4 = CasoPrueba::updateOrCreate(
            ['nombre' => 'CP-004: Expiración de sesión por inactividad'],
            [
                'descripcion'        => 'Verificar que la sesión del portal se invalida tras el tiempo de inactividad configurado.',
                'condiciones'        => 'SESSION_LIFETIME = 30 minutos. Usuario autenticado en el portal.',
                'resultado_esperado' => 'Tras 30 min de inactividad, cualquier request redirige al login con mensaje "Sesión expirada".',
                'proyecto_id'        => $p2->id,
                'creado_por'         => $tester2->id,
            ]
        );

        $cp5 = CasoPrueba::updateOrCreate(
            ['nombre' => 'CP-005: Descarga de estado de cuenta histórico'],
            [
                'descripcion'        => 'Verificar que usuarios con contratos anteriores a 2022 pueden descargar su estado de cuenta.',
                'condiciones'        => 'Cuenta de prueba con contrato creado en 2020. Rango de fechas solicitado: 2020-01-01 a 2021-12-31.',
                'resultado_esperado' => 'PDF descargado correctamente con movimientos del período seleccionado.',
                'proyecto_id'        => $p2->id,
                'creado_por'         => $tester2->id,
            ]
        );

        // Proyecto 3

        $cp6 = CasoPrueba::updateOrCreate(
            ['nombre' => 'CP-006: Escaneo de código de barras en condiciones normales'],
            [
                'descripcion'        => 'Verificar que el escáner de códigos de barras funciona correctamente en modo claro.',
                'condiciones'        => 'Dispositivo Android 11+, modo claro activado, permiso de cámara concedido. Producto con código EAN-13 disponible.',
                'resultado_esperado' => 'Al enfocar el código, la app lo detecta en menos de 2 segundos y muestra la ficha del producto.',
                'proyecto_id'        => $p3->id,
                'creado_por'         => $tester1->id,
            ]
        );

        $cp7 = CasoPrueba::updateOrCreate(
            ['nombre' => 'CP-007: Registro de salida de stock sin superar disponible'],
            [
                'descripcion'        => 'Verificar que el sistema valida correctamente que la cantidad de salida no supere el stock disponible.',
                'condiciones'        => 'Producto "Caja de pernos M6" con stock = 50 unidades.',
                'resultado_esperado' => 'Salidas menores o iguales a 50 se procesan. Salidas superiores muestran error de stock insuficiente.',
                'proyecto_id'        => $p3->id,
                'creado_por'         => $tester2->id,
            ]
        );

        // ─── 8. PASOS DE PRUEBA ──────────────────────────────────────────────────
        $this->crearPasos($cp1->id, [
            'Iniciar sesión con usuario Facturador.',
            'Navegar a Facturación → Nueva Factura.',
            'Seleccionar cliente "Empresa Demo S.A." en el campo de búsqueda.',
            'Agregar producto "Servicio de Consultoría" con cantidad 2 y precio unitario 500.00.',
            'Seleccionar condición de pago: Contado.',
            'Hacer clic en el botón "Generar Factura".',
            'Verificar que aparece mensaje "Factura #XXXX generada exitosamente".',
            'Hacer clic en "Descargar PDF" y confirmar que el archivo se abre correctamente.',
        ]);

        $this->crearPasos($cp2->id, [
            'Navegar a Facturación → Nueva Factura sin seleccionar ningún campo.',
            'Hacer clic directamente en "Generar Factura".',
            'Observar los mensajes de validación que aparecen en pantalla.',
            'Ingresar solo el cliente y dejar los ítems vacíos. Intentar guardar nuevamente.',
            'Verificar que el formulario persiste con los datos ingresados y no redirige.',
        ]);

        $this->crearPasos($cp3->id, [
            'Crear nueva factura con 2 ítems: Ítem A = 300.00, Ítem B = 200.00. Subtotal bruto = 500.00.',
            'Aplicar descuento del 15%: Descuento = 75.00. Subtotal neto = 425.00.',
            'Verificar que el IVA calculado = 425.00 × 0.13 = 55.25.',
            'Verificar que el Total = 425.00 + 55.25 = 480.25.',
            'Guardar la factura y abrir el PDF generado.',
            'Confirmar que los montos en el PDF coinciden con los mostrados en pantalla.',
        ]);

        $this->crearPasos($cp4->id, [
            'Iniciar sesión en el portal con usuario de prueba.',
            'No realizar ninguna acción por 35 minutos (esperar inactividad).',
            'Intentar navegar a "Mi Cuenta → Estado de Cuenta".',
            'Verificar que el sistema redirige al login.',
            'Confirmar que aparece el mensaje "Tu sesión ha expirado. Inicia sesión nuevamente."',
        ]);

        $this->crearPasos($cp5->id, [
            'Iniciar sesión con cuenta de cliente con contrato del año 2020.',
            'Ir a Mi Cuenta → Estado de Cuenta.',
            'Seleccionar fecha inicio: 2020-01-01 y fecha fin: 2021-12-31.',
            'Hacer clic en "Descargar Estado de Cuenta".',
            'Verificar que se descarga un PDF con los movimientos del período.',
            'Abrir el PDF y confirmar que el formato es correcto y los datos corresponden a la cuenta.',
        ]);

        $this->crearPasos($cp6->id, [
            'Verificar que el modo oscuro está DESACTIVADO en el dispositivo.',
            'Abrir la app de inventario e iniciar sesión.',
            'Ir a Inventario → Escanear producto.',
            'Otorgar permiso de cámara si se solicita.',
            'Apuntar la cámara a un código de barras EAN-13 de un producto existente.',
            'Verificar que la detección ocurre en menos de 2 segundos.',
            'Confirmar que la ficha del producto mostrada coincide con el código escaneado.',
        ]);

        $this->crearPasos($cp7->id, [
            'Ir a Inventario → Productos y localizar "Caja de pernos M6" (stock: 50).',
            'Registrar salida de 20 unidades. Confirmar que el stock queda en 30.',
            'Registrar salida de 30 unidades adicionales. Confirmar que el stock llega a 0.',
            'Intentar registrar salida de 5 unidades con stock = 0.',
            'Verificar que aparece mensaje de error "Stock insuficiente. Disponible: 0".',
            'Confirmar que el stock permanece en 0 y no se registra la salida inválida.',
        ]);

        // ─── 9. EJECUCIONES DE PRUEBA ────────────────────────────────────────────

        // CP-001 — OK
        $ej1 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp1->id,
            'ejecutado_por'   => $tester1->id,
            'resultado'       => 'OK',
            'observaciones'   => 'Todos los pasos ejecutados sin problemas. PDF generado correctamente incluyendo el número correlativo.',
            'fecha_ejecucion' => now()->subDays(9),
        ]);

        // CP-002 — OK
        $ej2 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp2->id,
            'ejecutado_por'   => $tester1->id,
            'resultado'       => 'OK',
            'observaciones'   => 'Las validaciones funcionan correctamente para todos los campos obligatorios.',
            'fecha_ejecucion' => now()->subDays(9),
        ]);

        // CP-003 — FAIL (bug del IVA con descuento)
        $ej3 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp3->id,
            'ejecutado_por'   => $tester1->id,
            'resultado'       => 'FAIL',
            'observaciones'   => 'El IVA se calculó sobre el subtotal bruto (500 × 0.13 = 65.00) en lugar del neto (425 × 0.13 = 55.25). Diferencia de 9.75 en el monto final.',
            'fecha_ejecucion' => now()->subDays(11),
        ]);
        $ej3->bugs()->sync([$bug2->id]);

        // CP-003 segunda ejecución — aún FAIL
        $ej4 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp3->id,
            'ejecutado_por'   => $tester2->id,
            'resultado'       => 'FAIL',
            'observaciones'   => 'Se confirma el fallo. Probado con distintos porcentajes de descuento (10%, 20%, 25%): en todos los casos el IVA ignora el descuento.',
            'fecha_ejecucion' => now()->subDays(7),
        ]);
        $ej4->bugs()->sync([$bug2->id]);

        // CP-004 — FAIL (sesión no expira)
        $ej5 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp4->id,
            'ejecutado_por'   => $tester2->id,
            'resultado'       => 'FAIL',
            'observaciones'   => 'Después de 40 minutos de inactividad, el sistema permitió navegar libremente sin exigir reautenticación.',
            'fecha_ejecucion' => now()->subDays(7),
        ]);
        $ej5->bugs()->sync([$bug4->id]);

        // CP-005 — OK (bug ya cerrado)
        $ej6 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp5->id,
            'ejecutado_por'   => $tester2->id,
            'resultado'       => 'OK',
            'observaciones'   => 'Descarga exitosa. El PDF contiene 24 movimientos del período 2020-2021. Formato y datos correctos.',
            'fecha_ejecucion' => now()->subDays(6),
        ]);

        // CP-006 — OK (modo claro)
        $ej7 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp6->id,
            'ejecutado_por'   => $tester1->id,
            'resultado'       => 'OK',
            'observaciones'   => 'Escaneo funciona en modo claro. Tiempo de detección promedio: 1.2 segundos. Probado con 5 productos distintos.',
            'fecha_ejecucion' => now()->subDays(5),
        ]);

        // CP-006 segunda ejecución — FAIL (modo oscuro → bug conocido)
        $ej8 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp6->id,
            'ejecutado_por'   => $tester1->id,
            'resultado'       => 'FAIL',
            'observaciones'   => 'Con modo oscuro activado la app hace crash inmediatamente al abrir el escáner. Crash reproducible al 100% en Pixel 6 y Samsung A52.',
            'fecha_ejecucion' => now()->subDays(4),
        ]);
        $ej8->bugs()->sync([$bug7->id]);

        // CP-007 — FAIL (stock negativo)
        $ej9 = EjecucionPrueba::create([
            'caso_prueba_id'  => $cp7->id,
            'ejecutado_por'   => $tester2->id,
            'resultado'       => 'FAIL',
            'observaciones'   => 'Paso 4 fallido: al intentar salida de 5 unidades con stock 0, el sistema aceptó la operación y el stock quedó en -5. No se mostró mensaje de error.',
            'fecha_ejecucion' => now()->subDays(2),
        ]);
        $ej9->bugs()->sync([$bug8->id]);

        $this->command->info('✔ DemoSeeder ejecutado exitosamente.');
        $this->command->table(
            ['Entidad', 'Registros creados'],
            [
                ['Usuarios',            6],
                ['Proyectos',           3],
                ['Bugs',                9],
                ['Historial bugs',      12],
                ['Casos de prueba',     7],
                ['Ejecuciones',         9],
                ['Bugs vinculados',     '5 vínculos en ejecucion_bug'],
            ]
        );
        $this->command->info('');
        $this->command->info('Credenciales de acceso:');
        $this->command->table(
            ['Email', 'Contraseña', 'Rol'],
            [
                ['admin@calidad.com',         '12345678', 'Administrador'],
                ['sofia.tester@calidad.com',  '12345678', 'Tester'],
                ['carlos.tester@calidad.com', '12345678', 'Tester'],
                ['luis.dev@calidad.com',       '12345678', 'Desarrollador'],
                ['ana.dev@calidad.com',        '12345678', 'Desarrollador'],
                ['pedro.dev@calidad.com',      '12345678', 'Desarrollador'],
            ]
        );
    }

    private function crearPasos(int $casoPruebaId, array $descripciones): void
    {
        PasoPrueba::where('caso_prueba_id', $casoPruebaId)->delete();

        foreach ($descripciones as $i => $desc) {
            PasoPrueba::create([
                'caso_prueba_id' => $casoPruebaId,
                'orden'          => $i + 1,
                'descripcion'    => $desc,
            ]);
        }
    }
}
