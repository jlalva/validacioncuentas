<?php

namespace App\Controllers;

use App\Models\codigoModelo;
use App\Models\periodoModelo;
use App\Models\personaModelo;
use App\Models\usuariosModelo;
use CodeIgniter\Controller;
use Config\Services;

//use App\Libraries\SMTPMail;

class Index extends Controller
{
       /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */

    protected $request;
    protected $helpers = ["form"];
    private $email;

    public function __construct()
    {
        $this->email = \Config\Services::email();
    }

    public function index()
    {
        if(session('recordar') == 1){
            return redirect()->to(base_url("/inicio"));
        }else{
            return view('login/index');
        }
    }


    public function recuperar()
    {
        return view('login/recuperar');
    }

    public function login(){
        $object = new usuariosModelo();
        $usuario = trim($this->request->getVar('usuario'));
        $clave = trim($this->request->getVar('clave'));
        $item = $object->usuarioLogin($usuario);
        $arr = [];
        if(!empty($item)){
            if(trim(desencriptar($item->usu_clave)) == $clave){
                $nombres = explode(" ", $item->usu_nombre);
                $apellidos = explode(" ", $item->usu_apellido);
                $nombre = $nombres[0];
                $apellido_uno = $apellidos[0];
                $apellido_dos = '';
                $empresaA = empresaActiva();
                $idempresa = 0;
                if($empresaA){
                    $idempresa = $empresaA->emp_id;
                }
                if(isset($apellidos[1])){
                    $apellido_dos = $apellidos[1];
                }
                $data = [
                    'authenticated' => true,
                    'idusuario' => $item->usu_id,
                    'usuario' => $item->usu_usuario,
                    'idrol' => $item->usu_rol_id,
                    'rol' => $item->rol_nombre,
                    'nombre' => $nombre,
                    'apellido_uno' => $apellido_uno,
                    'apellido_dos' => $apellido_dos,
                    'nombres' => $item->usu_nombre,
                    'apellidos' => $item->usu_apellido,
                    'empresa_activa' => $idempresa
                ];
                $session = session();
                $session->set($data);
                $arr = ['status'=>'ok','message'=>'Datos validados correctamente'];
            }else{
                $arr = ['status'=>'error','message'=>'El usuario o clave no son correctos'];
            }
        }else{
            $arr = ['status'=>'error','message'=>'El usuario no se encuentra registrado'];
        }
        echo json_encode($arr);
    }

    public function validarcorreo(){
        $object = new usuariosModelo();
        $usu_email = $_POST["correo"];
        $items = $object->getUsuarioCorreo($usu_email);
        if(empty($items)){
            echo 'vacio';
        }else{
            $objectCodigo = new codigoModelo();
            $correo = $items->usu_usuario;
            $nombre = $items->usu_nombre.' '.$items->usu_apellido;
            $rol = $items->rol_nombre;
            $codigo = rand(100000,999999);
            $fecha = date('Y-m-d H:i:s');
            $data = [
                'cod_email' => $correo,
                'cod_codigo' => $codigo,
                'cod_fecha' => $fecha
            ];
            $web = web();
            $face = facebook();
            $twiter = twitter();
            $instagram = instagram();
            $youtube = youtube();
            if($objectCodigo->addCodigo($data)){
                $html = '<!DOCTYPE html>
                <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width,initial-scale=1">
                    <meta name="x-apple-disable-message-reformatting">
                    <title></title>
                    <!--[if mso]>
                    <noscript>
                        <xml>
                            <o:OfficeDocumentSettings>
                                <o:PixelsPerInch>96</o:PixelsPerInch>
                            </o:OfficeDocumentSettings>
                        </xml>
                    </noscript>
                    <![endif]-->
                    <style>
                        table, td, div, h1, p {font-family: Arial, sans-serif;}
                    </style>
                </head>
                <body style="margin:0;padding:0;">
                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                        <tr>
                            <td align="center" style="padding:0;">
                                <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                    <tr>
                                        <td align="center" style="padding:40px 0 30px 0;background:#70bbd9;">
                                            <img src="https://assets.codepen.io/210284/h1.png" alt="" width="300" style="height:auto;display:block;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:36px 30px 42px 30px;">
                                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                <tr>
                                                    <td style="padding:0 0 36px 0;color:#153643;">
                                                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">SOLICITUD DE CAMBIO DE CONTRASEÑA</h1>
                                                        <h3>Estimado '.$rol.'</h3>
                                                        <h3>'.$nombre.'</h3>
                                                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Para continuar con el proceso de <b>cambio de contraseña</b>, ingresa el siguiente código de verificación</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:0;">
                                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                            <tr>
                                                                <td align="center" colspan="3" style="width:540px;color:#153643;background: skyblue;height: 80px;">
                                                                    <h1>'.$codigo.'</h1>
                                                                </td>
                                                            </tr>
                                                            <tr><td style="height:30px;"></td></tr>
                                                            <tr>
                                                                <td align="justify" colspan="3">
                                                                    Tener en cuenta que, en el caso de NO COMPLETAR el proceso dentro de las próximas 24 horas, el código de verificación mencionado no será válido.
                                                                </td>
                                                            </tr>
                                                            <tr><td style="height:30px;"></td></tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    Si tuvieras alguna duda o consulta adicional, encuéntranos en:
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px;">
                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:11px;font-family:Arial,sans-serif;">
                                        <tr>
                                            <td style="padding:0;width:50%;">
                                                <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                                                    <tr>
                                                        <td style="padding: 0 0 0 10px; width: 45%;">
                                                            <a style="color: #000;text-decoration: none; display: inline-block; vertical-align: middle;" href="'.$web.'">
                                                                <img style="height: auto; display: inline-block; border: 0; vertical-align: middle;" src="https://cdn-icons-png.flaticon.com/512/1927/1927746.png" alt="Web" width="25" />
                                                                '.$web.'
                                                            </a>
                                                        </td>
                                                        <td style="padding: 0 0 0 10px; width: 45%;">
                                                            <a style="color: #000;text-decoration: none; display: inline-block; vertical-align: middle;" href="'.$twiter.'">
                                                                <img style="height: auto; display: inline-block; border: 0; vertical-align: middle;" src="https://cdn.icon-icons.com/icons2/730/PNG/512/twitter_icon-icons.com_62765.png" alt="Twitter" width="25" />
                                                                '.$twiter.'
                                                            </a>
                                                        </td>
                                                        <td style="padding: 0 0 0 10px;">
                                                            <a style="color: #000;text-decoration: none; display: inline-block; vertical-align: middle;" href="'.$instagram.'">
                                                                <img style="height: auto; display: inline-block; border: 0; vertical-align: middle;" src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram" width="25" />
                                                                '.$instagram.'
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 0 0 0 10px; width: 45%;">
                                                            <a style="color: #000;text-decoration: none; display: inline-block; vertical-align: middle;" href="'.$face.'">
                                                                <img style="height: auto; display: inline-block; border: 0; vertical-align: middle;" src="https://cdn-icons-png.flaticon.com/512/220/220200.png" alt="Facebook" width="20" />
                                                                '.$face.'
                                                            </a>
                                                        </td>
                                                        <td style="padding: 0 0 0 10px; width: 45%;" colspan="2">
                                                            <a style="color: #000;text-decoration: none; display: inline-block; vertical-align: middle;" href="'.$youtube.'">
                                                                <img style="height: auto; display: inline-block; border: 0; vertical-align: middle;" src="https://w7.pngwing.com/pngs/1009/93/png-transparent-youtube-computer-icons-logo-youtube-angle-social-media-share-icon.png" alt="Youtube" width="25" />
                                                                '.$youtube.'
                                                            </a>
                                                        </td>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';

                $this->email->setTo($correo);// Datos el email destino. Donde irá a parar el formulario
                $this->email->setFrom("jinoluisalva@gmail.com", "Contacto web");// Email desde el que se envía (el que hemos configurarado en el apartado anterior)
                $this->email->setSubject('Código de validación');
                $this->email->setMessage($html);
                if ($this->email->send()){
                    echo 'ok';
                }else{
                    echo 'error';
                }
            }else{
                echo 'error';
            }
        }
    }

    public function validarcodigo(){
        $object = new codigoModelo();
        $email = $_POST["correo"];
        $codigo = $_POST["codigo"];
        $resp = $object->validaCodigo($email, $codigo);
        if(empty($resp)){
            echo 'vacio';
        }else{

            $fechaInicio = new \DateTime($resp[0]['cod_fecha']);
            $fechaFin = new \DateTime(date('Y-m-d H:i:s'));

            $diferencia = $fechaInicio->diff($fechaFin);

            $dias = $diferencia->days;
            $horas = $dias * 24 + $diferencia->h;
            if($horas > 24){
                echo 'expiro';
            }else{
                $user = new usuariosModelo();
                $id = $user->usuariosId($email);
                echo $id[0]['usu_id'];
            }
        }
    }

    public function actualizarclave(){
        $object = new usuariosModelo();
        $session = Services::session();
        $email = $_POST["correo"];
        $clave = $_POST["clave"];
        $idusuario = $_POST["idusuario"];
        $data = [
            'usu_clave' => encriptar($clave)
        ];
        if($object->updateClave($idusuario, $data)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }

    public function salir(){
        $session = session();
        $session->destroy();
        return redirect()->to(base_url("/"));
    }
}
