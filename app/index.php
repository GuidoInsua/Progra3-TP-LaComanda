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
//Middlewares
require_once './Middlewares/MValidarMesa.php';
require_once './Middlewares/MValidarPedido.php';

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

    $app->group('/mesa', function (RouteCollectorProxy $group) {
        $group->get('/obtenerTodas', MesaController::class . ':getAll');
        $group->post('/obtenerUna', MesaController::class . ':get')->add(new MValidarMesa("codigo"));
        $group->post('/alta', MesaController::class . ':add')->add(new MValidarMesa("codigo"));
        $group->put('/modificar', MesaController::class . ':update')->add(new MValidarMesa("codigo", "estadoMesa"));
        $group->put('/baja', MesaController::class . ':delete')->add(new MValidarMesa("codigo"));
    });

    $app->group('/pedido', function (RouteCollectorProxy $group) {
      $group->get('/obtenerTodos', PedidoController::class . ':getAll');
      $group->post('/obtenerUno', PedidoController::class . ':get')->add(new MValidarPedido("codigo"));
      $group->post('/alta', PedidoController::class . ':add')->add(new MValidarPedido("codigo"));
      $group->put('/modificar', PedidoController::class . ':update')->add(new MValidarPedido("codigo"));
      $group->put('/baja', PedidoController::class . ':delete')->add(new MValidarPedido("codigo"));
    });

    $app->group('/producto', function (RouteCollectorProxy $group) {
      $group->get('/obtenerTodos', ProductoController::class . ':getAll');
      $group->post('/obtenerUno', ProductoController::class . ':get');
      $group->post('/alta', ProductoController::class . ':add');
      $group->put('/modificar', ProductoController::class . ':update');
      $group->put('/baja', ProductoController::class . ':delete');
    });

    $app->group('/usuario', function (RouteCollectorProxy $group) {
      $group->get('/obtenerTodos', UsuarioController::class . ':getAll');
      $group->post('/obtenerUno', UsuarioController::class . ':get');
      $group->post('/alta', UsuarioController::class . ':add');
      $group->put('/modificar', UsuarioController::class . ':update');
      $group->put('/baja', UsuarioController::class . ':delete');
    });

    $app->run();
} 
catch (Exception $e) {
  echo $e->getMessage();
}

?>