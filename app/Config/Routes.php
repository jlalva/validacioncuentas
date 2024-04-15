<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Index');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
//Login e Inicio
$routes->get('/', 'Index::index');
$routes->post('/login', 'Index::login');
$routes->get('/recuperar', 'Index::recuperar');
$routes->post('/validarcorreo', 'Index::validarcorreo');
$routes->post('/validarcodigo', 'Index::validarcodigo');
$routes->post('/actualizarclave', 'Index::actualizarclave');
$routes->get('/salir', 'Index::salir');
$routes->get('inicio', 'Inicio::index');
$routes->post('inicio/fijarperiodo', 'Inicio::fijarperiodo');

//Módulo seguridad
//Roles
$routes->get('roles', 'Roles::index');
$routes->get('roles/add', 'Roles::add');
$routes->post('roles/register', 'Roles::register');
$routes->get('roles/edit/(:num)', 'Roles::edit/$1');
$routes->post('roles/update/(:num)', 'Roles::update/$1');
$routes->get('roles/delete/(:num)', 'Roles::delete/$1');
$routes->get('roles/access/(:num)', 'Roles::access/$1');
$routes->post('roles/permiso', 'Roles::permiso');
//Modulos
$routes->get('modulo', 'Modulo::index');
$routes->get('modulo/add', 'Modulo::add');
$routes->post('modulo/register', 'Modulo::register');
$routes->get('modulo/edit/(:num)', 'Modulo::edit/$1');
$routes->post('modulo/update/(:num)', 'Modulo::update/$1');
$routes->post('modulo/delete', 'Modulo::delete');
//Usuarios
$routes->get('usuarios', 'Usuarios::index');
$routes->get('usuarios/add', 'Usuarios::add');
$routes->post('usuarios/register', 'Usuarios::register');
$routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
$routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');
$routes->post('usuarios/updatefoto', 'Usuarios::updatefoto');
$routes->post('usuarios/updateperfil', 'Usuarios::updateperfil');
$routes->post('usuarios/eliminar', 'Usuarios::eliminar');
$routes->post('usuarios/tipousuario', 'Usuarios::tipousuario');
//Bitácora
$routes->get('bitacora', 'Bitacora::index');
$routes->post('bitacora/tabla', 'Bitacora::tabla');
$routes->get('bitacora/exportar', 'Bitacora::exportar');
//Backup
$routes->get('backup', 'Backup::index');
$routes->post('backup/generarBackup', 'Backup::generarBackup');
$routes->post('backup/eliminar', 'Backup::eliminar');
$routes->post('backup/restaurar', 'Backup::restaurar');
//Perfil
$routes->get('perfil', 'Perfil::index');
//Configuracion
//Facultad
$routes->get('facultad', 'Facultad::index');
$routes->get('facultad/add', 'Facultad::add');
$routes->post('facultad/register', 'Facultad::register');
$routes->get('facultad/edit/(:num)', 'Facultad::edit/$1');
$routes->post('facultad/update/(:num)', 'Facultad::update/$1');
$routes->post('facultad/eliminar', 'Facultad::eliminar');
//Escuela
$routes->get('escuela', 'Escuela::index');
$routes->get('escuela/add', 'Escuela::add');
$routes->post('escuela/register', 'Escuela::register');
$routes->get('escuela/edit/(:num)', 'Escuela::edit/$1');
$routes->post('escuela/update/(:num)', 'Escuela::update/$1');
$routes->post('escuela/eliminar', 'Escuela::eliminar');
$routes->post('escuela/escuelas', 'Escuela::escuelas');
//Sede
$routes->get('sede', 'Sede::index');
$routes->get('sede/add', 'Sede::add');
$routes->post('sede/register', 'Sede::register');
$routes->get('sede/edit/(:num)', 'Sede::edit/$1');
$routes->post('sede/update/(:num)', 'Sede::update/$1');
$routes->post('sede/eliminar', 'Sede::eliminar');
//Migración
$routes->post('migracion/eliminarduplicados', 'Migracion::eliminarduplicados');
$routes->post('migracion/eliminardocentes', 'Migracion::eliminardocentes');
//Estudiantes
$routes->get('estudiantes', 'Estudiantes::index');
$routes->get('estudiantes/migrar', 'Estudiantes::migrar');
$routes->post('estudiantes/migracion', 'Estudiantes::migracion');
$routes->post('estudiantes/guardarmigracion', 'Estudiantes::guardarmigracion');
$routes->get('estudiantes/detalle/(:num)-(:segment)', 'Estudiantes::detalle/$1-$2');
$routes->post('estudiantes/item', 'Estudiantes::item');
$routes->post('estudiantes/eliminar', 'Estudiantes::eliminar');
//Cursos
$routes->get('cursos', 'Cursos::index');
$routes->get('cursos/migrar', 'Cursos::migrar');
$routes->post('cursos/migracion', 'Cursos::migracion');
$routes->post('cursos/guardarmigracion', 'Cursos::guardarmigracion');
$routes->get('cursos/detalle/(:num)-(:segment)', 'Cursos::detalle/$1-$2');
$routes->post('cursos/item', 'Cursos::item');
$routes->post('cursos/eliminar', 'Cursos::eliminar');
//Docentes
$routes->get('docentes', 'Docentes::index');
$routes->get('docentes/migrar', 'Docentes::migrar');
$routes->post('docentes/migracion', 'Docentes::migracion');
$routes->post('docentes/guardarmigracion', 'Docentes::guardarmigracion');
$routes->get('docentes/detalle/(:num)-(:segment)', 'Docentes::detalle/$1-$2');
$routes->post('docentes/item', 'Docentes::item');
$routes->post('docentes/eliminar', 'Docentes::eliminar');
//Periodo
$routes->get('periodo', 'Periodo::index');
$routes->get('periodo/migrar', 'Periodo::migrar');
$routes->post('periodo/migracion', 'Periodo::migracion');
$routes->post('periodo/guardarmigracion', 'Periodo::guardarmigracion');
$routes->get('periodo/detalle/(:num)', 'Periodo::detalle/$1');
$routes->post('periodo/eliminar', 'Periodo::eliminar');
//Asignacion
$routes->get('asignacion', 'Asignacion::index');
$routes->get('asignacion/migrar', 'Asignacion::migrar');
$routes->post('asignacion/migracion', 'Asignacion::migracion');
$routes->post('asignacion/guardarmigracion', 'Asignacion::guardarmigracion');
$routes->get('asignacion/detalle/(:num)-(:segment)', 'Asignacion::detalle/$1-$2');
$routes->post('asignacion/item', 'Asignacion::item');
$routes->post('asignacion/eliminar', 'Asignacion::eliminar');
//Matriculas
$routes->get('matriculas', 'Matriculas::index');
$routes->get('matriculas/migrar', 'Matriculas::migrar');
$routes->post('matriculas/migracion', 'Matriculas::migracion');
$routes->post('matriculas/guardarmigracion', 'Matriculas::guardarmigracion');
$routes->get('matriculas/detalle/(:num)-(:segment)-(:segment)', 'Matriculas::detalle/$1-$2-$3');
$routes->post('matriculas/item', 'Matriculas::item');
$routes->post('matriculas/eliminar', 'Matriculas::eliminar');
//Encuestas
//Encuesta
$routes->get('encuesta', 'Encuesta::index');
$routes->get('encuesta/add', 'Encuesta::add');
$routes->post('encuesta/register', 'Encuesta::register');
$routes->get('encuesta/edit/(:num)', 'Encuesta::edit/$1');
$routes->post('encuesta/update/(:num)', 'Encuesta::update/$1');
$routes->post('encuesta/eliminar', 'Encuesta::eliminar');
//Titulo encuesta
$routes->get('tituloencuesta', 'Tituloencuesta::index');
$routes->get('tituloencuesta/add', 'Tituloencuesta::add');
$routes->post('tituloencuesta/register', 'Tituloencuesta::register');
$routes->get('tituloencuesta/edit/(:num)', 'Tituloencuesta::edit/$1');
$routes->post('tituloencuesta/update/(:num)', 'Tituloencuesta::update/$1');
$routes->post('tituloencuesta/eliminar', 'Tituloencuesta::eliminar');
$routes->post('tituloencuesta/nroorden', 'Tituloencuesta::nroorden');
//Preguntas
$routes->get('preguntas', 'Preguntas::index');
$routes->get('preguntas/add', 'Preguntas::add');
$routes->post('preguntas/register', 'Preguntas::register');
$routes->get('preguntas/edit/(:num)-(:num)', 'Preguntas::edit/$1-$2');
$routes->post('preguntas/update/(:num)', 'Preguntas::update/$1');
$routes->post('preguntas/eliminar', 'Preguntas::eliminar');
$routes->post('preguntas/nropregunta', 'Preguntas::nropregunta');
//Valores
$routes->get('valores', 'Valores::index');
$routes->get('valores/add', 'Valores::add');
$routes->post('valores/register', 'Valores::register');
$routes->get('valores/edit/(:num)', 'Valores::edit/$1');
$routes->post('valores/update/(:num)', 'Valores::update/$1');
$routes->post('valores/eliminar', 'Valores::eliminar');
$routes->post('valores/nroorden', 'Valores::nroorden');
//Ubigeo
$routes->post('usuarios/provincia', 'Usuarios::provincia');
$routes->post('usuarios/distrito', 'Usuarios::distrito');
//Empresa
$routes->get('empresa', 'Empresa::index');
$routes->get('empresa/add', 'Empresa::add');
$routes->post('empresa/register', 'Empresa::register');
$routes->get('empresa/edit/(:num)', 'Empresa::edit/$1');
$routes->post('empresa/update', 'Empresa::update');
$routes->post('empresa/eliminar', 'Empresa::eliminar');
//Configuracion
$routes->get('configuracion', 'Configuracion::index');
$routes->get('configuracion/add', 'Configuracion::add');
$routes->post('configuracion/register', 'Configuracion::register');
$routes->get('configuracion/edit/(:num)', 'Configuracion::edit/$1');
$routes->post('configuracion/update/(:num)', 'Configuracion::update/$1');
$routes->post('configuracion/eliminar', 'Configuracion::eliminar');
//Encuestar
$routes->get('encuestar', 'Encuestar::index');
$routes->get('encuestar/listarencuestas/(:num)', 'Encuestar::listarencuestas/$1');
$routes->get('encuestar/listarcursos/(:num)-(:num)', 'Encuestar::listarcursos/$1-$2');
$routes->get('encuestar/listarestudiantes', 'Encuestar::listarestudiantes');
$routes->get('encuestar/llenarencuesta/(:num)-(:num)-(:num)', 'Encuestar::llenarencuesta/$1-$2-$3');
$routes->post('encuestar/guardarrespuestas', 'Encuestar::guardarrespuestas');
$routes->post('encuestar/guardarrespuestatemporal', 'Encuestar::guardarrespuestatemporal');
$routes->get('encuestar/constancia/(:num)-(:num)', 'Encuestar::constancia/$1-$2');
$routes->get('encuestar/encuestas', 'Encuestar::encuestas');
$routes->get('encuestar/estudiantes/(:num)', 'Encuestar::estudiantes/$1');
$routes->get('encuestar/validarencuesta/(:num)-(:num)', 'Encuestar::validarencuesta/$1-$2');
$routes->post('encuestar/guardarvalidacion', 'Encuestar::guardarvalidacion');
//Generar Constancias
$routes->get('generar', 'Generar::index');
$routes->get('generar/detalle/(:num)-(:num)', 'Generar::detalle/$1-$2');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
