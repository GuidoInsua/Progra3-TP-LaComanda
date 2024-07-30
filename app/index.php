<?php
// php -S localhost:666 -t app
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
//Data base
require_once './DataBase/AccesoDatos.php';
//Controllers
require_once './Controllers/MesaController.php';
require_once './Controllers/PedidoController.php';
require_once './Controllers/ProductoController.php';
require_once './Controllers/UsuarioController.php';
require_once './Controllers/OrdenController.php';
require_once './Controllers/LoginController.php';
require_once './Controllers/EncuestaController.php';
//Middlewares
require_once './Middlewares/MValidarMesa.php';
require_once './Middlewares/MValidarPedido.php';
require_once './Middlewares/MValidarProducto.php';
require_once './Middlewares/MValidarUsuario.php';
require_once './Middlewares/MValidarOrden.php';
require_once './Middlewares/MLowerCase.php';
require_once './Middlewares/MValidarEncuesta.php';
//Autenticacion
require_once './Middlewares/MValidarLogin.php';
require_once './Middlewares/MAutenticacionToken.php';
require_once './Middlewares/MAutenticacionPerfil.php';

try {
    // Load ENV
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
  
    // Instantiate App
    $app = AppFactory::create();
  
    // Set base path
    $app->setBasePath('/app');
  
    // Add Body Parsing Middleware
    $app->addBodyParsingMiddleware();

    $app->add(new MLowerCase());

    $app->group('/mesa', function (RouteCollectorProxy $group) {
        $group->get('/obtenerTodas', MesaController::class . ':getAll')
          ->add(new MAutenticacionPerfil(['socio']));   //

        $group->post('/obtenerUna', MesaController::class . ':get')
          ->add(new MValidarMesa("codigo"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

        $group->post('/alta', MesaController::class . ':add')
          ->add(new MValidarMesa("codigo"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

        $group->put('/modificar', MesaController::class . ':update')
          ->add(new MValidarMesa("codigo", "estadoMesa"))->add(new MAutenticacionPerfil(['mozo', 'socio']));  //

        $group->put('/baja', MesaController::class . ':delete')
          ->add(new MValidarMesa("codigo"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio'])); 
          
        $group->post('/altaFoto', MesaController::class . ':addFoto')
          ->add(new MValidarMesa("codigo", "imagen"))->add(new MAutenticacionPerfil(['mozo']));  //

        $group->get('/mesaMasUsada', MesaController::class . ':obtenerMesaMasUsada')
          ->add(new MAutenticacionPerfil(['socio']));   //
    });

    $app->group('/pedido', function (RouteCollectorProxy $group) {
      $group->get('/obtenerTodos', PedidoController::class . ':getAll')
        ->add(new MAutenticacionPerfil(['socio']));  

      $group->post('/obtenerUno', PedidoController::class . ':get')
        ->add(new MValidarPedido("codigo"))->add(new MAutenticacionPerfil(['socio']));  

      $group->post('/alta', PedidoController::class . ':add')
        ->add(new MValidarPedido("nombreCliente", "idMesa", "productos"))->add(new MAutenticacionPerfil(['mozo'])); //

      $group->put('/modificar', PedidoController::class . ':update')
        ->add(new MValidarPedido("codigo"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));

      $group->put('/baja', PedidoController::class . ':delete')
        ->add(new MValidarPedido("codigo"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio'])); 
        
      $group->post('/obtenerTiempoEstimado', PedidoController::class . ':obtenerTiempoEstimadoPorMesa')
        ->add(new MValidarPedido("idMesa", "codigo"));      //

      $group->get('/obtenerPedidosParaServir', PedidoController::class . ':obtenerPedidosParaServir')
        ->add(new MAutenticacionPerfil(['mozo']));  //

      $group->get('/pedidosFueraDeTiempo', PedidoController::class . ':obtenerPedidosFueraDeTiempo')
        ->add(new MAutenticacionPerfil(['socio']));  //
    });

    $app->group('/producto', function (RouteCollectorProxy $group) {
      $group->get('/obtenerTodos', ProductoController::class . ':getAll')
        ->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

      $group->post('/obtenerUno', ProductoController::class . ':get')
        ->add(new MValidarProducto("tipo", "idSector"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

      $group->post('/alta', ProductoController::class . ':add')
        ->add(new MValidarProducto("tipo", "idSector", "precio"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));    
         
      $group->put('/modificar', ProductoController::class . ':update')
        ->add(new MValidarProducto("tipo", "idSector"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

      $group->put('/baja', ProductoController::class . ':delete')
        ->add(new MValidarProducto("tipo", "idSector"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  
    });

    $app->group('/usuario', function (RouteCollectorProxy $group) {
      $group->get('/obtenerTodos', UsuarioController::class . ':getAll')
        ->add(new MAutenticacionPerfil(['socio']));  

      $group->post('/obtenerUno', UsuarioController::class . ':get')
        ->add(new MValidarUsuario("nombre"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

      $group->post('/alta', UsuarioController::class . ':add')
        ->add(new MValidarUsuario("nombre", "clave", "idRol"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

      $group->put('/modificar', UsuarioController::class . ':update')
        ->add(new MValidarUsuario("nombre"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

      $group->put('/baja', UsuarioController::class . ':delete')
        ->add(new MValidarUsuario("nombre"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  
    });

    $app->group('/orden', function (RouteCollectorProxy $group) {
      $group->get('/obtenerTodos', OrdenController::class . ':mostrarTodas')
        ->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  

      $group->post('/obtenerPorEstado', OrdenController::class . ':mostrarOrdenesPorEstado')
        ->add(new MValidarOrden("estadoOrden"))->add(new MAutenticacionPerfil(['mozo', 'cocinero', 'bartender', 'cervecero', 'socio']));  
           
      $group->post('/obtenerPorEstadoSector', OrdenController::class . ':mostrarOrdenesPorEstadoSector')
        ->add(new MValidarOrden("idSector", "estadoOrden"))->add(new MAutenticacionPerfil(['cocinero', 'bartender', 'cervecero']));  //
         
      $group->put('/modificarEstado', OrdenController::class . ':modificarEstado')
        ->add(new MValidarOrden("id", "estadoOrden", "tiempoEstimado", "idUsuario"))->add(new MAutenticacionPerfil(['cocinero', 'bartender', 'cervecero'])); //
    });

    $app->group('/encuesta', function (RouteCollectorProxy $group) {
      $group->post('/subirEncuesta', EncuestaController::class . ':add')
        ->add(new MValidarEncuesta("idMesa", "codigoPedido", "puntuacion", "comentario"));  //

      $group->get('/obtenerMejoresComentarios', EncuestaController::class . ':obtenerMejoresComentarios')
        ->add(new MAutenticacionPerfil(['socio'])); //
    });

    $app->post('/login', \LoginController::class . ':loginUsuario')->add(new MValidarLogin());

    $app->get('/descargarLogoPdf', \EncuestaController::class . ':descargarLogoPdf')->add(new MAutenticacionPerfil(['socio']));

    $app->run();
} 
catch (Exception $e) {
  echo $e->getMessage();
}

?>