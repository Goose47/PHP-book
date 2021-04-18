<?php

namespace Ijdb;

use Ninja\DatabaseTable;

class IjdbRoutes implements \Ninja\Routes
{
    private $authorsTable;
    private $jokesTable;
    private $categoriesTable;
    private $jokeCategoriesTable;
    private $authentication;

    public function __construct()
    {
        include __DIR__.'/../../includes/DatabaseConnection.php';
        $this->jokesTable = new \Ninja\DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorsTable, &$this->jokeCategoriesTable]);
        $this->authorsTable = new \Ninja\DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokesTable]);
        $this->categoriesTable = new \Ninja\DatabaseTable($pdo, 'category', 'id', 'Ijdb\Entity\Category',[&$this->jokeCategoriesTable, &$this->jokesTable]);
        $this->jokeCategoriesTable = new \Ninja\DatabaseTable($pdo,'joke_category', 'jokeId');
        $this->authentication = new \Ninja\Authentication($this->authorsTable, 'email', 'password');
    }


    public function getRoutes($route) : array {

        $jokeController = new \Ijdb\Controllers\Joke($this->jokesTable, $this->authorsTable, $this->authentication, $this->categoriesTable);
        $authorController = new \Ijdb\Controllers\Register($this->authorsTable, $this->authentication);
        $loginController = new \Ijdb\Controllers\Login($this->authentication);
        $categoryController = new \Ijdb\Controllers\Category($this->categoriesTable);

        $routes = [
            'joke/edit'=>[
                'POST'=>[
                    'controller'=>$jokeController,
                    'action'=>'saveEdit'
                ],
                'GET'=>[
                    'controller'=>$jokeController,
                    'action'=>'edit'
                ],
                'login'=>true
            ],
            'joke/list'=>[
                'GET'=>[
                    'controller'=>$jokeController,
                    'action'=>'list'
                ]
            ],
            'joke/home'=>[
                'GET'=>[
                    'controller'=>$jokeController,
                    'action'=>'home'
                ]
            ],
            'joke/delete'=>[
                'POST'=>[
                    'controller'=>$jokeController,
                    'action'=>'delete'
                ],
                'login'=>true
            ],
            'author/register'=>[
                'GET'=>[
                    'controller'=>$authorController,
                    'action'=>'registrationForm'
                ],
                'POST'=>[
                    'controller'=>$authorController,
                    'action'=>'registerUser'
                ]
            ],
            'author/success'=>[
                'GET'=>[
                    'controller'=>$authorController,
                    'action'=>'success'
                ]
            ],
            'login/error'=>[
                'GET'=>[
                    'controller'=>$loginController,
                    'action'=>'error'
                ]
            ],
            'login'=>[
                'GET'=>[
                    'controller'=>$loginController,
                    'action'=>'loginForm'
                ],
                'POST'=>[
                    'controller'=>$loginController,
                    'action'=>'processLogin'
                ]
            ],
            'permission/error'=>[
                'GET'=>[
                    'controller'=> $loginController,
                    'action'=>'permissionError'
                ]
            ],
            'login/success'=>[
                'GET'=>[
                    'controller'=>$loginController,
                    'action'=>'success'
                ]
            ],
            'logout'=>[
                'GET'=>[
                    'controller'=>$loginController,
                    'action'=>'logout'
                ]
            ],
            'category/edit'=>[
                'GET'=>[
                    'controller'=>$categoryController,
                    'action'=>'edit'
                ],
                'POST'=>[
                    'controller'=>$categoryController,
                    'action'=>'saveEdit'
                ],
                'login'=>true,
                'permissions'=>\Ijdb\Entity\Author::EDIT_CATEGORIES
            ],
            'category/list'=>[
                'GET'=>[
                    'controller'=>$categoryController,
                    'action'=>'list'
                ],
                'login'=>true,
                'permissions'=>\Ijdb\Entity\Author::LIST_CATEGORIES
            ],
            'category/delete'=>[
                'POST'=>[
                    'controller'=>$categoryController,
                    'action'=>'delete'
                ],
                'login'=>true,
                'permissions'=>\Ijdb\Entity\Author::REMOVE_CATEGORIES
            ],
            'author/list'=>[
                'GET'=>[
                    'controller'=>$authorController,
                    'action'=>'list'
                ],
                'login'=>true,
                'permissions'=>\Ijdb\Entity\Author::EDIT_USER_ACCESS
            ],
            'author/permissions'=>[
                'GET'=>[
                    'controller'=>$authorController,
                    'action'=>'permissions'
                ],
                'POST'=>[
                    'controller'=>$authorController,
                    'action'=>'savePermissions'
                ],
                'login'=>true,
                'permissions'=>\Ijdb\Entity\Author::EDIT_USER_ACCESS
            ]
        ];


        return $routes;
    }

    public function getAuthentication() : \Ninja\Authentication {
        return $this->authentication;
    }
    public function checkPermission($permission): bool{
        $user = $this->authentication->getUser();
        if($user && $user->hasPermission($permission)){
            return true;
        }else{
            return false;
        }
    }
}
