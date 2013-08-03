<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 24/7/13
 * Time: 3:45 PM
 * To change this template use File | Settings | File Templates.
 */
class BlogController extends LibController{

    public function home(){

        $blog=new BlogModel();
        $blogs=$blog->getAll();
        $this->render('Home',array('blogs'=>$blogs));

    }
    public function newBlog(){
        $this->render('create');
    }
    public function createBlog(){
        $blog=new BlogModel();
        $blog->title=$_POST['title'];
        $blog->description=$_POST['desc'];
        $blog->encode();
        $blog->save();
    }
    public function filter(){
        $blog=new BlogModel();
        if(isset($_POST['submit'])){
            $blogs=$blog->findByCondition(array('title','=',$_POST['title']));
            $this->render('Home',array('blogs'=>$blogs));
        }
    }
    public function specific(){
        $blog=new BlogModel();

        if(isset($_GET['id'])){
            $blogs=$blog->findByCondition(array('id','=',$_GET['id']));
            $queryString="select u.first_name,u.last_name from user u,blog b where u.id=b.user_id and b.id=".$_GET['id'];
            $user=$blog->query($queryString);
            $queryString="select c.id,c.comment,c.isApprove,u.first_name,u.last_name from comment c,user u where c.user_id=u.id and c.blog_id=".$_GET['id'];
            $comments=$blog->query($queryString);
            $this->render('Specific',array('blogs'=>$blogs,'comments'=>$comments,'user'=>$user));
        }
    }
    public function approve(){
        $blog=new BlogModel();
        $blog->id=$_GET['id'];
        $blog->isApprove='Yes';
        $blog->save();
        $this->redirect('/specific/'.$_GET['id']);
    }
    public function edit(){
        $blog=new BlogModel();
        $blogs=$blog->findByCondition(array('id','=',$_GET['id']));
        $this->render('Edit',array('blogs'=>$blogs));
    }
    public function editBlog(){
        $blog=new BlogModel();
        $blog->id=$_POST['id'];
        $blog->title=$_POST['title'];
        $blog->description=$_POST['desc'];
        $blog->encode();
        $blog->save();
        $this->redirect('/specific/'.$_POST['id']);
    }
    public function delete(){
        $blog=new BlogModel();
        if($blog->delete(array('id','=',$_GET['id']))==true){
            $blogs=$blog->getAll();
            $this->render('Home',array('blogs'=>$blogs));
        }
        else{
            throw new ApplicationException('Could not delete specified blog');
        }
    }
}