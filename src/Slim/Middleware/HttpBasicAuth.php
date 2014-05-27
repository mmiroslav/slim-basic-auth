<?php

namespace Slim\Middleware;

/**
 * HTTP Basic Authentication
 *
 * Provides HTTP Basic Authentication on given routes
 *
 * @package    Slim
 * @author     Mika Tuupola <tuupola@appelsiini.net>
 */
class HttpBasicAuth extends \Slim\Middleware {

    public $options;

    public function __construct($options = null) {

        /* Default options. */
        $this->options = array(
            "users" => array(),
            "path" => "/",
            "realm" => "Protected",
            "responseContent" => "",
        );

        if ($options) {
            $this->options = array_merge($this->options, (array)$options);
        }
    }

    public function call() {
        $request = $this->app->request;

        /* If path matches what is given on initialization. */
        if (false !== strpos($request->getPath(), $this->options["path"])) {

            /* Ignore POST-ing feedbacks */
            if (($request->getMethod() == "POST")) {
              if (endsWith($request->getPath(), "/1/feedback")) {
                $this->next->call();
                return;
              }
            }

            /* Ignore CORS OPTIONS */
            if (($request->getMethod() == "OPTIONS")) {
              if (endsWith($request->getPath(), "/1/feedback")) {
                $this->next->call();
                return;
              }
            }

            $user = $request->headers("PHP_AUTH_USER");
            $pass = $request->headers("PHP_AUTH_PW");

            /* Check if user and passwords matches. */
            if (isset($this->options["users"][$user]) && $this->options["users"][$user] === $pass) {
                $this->next->call();
            } else {
                $this->app->response->status(401);
                $this->app->response->header("WWW-Authenticate", sprintf('Basic realm="%s"', $this->options["realm"]));
                $this->app->response->setBody($this->options["responseContent"]);
                return;
            }
        } else {
            $this->next->call();
        }
    }
}

function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}
