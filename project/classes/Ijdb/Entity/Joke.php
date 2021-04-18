<?php


namespace Ijdb\Entity;


class Joke
{
    public $joketext;
    public $jokedate;
    public $authorid;
    public $id;
    private $authorsTable;
    private $author;
    private $jokeCategoriesTable;

    public function __construct(\Ninja\DatabaseTable $authorsTable, \Ninja\DatabaseTable $jokeCategoriesTable){
        $this->authorsTable = $authorsTable;
        $this->jokeCategoriesTable  = $jokeCategoriesTable;
    }

    public function getAuthor(){
        if(empty($this->author)){
            $this->author=$this->authorsTable->findById($this->authorid);
        }
        return $this->author;
    }
    public function addCategory($categoryId){
        $jokeCat = ['categoryId'=>$categoryId, 'jokeId'=>$this->id];
        $this->jokeCategoriesTable->save($jokeCat);
    }
    public function hasCategory($categoryId){
        $jokeCategories = $this->jokeCategoriesTable->find('jokeId', $this->id);
        foreach($jokeCategories as $jokeCategory){
            if($jokeCategory->categoryId==$categoryId){
                return true;
            }
        }
        return false;
    }
    public function clearCategories(){
        $this->jokeCategoriesTable->deleteWhere('jokeId', $this->id);
    }
}