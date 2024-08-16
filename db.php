<?php
require 'vendor/autoload.php';
use Carbon\Carbon;

class Model
{

    protected $connection;
    protected $query;
    protected $show_errors = TRUE;
    protected $query_closed = TRUE;
    public $query_count = 0;

    public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = 'blogs', $charset = 'utf8')
    {
        $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($this->connection->connect_error) {
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset($charset);
    }

    public function query($query)
    {
        if (!$this->query_closed) {
            $this->query->close();
        }
        if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
            if ($this->query->errno) {
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
            $this->query_count++;
        } else {
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }


    public function fetchAll($callback = null)
    {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break')
                    break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function fetchArray()
    {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function close()
    {
        return $this->connection->close();
    }

    public function numRows()
    {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    public function affectedRows()
    {
        return $this->query->affected_rows;
    }

    public function lastInsertID()
    {
        return $this->connection->insert_id;
    }

    public function error($error)
    {
        if ($this->show_errors) {
            exit($error);
        }
    }

    private function _gettype($var)
    {
        if (is_string($var))
            return 's';
        if (is_float($var))
            return 'd';
        if (is_int($var))
            return 'i';
        return 'b';
    }

}

class Post extends Model
{
    private $id;
    private $title;
    private $content;
    private $author;
    private $created_at;

    public function getTitle()
    {
        return $this->title;
    }

    public function createPost()
    {
        if (isset($_POST["send"])) {
            $title = trim(htmlspecialchars($_POST['title']));
            $content = trim(htmlspecialchars($_POST['content']));
            $author = trim(htmlspecialchars($_POST['author']));
            $created_at = Carbon::now();
            $sql = "insert into blogs (title, content, author, created_at) values ('$title', '$content', '$author', '$created_at')";
            $db = new Model();
            $val = $db->query($sql);
            if ($val) {
                header('location: index.php');
            } else {
                echo "<script type='text/javascript'> alert('ERROR!');</script>";
            }
        } else {
            echo "<script type='text/javascript'> alert('ERROR!');</script>";
        }
    }

    public function getPostById($id)
    {
        $sql = "select * from blogs where id = $id";
        $db = new Model();
        $val = $db->query($sql)->fetchAll();
        if ($val) {
            return $val;
        } else {
            echo "Error";
        }
    }

    public function updatePost($id)
    {
        $title = $this->test_input($_POST['title']);
        $content = $this->test_input($_POST['content']);
        $author = $this->test_input($_POST['author']);
        $sql2 = "update blogs set title = '$title', content = '$content', author = '$author' where id = '$id'";
        $db = new Model();
        $db->query($sql2);
        header('location: update.php?id=' . $id . '.php');

    }

    public function test_input($data) { 
        $data = trim($data); 
        $data = strip_tags($data);
        $data = stripslashes($data); 
        $data = htmlspecialchars($data); 
        return $data; 
    } 

    public function deletePost()
    {
        $id = $_GET['id'];
        $sql = "delete from tasks where id = '$id'";
        $db = new Model();
        $val = $db->query($sql);
        if ($val) {
            header('location: index.php');
        }
    }

}
?>