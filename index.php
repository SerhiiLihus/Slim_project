<?php 
require 'vendor/autoload.php';
require 'sanitize/sanitize_input.php';

use \Slim\App;
use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;

$app = new App();
$container = $app->getContainer();


$container['view'] = function ($container) {
    $view = new Twig('templates', [
        'cache' => false
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($container['router'], $basePath));

    return $view;
};


$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'about.twig');
})->setName('home');

$app->get('/contact', function ($request, $response, $args) {
    return $this->view->render($response, 'contact.twig');
})->setName('contact');

$app->post('/contact', function ($request, $response, $args) {
    //var_dump($request->getParams()); -> see all params that were send via post or get methods
    // $request->getParam('username'); -> to retrieve certain parameter from query string

    $name = $request->getParam('name');
    $email = $request->getParam('email');
    $message = $request->getParam('message');

    if (!empty($name) && !empty($email) && !empty($message)) {
        $name = sanitize_input($name, 'name');
        $email = sanitize_input($email, 'email');
        $message = sanitize_input($message, 'message');
    } else {
        return $response->withRedirect('/contact', 301);
    }


    $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
    $mailer = \Swift_mailer::newInstance($transport);
    $message = \Swift_Message::newInstance();
    $message->setSubject('Email from our webiste');
    $message->setFrom(array(
        $name => $email
    ));
    $message->setTo(array(
        'sergey@sergey'
    ));
    $X550VL->setBody($message);
    $result = $mailer->send($message);
});

$app->run();