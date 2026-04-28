<?php

namespace Database\Seeders;

use App\Models\Bug;
use App\Models\CasoPrueba;
use App\Models\EjecucionPrueba;
use App\Models\EvaluacionCalidad;
use App\Models\HistorialBug;
use App\Models\MetricaProyecto;
use App\Models\ModuloProyecto;
use App\Models\Proyecto;
use App\Models\Recomendacion;
use App\Models\Rol;
use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ─── USUARIOS ─────────────────────────────────────────────────────────
        $rolAdmin  = Rol::where('nombre', 'Administrador')->first();
        $rolTester = Rol::where('nombre', 'Tester')->first();
        $rolDev    = Rol::where('nombre', 'Desarrollador')->first();

        $admin = Usuario::updateOrCreate(
            ['email' => 'admin@calidad.com'],
            ['nombre' => 'Admin', 'apellido' => 'Sistema', 'password' => Hash::make('12345678'), 'rol_id' => $rolAdmin->id, 'estado' => 'activo']
        );

        $sofia = Usuario::updateOrCreate(
            ['email' => 'sofia.tester@calidad.com'],
            ['nombre' => 'Sofía', 'apellido' => 'Ramírez', 'password' => Hash::make('12345678'), 'rol_id' => $rolTester->id, 'estado' => 'activo']
        );

        $carlos = Usuario::updateOrCreate(
            ['email' => 'carlos.tester@calidad.com'],
            ['nombre' => 'Carlos', 'apellido' => 'Mendoza', 'password' => Hash::make('12345678'), 'rol_id' => $rolTester->id, 'estado' => 'activo']
        );

        $luis = Usuario::updateOrCreate(
            ['email' => 'luis.dev@calidad.com'],
            ['nombre' => 'Luis', 'apellido' => 'Torres', 'password' => Hash::make('12345678'), 'rol_id' => $rolDev->id, 'estado' => 'activo']
        );

        $ana = Usuario::updateOrCreate(
            ['email' => 'ana.dev@calidad.com'],
            ['nombre' => 'Ana', 'apellido' => 'González', 'password' => Hash::make('12345678'), 'rol_id' => $rolDev->id, 'estado' => 'activo']
        );

        $pedro = Usuario::updateOrCreate(
            ['email' => 'pedro.dev@calidad.com'],
            ['nombre' => 'Pedro', 'apellido' => 'Vargas', 'password' => Hash::make('12345678'), 'rol_id' => $rolDev->id, 'estado' => 'activo']
        );

        // ─── PROYECTOS ────────────────────────────────────────────────────────
        $p1 = Proyecto::create([
            'nombre'      => 'Sistema de Inventario',
            'descripcion' => 'Sistema de control de inventario y almacén para empresa distribuidora.',
            'estado'      => 'activo',
            'created_by'  => $admin->id,
        ]);

        $p2 = Proyecto::create([
            'nombre'      => 'Portal Web Corporativo',
            'descripcion' => 'Portal web institucional con panel administrativo y API REST.',
            'estado'      => 'activo',
            'created_by'  => $admin->id,
        ]);

        $p3 = Proyecto::create([
            'nombre'      => 'App Móvil de Ventas',
            'descripcion' => 'Aplicación móvil para gestión de ventas y catálogo de productos.',
            'estado'      => 'finalizado',
            'created_by'  => $admin->id,
        ]);

        // ─── MIEMBROS ─────────────────────────────────────────────────────────
        $p1->miembros()->syncWithoutDetaching([
            $sofia->id  => ['fecha_asignacion' => now()->subDays(30)->toDateString()],
            $luis->id   => ['fecha_asignacion' => now()->subDays(30)->toDateString()],
            $ana->id    => ['fecha_asignacion' => now()->subDays(28)->toDateString()],
        ]);

        $p2->miembros()->syncWithoutDetaching([
            $carlos->id => ['fecha_asignacion' => now()->subDays(20)->toDateString()],
            $pedro->id  => ['fecha_asignacion' => now()->subDays(20)->toDateString()],
            $ana->id    => ['fecha_asignacion' => now()->subDays(18)->toDateString()],
        ]);

        $p3->miembros()->syncWithoutDetaching([
            $sofia->id  => ['fecha_asignacion' => now()->subDays(60)->toDateString()],
            $carlos->id => ['fecha_asignacion' => now()->subDays(60)->toDateString()],
            $luis->id   => ['fecha_asignacion' => now()->subDays(55)->toDateString()],
            $pedro->id  => ['fecha_asignacion' => now()->subDays(55)->toDateString()],
        ]);

        // ─── MÓDULOS ──────────────────────────────────────────────────────────
        // Proyecto 1
        $m1auth  = ModuloProyecto::create(['proyecto_id' => $p1->id, 'nombre' => 'Autenticación',        'descripcion' => 'Login, roles y permisos.',              'estado' => 'activo']);
        $m1cat   = ModuloProyecto::create(['proyecto_id' => $p1->id, 'nombre' => 'Catálogo de Productos', 'descripcion' => 'CRUD de productos e inventario.',         'estado' => 'activo']);
        $m1rep   = ModuloProyecto::create(['proyecto_id' => $p1->id, 'nombre' => 'Reportes',              'descripcion' => 'Generación de reportes PDF y Excel.',     'estado' => 'activo']);

        // Proyecto 2
        $m2login = ModuloProyecto::create(['proyecto_id' => $p2->id, 'nombre' => 'Login y Sesiones',      'descripcion' => 'Autenticación y gestión de sesiones.',    'estado' => 'activo']);
        $m2admin = ModuloProyecto::create(['proyecto_id' => $p2->id, 'nombre' => 'Panel Administrativo',  'descripcion' => 'Dashboard y gestión de contenidos.',      'estado' => 'activo']);
        $m2api   = ModuloProyecto::create(['proyecto_id' => $p2->id, 'nombre' => 'API REST',              'descripcion' => 'Endpoints públicos y privados.',           'estado' => 'activo']);

        // Proyecto 3
        $m3pagos = ModuloProyecto::create(['proyecto_id' => $p3->id, 'nombre' => 'Módulo de Pagos',       'descripcion' => 'Integración con pasarelas de pago.',      'estado' => 'activo']);
        $m3cat   = ModuloProyecto::create(['proyecto_id' => $p3->id, 'nombre' => 'Catálogo Móvil',        'descripcion' => 'Listado y búsqueda de productos.',         'estado' => 'activo']);
        $m3carr  = ModuloProyecto::create(['proyecto_id' => $p3->id, 'nombre' => 'Carrito de Compras',    'descripcion' => 'Gestión del carrito y checkout.',          'estado' => 'activo']);

        // ─── TAREAS ───────────────────────────────────────────────────────────
        $t1 = Tarea::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1auth->id,
            'titulo'       => 'Implementar recuperación de contraseña',
            'descripcion'  => 'Agregar flujo de recuperación de contraseña por email con enlace de un solo uso.',
            'responsable_id' => $luis->id, 'estado' => 'en_progreso', 'prioridad' => 'alta',
            'fecha_inicio' => now()->subDays(10)->toDateString(), 'fecha_fin' => now()->addDays(5)->toDateString(),
            'created_by'   => $admin->id,
        ]);

        $t2 = Tarea::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1cat->id,
            'titulo'       => 'Paginación en listado de productos',
            'descripcion'  => 'El listado debe paginar de a 20 ítems y respetar filtros activos.',
            'responsable_id' => $ana->id, 'estado' => 'completada', 'prioridad' => 'media',
            'fecha_inicio' => now()->subDays(20)->toDateString(), 'fecha_fin' => now()->subDays(5)->toDateString(),
            'created_by'   => $admin->id,
        ]);

        $t3 = Tarea::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1rep->id,
            'titulo'       => 'Reporte mensual de stock crítico',
            'descripcion'  => 'Generar PDF con productos por debajo del stock mínimo configurado.',
            'responsable_id' => $luis->id, 'estado' => 'pendiente', 'prioridad' => 'media',
            'fecha_inicio' => now()->addDays(2)->toDateString(), 'fecha_fin' => now()->addDays(15)->toDateString(),
            'created_by'   => $admin->id,
        ]);

        $t4 = Tarea::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2api->id,
            'titulo'       => 'Autenticación JWT en la API',
            'descripcion'  => 'Proteger todos los endpoints con tokens JWT con expiración correcta.',
            'responsable_id' => $pedro->id, 'estado' => 'en_progreso', 'prioridad' => 'alta',
            'fecha_inicio' => now()->subDays(7)->toDateString(), 'fecha_fin' => now()->addDays(3)->toDateString(),
            'created_by'   => $admin->id,
        ]);

        $t5 = Tarea::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2admin->id,
            'titulo'       => 'Diseño responsivo del dashboard',
            'descripcion'  => 'Crear dashboard con gráficos y sidebar adaptable a móviles.',
            'responsable_id' => $ana->id, 'estado' => 'pendiente', 'prioridad' => 'baja',
            'fecha_inicio' => now()->addDays(5)->toDateString(), 'fecha_fin' => now()->addDays(20)->toDateString(),
            'created_by'   => $admin->id,
        ]);

        // ─── BUGS ─────────────────────────────────────────────────────────────
        // Proyecto 1
        $b1 = Bug::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1auth->id, 'tarea_id' => $t1->id,
            'titulo'             => 'Error 500 al recuperar contraseña con email inválido',
            'descripcion'        => 'Al ingresar un email no registrado en el formulario de recuperación, el sistema lanza un error 500 en lugar de mostrar un mensaje amigable.',
            'pasos_reproducir'   => "1. Ir a /recuperar-password\n2. Ingresar email inexistente\n3. Hacer clic en Enviar",
            'resultado_esperado' => 'Mensaje: "Email no registrado en el sistema."',
            'resultado_actual'   => 'Error 500 - Internal Server Error',
            'severidad' => 'alta', 'estado' => 'en_proceso',
            'reportado_por' => $sofia->id, 'asignado_a' => $luis->id,
            'fecha_reporte' => now()->subDays(8),
        ]);

        $b2 = Bug::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1cat->id, 'tarea_id' => $t2->id,
            'titulo'             => 'La paginación no respeta filtros activos',
            'descripcion'        => 'Al paginar con filtros aplicados, la página 2 ignora los filtros y muestra todos los productos sin filtrar.',
            'pasos_reproducir'   => "1. Filtrar productos por categoría\n2. Ir a la página 2",
            'resultado_esperado' => 'La página 2 muestra solo productos de la categoría filtrada.',
            'resultado_actual'   => 'La página 2 muestra todos los productos sin filtro.',
            'severidad' => 'media', 'estado' => 'cerrado',
            'reportado_por' => $sofia->id, 'asignado_a' => $ana->id,
            'fecha_reporte' => now()->subDays(15), 'fecha_resolucion' => now()->subDays(5),
            'tiempo_resolucion_horas' => 24.5,
        ]);

        $b3 = Bug::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1cat->id,
            'titulo'             => 'Campo "stock mínimo" acepta valores negativos',
            'descripcion'        => 'El formulario de creación de producto no valida que el stock mínimo sea mayor o igual a 0.',
            'pasos_reproducir'   => "1. Ir a Catálogo > Nuevo Producto\n2. Ingresar -5 en stock mínimo\n3. Guardar",
            'resultado_esperado' => 'Error de validación: "El stock mínimo debe ser mayor o igual a 0."',
            'resultado_actual'   => 'Producto guardado con stock mínimo negativo sin error.',
            'severidad' => 'media', 'estado' => 'abierto',
            'reportado_por' => $carlos->id, 'asignado_a' => $ana->id,
            'fecha_reporte' => now()->subDays(9),
        ]);

        $b4 = Bug::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1rep->id,
            'titulo'             => 'Reporte PDF genera páginas en blanco al final',
            'descripcion'        => 'El PDF generado de inventario incluye 3 páginas vacías al final del documento.',
            'pasos_reproducir'   => "1. Ir a Reportes\n2. Generar reporte de inventario\n3. Descargar y abrir el PDF",
            'resultado_esperado' => 'PDF sin páginas en blanco.',
            'resultado_actual'   => 'PDF con 3 páginas vacías al final.',
            'severidad' => 'baja', 'estado' => 'abierto',
            'reportado_por' => $carlos->id, 'asignado_a' => null,
            'fecha_reporte' => now()->subDays(3),
        ]);

        // Proyecto 2
        $b5 = Bug::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2api->id, 'tarea_id' => $t4->id,
            'titulo'             => 'Token JWT no expira correctamente',
            'descripcion'        => 'El token JWT sigue siendo válido después del tiempo de expiración configurado, representando una vulnerabilidad de seguridad.',
            'pasos_reproducir'   => "1. Autenticarse en la API (POST /api/login)\n2. Esperar más de 1 hora\n3. Usar el mismo token en GET /api/perfil",
            'resultado_esperado' => 'Respuesta 401 Unauthorized con mensaje de token expirado.',
            'resultado_actual'   => 'Respuesta 200, el token sigue siendo válido indefinidamente.',
            'severidad' => 'critica', 'estado' => 'en_proceso',
            'reportado_por' => $carlos->id, 'asignado_a' => $pedro->id,
            'fecha_reporte' => now()->subDays(5),
        ]);

        $b6 = Bug::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2admin->id,
            'titulo'             => 'Sidebar no colapsa en pantallas móviles',
            'descripcion'        => 'En pantallas menores a 768px el sidebar permanece visible y cubre el contenido principal.',
            'pasos_reproducir'   => "1. Abrir el portal en un móvil o reducir ventana a menos de 768px\n2. Verificar el sidebar",
            'resultado_esperado' => 'Sidebar colapsable con botón de menú hamburguesa.',
            'resultado_actual'   => 'Sidebar siempre visible, cubre el contenido.',
            'severidad' => 'media', 'estado' => 'abierto',
            'reportado_por' => $sofia->id, 'asignado_a' => $ana->id,
            'fecha_reporte' => now()->subDays(2),
        ]);

        // Proyecto 3
        $b7 = Bug::create([
            'proyecto_id' => $p3->id, 'modulo_id' => $m3pagos->id,
            'titulo'             => 'Error al procesar pago con Visa 3DS',
            'descripcion'        => 'Tarjetas Visa con 3D Secure activo fallan al procesar el pago con el mensaje "Payment declined".',
            'pasos_reproducir'   => "1. Agregar producto al carrito\n2. Ir a checkout\n3. Ingresar tarjeta Visa con 3DS activo\n4. Confirmar pago",
            'resultado_esperado' => 'Pago procesado y orden generada.',
            'resultado_actual'   => '"Payment declined" sin detalles del error.',
            'severidad' => 'critica', 'estado' => 'cerrado',
            'reportado_por' => $carlos->id, 'asignado_a' => $pedro->id,
            'fecha_reporte' => now()->subDays(45), 'fecha_resolucion' => now()->subDays(30),
            'tiempo_resolucion_horas' => 72.0,
        ]);

        $b8 = Bug::create([
            'proyecto_id' => $p3->id, 'modulo_id' => $m3carr->id,
            'titulo'             => 'Contador del carrito no se actualiza en tiempo real',
            'descripcion'        => 'Al agregar productos, el ícono del carrito no refleja la cantidad actualizada hasta que se recarga la página.',
            'pasos_reproducir'   => "1. Ir al catálogo\n2. Hacer clic en Agregar al carrito\n3. Verificar el ícono del carrito",
            'resultado_esperado' => 'El contador incrementa inmediatamente.',
            'resultado_actual'   => 'El contador siempre muestra 0 hasta recargar.',
            'severidad' => 'media', 'estado' => 'cerrado',
            'reportado_por' => $sofia->id, 'asignado_a' => $luis->id,
            'fecha_reporte' => now()->subDays(40), 'fecha_resolucion' => now()->subDays(35),
            'tiempo_resolucion_horas' => 8.0,
        ]);

        // ─── HISTORIAL DE BUGS ────────────────────────────────────────────────
        HistorialBug::create(['bug_id' => $b1->id, 'usuario_id' => $sofia->id,  'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Bug reportado durante pruebas.',                                         'fecha_cambio' => now()->subDays(8)]);
        HistorialBug::create(['bug_id' => $b1->id, 'usuario_id' => $admin->id,  'estado_anterior' => 'abierto',   'estado_nuevo' => 'en_proceso', 'comentario' => 'Asignado a Luis Torres para corrección urgente.',                         'fecha_cambio' => now()->subDays(7)]);

        HistorialBug::create(['bug_id' => $b2->id, 'usuario_id' => $sofia->id,  'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Detectado en revisión de paginación.',                                   'fecha_cambio' => now()->subDays(15)]);
        HistorialBug::create(['bug_id' => $b2->id, 'usuario_id' => $admin->id,  'estado_anterior' => 'abierto',   'estado_nuevo' => 'en_proceso', 'comentario' => 'Asignado a Ana González.',                                               'fecha_cambio' => now()->subDays(14)]);
        HistorialBug::create(['bug_id' => $b2->id, 'usuario_id' => $ana->id,    'estado_anterior' => 'en_proceso','estado_nuevo' => 'cerrado',    'comentario' => 'Corregido: se agrega query string a todos los links de paginación.',     'fecha_cambio' => now()->subDays(5)]);

        HistorialBug::create(['bug_id' => $b3->id, 'usuario_id' => $carlos->id, 'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Detectado durante ejecución de prueba CP-003.',                          'fecha_cambio' => now()->subDays(9)]);

        HistorialBug::create(['bug_id' => $b4->id, 'usuario_id' => $carlos->id, 'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Detectado al generar reporte de prueba.',                                'fecha_cambio' => now()->subDays(3)]);

        HistorialBug::create(['bug_id' => $b5->id, 'usuario_id' => $carlos->id, 'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Vulnerabilidad de seguridad detectada en pruebas de la API.',           'fecha_cambio' => now()->subDays(5)]);
        HistorialBug::create(['bug_id' => $b5->id, 'usuario_id' => $admin->id,  'estado_anterior' => 'abierto',   'estado_nuevo' => 'en_proceso', 'comentario' => 'Prioridad crítica. Asignado a Pedro Vargas.',                            'fecha_cambio' => now()->subDays(4)]);

        HistorialBug::create(['bug_id' => $b6->id, 'usuario_id' => $sofia->id,  'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Detectado en revisión de responsividad.',                                'fecha_cambio' => now()->subDays(2)]);

        HistorialBug::create(['bug_id' => $b7->id, 'usuario_id' => $carlos->id, 'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Reportado por cliente en producción.',                                   'fecha_cambio' => now()->subDays(45)]);
        HistorialBug::create(['bug_id' => $b7->id, 'usuario_id' => $admin->id,  'estado_anterior' => 'abierto',   'estado_nuevo' => 'en_proceso', 'comentario' => 'Investigando con el equipo de integraciones.',                           'fecha_cambio' => now()->subDays(44)]);
        HistorialBug::create(['bug_id' => $b7->id, 'usuario_id' => $pedro->id,  'estado_anterior' => 'en_proceso','estado_nuevo' => 'cerrado',    'comentario' => 'Actualizada librería de pagos y configurado correctamente 3DS v2.',     'fecha_cambio' => now()->subDays(30)]);

        HistorialBug::create(['bug_id' => $b8->id, 'usuario_id' => $sofia->id,  'estado_anterior' => null,        'estado_nuevo' => 'abierto',    'comentario' => 'Bug visual detectado en pruebas.',                                       'fecha_cambio' => now()->subDays(40)]);
        HistorialBug::create(['bug_id' => $b8->id, 'usuario_id' => $luis->id,   'estado_anterior' => 'abierto',   'estado_nuevo' => 'cerrado',    'comentario' => 'Corregida la reactividad del store del carrito.',                        'fecha_cambio' => now()->subDays(35)]);

        // ─── CASOS DE PRUEBA ──────────────────────────────────────────────────
        $cp1 = CasoPrueba::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1auth->id,
            'titulo'             => 'CP-001: Login con credenciales válidas',
            'descripcion'        => 'Verificar que el usuario se autentica correctamente.',
            'precondiciones'     => 'Usuario registrado y en estado activo.',
            'pasos'              => "1. Ir a /login\n2. Ingresar email válido\n3. Ingresar contraseña correcta\n4. Clic en Iniciar sesión",
            'resultado_esperado' => 'Redirige al dashboard con sesión activa.',
            'creado_por' => $sofia->id, 'estado' => 'activo',
        ]);

        $cp2 = CasoPrueba::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1auth->id,
            'titulo'             => 'CP-002: Login con contraseña incorrecta',
            'descripcion'        => 'Verificar que el sistema rechaza credenciales inválidas.',
            'precondiciones'     => 'Usuario registrado.',
            'pasos'              => "1. Ir a /login\n2. Ingresar email válido\n3. Ingresar contraseña incorrecta\n4. Clic en Iniciar sesión",
            'resultado_esperado' => 'Muestra error "Credenciales incorrectas" sin redirigir.',
            'creado_por' => $sofia->id, 'estado' => 'activo',
        ]);

        $cp3 = CasoPrueba::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1cat->id,
            'titulo'             => 'CP-003: Agregar producto con stock mínimo',
            'descripcion'        => 'Verificar validación del campo stock mínimo al crear producto.',
            'precondiciones'     => 'Usuario Administrador autenticado.',
            'pasos'              => "1. Ir a Catálogo > Nuevo Producto\n2. Completar todos los campos\n3. Ingresar -5 en stock mínimo\n4. Guardar",
            'resultado_esperado' => 'Error de validación: "El stock mínimo debe ser mayor o igual a 0."',
            'creado_por' => $carlos->id, 'estado' => 'activo',
        ]);

        $cp4 = CasoPrueba::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2api->id,
            'titulo'             => 'CP-004: API acepta token JWT válido',
            'descripcion'        => 'Verificar que los endpoints protegidos aceptan tokens vigentes.',
            'precondiciones'     => 'API desplegada, usuario registrado.',
            'pasos'              => "1. POST /api/login con credenciales válidas\n2. Copiar el token de la respuesta\n3. GET /api/perfil con header Authorization: Bearer <token>",
            'resultado_esperado' => 'Respuesta 200 con datos del perfil.',
            'creado_por' => $carlos->id, 'estado' => 'activo',
        ]);

        $cp5 = CasoPrueba::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2api->id,
            'titulo'             => 'CP-005: API rechaza token JWT expirado',
            'descripcion'        => 'Verificar que el sistema rechaza tokens vencidos.',
            'precondiciones'     => 'API desplegada, token expirado disponible.',
            'pasos'              => "1. Obtener un token y esperar a que expire\n2. GET /api/perfil con el token expirado",
            'resultado_esperado' => 'Respuesta 401 Unauthorized.',
            'creado_por' => $carlos->id, 'estado' => 'activo',
        ]);

        $cp6 = CasoPrueba::create([
            'proyecto_id' => $p3->id, 'modulo_id' => $m3pagos->id,
            'titulo'             => 'CP-006: Pago exitoso con tarjeta Visa',
            'descripcion'        => 'Verificar el flujo completo de pago con Visa.',
            'precondiciones'     => 'Producto en carrito, entorno sandbox de pasarela activo.',
            'pasos'              => "1. Agregar producto al carrito\n2. Ir a checkout\n3. Ingresar tarjeta Visa de prueba (4242 4242 4242 4242)\n4. Confirmar pago",
            'resultado_esperado' => 'Pago aprobado, orden generada y email de confirmación enviado.',
            'creado_por' => $sofia->id, 'estado' => 'activo',
        ]);

        $cp7 = CasoPrueba::create([
            'proyecto_id' => $p3->id, 'modulo_id' => $m3carr->id,
            'titulo'             => 'CP-007: Contador del carrito se actualiza en tiempo real',
            'descripcion'        => 'Verificar que el ícono del carrito refleja la cantidad actual sin recargar.',
            'precondiciones'     => 'Carrito vacío.',
            'pasos'              => "1. Ir al catálogo\n2. Hacer clic en Agregar al carrito en cualquier producto\n3. Observar el ícono del carrito",
            'resultado_esperado' => 'El contador del carrito incrementa a 1 inmediatamente.',
            'creado_por' => $sofia->id, 'estado' => 'activo',
        ]);

        // ─── EJECUCIONES DE PRUEBA ────────────────────────────────────────────
        $e1 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp1->id, 'ejecutado_por' => $sofia->id,
            'fecha_ejecucion' => now()->subDays(10), 'resultado' => 'OK',
            'observaciones'   => 'Login funciona correctamente con credenciales válidas.',
        ]);

        $e2 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp2->id, 'ejecutado_por' => $sofia->id,
            'fecha_ejecucion' => now()->subDays(10), 'resultado' => 'OK',
            'observaciones'   => 'El sistema rechaza correctamente las credenciales inválidas.',
        ]);

        $e3 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp3->id, 'ejecutado_por' => $carlos->id,
            'fecha_ejecucion' => now()->subDays(9), 'resultado' => 'FAIL',
            'observaciones'   => 'El campo "stock mínimo" acepta -5 sin error de validación. Se registra bug.',
        ]);

        $e4 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp4->id, 'ejecutado_por' => $carlos->id,
            'fecha_ejecucion' => now()->subDays(4), 'resultado' => 'OK',
            'observaciones'   => 'El endpoint retorna 200 con datos correctos del perfil.',
        ]);

        $e5 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp5->id, 'ejecutado_por' => $carlos->id,
            'fecha_ejecucion' => now()->subDays(4), 'resultado' => 'FAIL',
            'observaciones'   => 'El token expirado sigue siendo aceptado. Vulnerabilidad de seguridad confirmada.',
        ]);

        $e6 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp6->id, 'ejecutado_por' => $sofia->id,
            'fecha_ejecucion' => now()->subDays(44), 'resultado' => 'FAIL',
            'observaciones'   => 'Pago con Visa 3DS falla con "Payment declined". Se vincula bug crítico.',
        ]);

        $e7 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp6->id, 'ejecutado_por' => $sofia->id,
            'fecha_ejecucion' => now()->subDays(28), 'resultado' => 'OK',
            'observaciones'   => 'Luego de la corrección, el pago Visa 3DS procesa correctamente.',
        ]);

        $e8 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp7->id, 'ejecutado_por' => $carlos->id,
            'fecha_ejecucion' => now()->subDays(39), 'resultado' => 'FAIL',
            'observaciones'   => 'El contador permanece en 0 al agregar productos.',
        ]);

        $e9 = EjecucionPrueba::create([
            'caso_prueba_id' => $cp7->id, 'ejecutado_por' => $carlos->id,
            'fecha_ejecucion' => now()->subDays(34), 'resultado' => 'OK',
            'observaciones'   => 'Luego del fix, el contador responde en tiempo real.',
        ]);

        // ─── VINCULAR EJECUCIONES FAIL CON BUGS ──────────────────────────────
        $e3->bugs()->attach($b3->id);  // CP-003 FAIL → stock mínimo negativo
        $e5->bugs()->attach($b5->id);  // CP-005 FAIL → JWT no expira
        $e6->bugs()->attach($b7->id);  // CP-006 FAIL → pago Visa 3DS
        $e8->bugs()->attach($b8->id);  // CP-007 FAIL → contador carrito

        // ─── EVALUACIONES DE CALIDAD ──────────────────────────────────────────
        EvaluacionCalidad::create([
            'proyecto_id' => $p1->id, 'evaluado_por' => $admin->id,
            'usabilidad' => 7.5, 'rendimiento' => 8.0, 'seguridad' => 6.5,
            'indice_calidad_global' => 7.33,
            'observaciones'   => 'Sistema funcional con detalles menores de UX. El módulo de autenticación requiere refuerzo en el manejo de errores.',
            'fecha_evaluacion' => now()->subDays(5),
        ]);

        EvaluacionCalidad::create([
            'proyecto_id' => $p2->id, 'evaluado_por' => $admin->id,
            'usabilidad' => 6.0, 'rendimiento' => 7.0, 'seguridad' => 4.5,
            'indice_calidad_global' => 5.83,
            'observaciones'   => 'Vulnerabilidad activa de seguridad en la API (JWT). Requiere corrección urgente antes de pasar a producción.',
            'fecha_evaluacion' => now()->subDays(3),
        ]);

        EvaluacionCalidad::create([
            'proyecto_id' => $p3->id, 'evaluado_por' => $admin->id,
            'usabilidad' => 8.5, 'rendimiento' => 9.0, 'seguridad' => 8.0,
            'indice_calidad_global' => 8.5,
            'observaciones'   => 'Proyecto finalizado con alta calidad. Todos los bugs críticos resueltos antes del release.',
            'fecha_evaluacion' => now()->subDays(20),
        ]);

        // ─── MÉTRICAS ─────────────────────────────────────────────────────────
        MetricaProyecto::create([
            'proyecto_id' => $p1->id, 'fecha_calculo' => now()->subDay(),
            'total_bugs' => 4, 'bugs_abiertos' => 2, 'bugs_en_proceso' => 1, 'bugs_cerrados' => 1,
            'total_pruebas' => 3, 'pruebas_ok' => 2, 'pruebas_fail' => 1,
            'tasa_exito_pruebas' => 66.67, 'tiempo_promedio_resolucion' => 24.5, 'densidad_defectos' => 1.33,
        ]);

        MetricaProyecto::create([
            'proyecto_id' => $p2->id, 'fecha_calculo' => now()->subDay(),
            'total_bugs' => 2, 'bugs_abiertos' => 1, 'bugs_en_proceso' => 1, 'bugs_cerrados' => 0,
            'total_pruebas' => 2, 'pruebas_ok' => 1, 'pruebas_fail' => 1,
            'tasa_exito_pruebas' => 50.0, 'tiempo_promedio_resolucion' => 0.0, 'densidad_defectos' => 0.67,
        ]);

        MetricaProyecto::create([
            'proyecto_id' => $p3->id, 'fecha_calculo' => now()->subDays(20),
            'total_bugs' => 2, 'bugs_abiertos' => 0, 'bugs_en_proceso' => 0, 'bugs_cerrados' => 2,
            'total_pruebas' => 4, 'pruebas_ok' => 2, 'pruebas_fail' => 2,
            'tasa_exito_pruebas' => 50.0, 'tiempo_promedio_resolucion' => 40.0, 'densidad_defectos' => 0.67,
        ]);

        // ─── RECOMENDACIONES ──────────────────────────────────────────────────
        Recomendacion::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1auth->id,
            'tipo' => 'seguridad',
            'descripcion' => 'Implementar manejo robusto de errores en el flujo de recuperación de contraseña para evitar exposición de errores 500.',
            'prioridad' => 'alta', 'generado_por_sistema' => true, 'estado' => 'pendiente',
        ]);

        Recomendacion::create([
            'proyecto_id' => $p1->id, 'modulo_id' => $m1cat->id,
            'tipo' => 'validacion',
            'descripcion' => 'Agregar validación server-side en formularios de productos: stock mínimo debe ser >= 0.',
            'prioridad' => 'media', 'generado_por_sistema' => true, 'estado' => 'pendiente',
        ]);

        Recomendacion::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2api->id,
            'tipo' => 'seguridad',
            'descripcion' => 'Corregir con urgencia la configuración de expiración de tokens JWT. Revisar el parámetro TTL en el proveedor de autenticación.',
            'prioridad' => 'alta', 'generado_por_sistema' => true, 'estado' => 'en_revision',
        ]);

        Recomendacion::create([
            'proyecto_id' => $p2->id, 'modulo_id' => $m2admin->id,
            'tipo' => 'usabilidad',
            'descripcion' => 'Implementar diseño responsivo en el sidebar del panel administrativo para mejorar la experiencia en dispositivos móviles.',
            'prioridad' => 'media', 'generado_por_sistema' => false, 'estado' => 'pendiente',
        ]);

        Recomendacion::create([
            'proyecto_id' => $p3->id, 'modulo_id' => null,
            'tipo' => 'proceso',
            'descripcion' => 'Proyecto finalizado exitosamente. Se recomienda documentar las lecciones aprendidas sobre integración con pasarelas de pago para futuros proyectos.',
            'prioridad' => 'baja', 'generado_por_sistema' => false, 'estado' => 'implementada',
        ]);
    }
}
