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
//Perfil
$routes->get('perfil', 'Perfil::index');
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
$routes->post('empresa/marcaractivo', 'Empresa::marcaractivo');
//Subir data
$routes->get('subirdata', 'Subirdata::index');
$routes->get('subirdata/add', 'Subirdata::add');
$routes->post('subirdata/validar', 'Subirdata::validar');
$routes->post('subirdata/guardararchivo', 'Subirdata::guardararchivo');
$routes->get('subirdata/detalle/(:num)', 'Subirdata::detalle/$1');
$routes->get('subirdata/pdf/(:num)', 'Subirdata::pdf/$1');
//Generar data
$routes->get('generardata', 'Generardata::index');
$routes->get('generardata/add', 'Generardata::add');
$routes->post('generardata/preview', 'Generardata::preview');
$routes->post('generardata/procesar', 'Generardata::procesar');
$routes->post('generardata/guardararchivo', 'Generardata::guardararchivo');
$routes->get('generardata/detalle/(:num)', 'Generardata::detalle/$1');
$routes->get('generardata/cuentas/(:num)', 'Generardata::cuentas/$1');
$routes->get('generardata/exportar/(:num)', 'Generardata::exportar/$1');
$routes->get('generardata/descargarcuentas/(:num)', 'Generardata::descargarcuentas/$1');
$routes->get('generardata/pdf/(:num)', 'Generardata::pdf/$1');
$routes->get('generardata/descargarrepoexcel/(:num)', 'Generardata::descargarrepoexcel/$1');
$routes->get('generardata/descargarrepocsv/(:num)', 'Generardata::descargarrepocsv/$1');
$routes->get('generardata/pdfdescargar/(:num)', 'Generardata::pdfdescargar/$1');
$routes->get('generardata/peyorativos/(:num)', 'Generardata::peyorativos/$1');
$routes->post('generardata/meditarcacafonia', 'Generardata::meditarcacafonia');
$routes->get('generardata/duplicados/(:num)', 'Generardata::duplicados/$1');
//Tipo persona
$routes->get('tipopersona', 'Tipopersona::index');
$routes->get('tipopersona/add', 'Tipopersona::add');
$routes->post('tipopersona/register', 'Tipopersona::register');
$routes->get('tipopersona/edit/(:num)', 'Tipopersona::edit/$1');
$routes->post('tipopersona/update', 'Tipopersona::update');
$routes->post('tipopersona/eliminar', 'Tipopersona::eliminar');
//Dominio
$routes->get('dominio', 'Dominio::index');
$routes->get('dominio/add', 'Dominio::add');
$routes->post('dominio/register', 'Dominio::register');
$routes->get('dominio/edit/(:num)', 'Dominio::edit/$1');
$routes->post('dominio/update', 'Dominio::update');
$routes->post('dominio/eliminar', 'Dominio::eliminar');
//Peyorativo
$routes->get('peyorativos', 'Peyorativos::index');
$routes->get('peyorativos/add', 'Peyorativos::add');
$routes->post('peyorativos/register', 'Peyorativos::register');
$routes->get('peyorativos/edit/(:num)', 'Peyorativos::edit/$1');
$routes->post('peyorativos/update', 'Peyorativos::update');
$routes->post('peyorativos/eliminar', 'Peyorativos::eliminar');
//Compuesto
$routes->get('compuesto', 'Compuesto::index');
$routes->get('compuesto/add', 'Compuesto::add');
$routes->post('compuesto/register', 'Compuesto::register');
$routes->get('compuesto/edit/(:num)', 'Compuesto::edit/$1');
$routes->post('compuesto/update', 'Compuesto::update');
$routes->post('compuesto/eliminar', 'Compuesto::eliminar');
//Backup
$routes->get('backup', 'Backup::index');
$routes->post('backup/generarBackup', 'Backup::generarBackup');
$routes->post('backup/eliminar', 'Backup::eliminar');
$routes->post('backup/restaurar', 'Backup::restaurar');
//Exportar
$routes->get('exportar', 'Exportar::index');
$routes->post('exportar/filtrado', 'Exportar::filtrado');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
