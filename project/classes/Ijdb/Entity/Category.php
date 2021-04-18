<?php


namespace Ijdb\Entity;
use \Ninja\DatabaseTable;

class Category
{
    public $id;
    public $name;
    private $jokeCategoriesTable;
    private $jokesTable;

    public function __construct(DatabaseTable $jokeCategoriesTable, DatabaseTable $jokesTable){
        $this->jokeCategoriesTable=$jokeCategoriesTable;
        $this->jokesTable=$jokesTable;
    }
    public function getJokes($offset = null, $limit = null){
        $jokeCategories=$this->jokeCategoriesTable->find('categoryId', $this->id,null ,$limit, $offset);

        $jokes=[];
        foreach($jokeCategories as $jokeCategory){
            $joke=$this->jokesTable->findById($jokeCategory->jokeId);
            if($joke){
                $jokes[]=$joke;
            }
        }
        usort($jokes, [$this, 'sortJokes']);

        return $jokes;
    }
    public function sortJokes($a,$b){
        $a = new \Datetime($a->jokedate);
        $b = new \Datetime($b->jokedate);
        if ($a->getTimestamp() == $b->getTimestamp()){
            return 0;
        }
        return ($a->getTimestamp() > $b->getTimestamp()) ? -1 : 1;
    }
    public function getNumJokes(){
        return $this->jokeCategoriesTable->total('CategoryId',$this->id);
    }
}