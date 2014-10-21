<?php
namespace Peridot\Plugin\Yo;

class Request 
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var array
     */
    protected $users;

    /**
     * @var string
     */
    protected $link;

    /**
     * @param $token
     */
    public function __construct($token, array $users)
    {
        $this->token = $token;
        $this->users = $users;
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        $queries = [];
        foreach ($this->users as $user) {
            $queries[] = $this->getQuery($user);
        }
        return $queries;
    }

    /**
     * @param string|callable $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return array
     */
    public function getContextOptions()
    {
        $options = [];
        foreach ($this->users as $user) {
            $options[] = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => $this->getQuery($user)
                )
            );
        }
        return $options;
    }

    /**
     * @param $user
     * @return string
     */
    public function getQuery($user)
    {
        $data = ['api_token' => $this->token, 'username' => $user];
        if (isset($this->link)) {
            if (is_callable($this->link)) {
                $data['link'] = call_user_func($this->link);
            } else {
                $data['link'] = $this->link;
            }
        }
        $query = http_build_query($data);
        return $query;
    }
} 
