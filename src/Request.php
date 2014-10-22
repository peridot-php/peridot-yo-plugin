<?php
namespace Peridot\Plugin\Yo;

use GuzzleHttp\Client;

class Request
{
    /**
     * Yo api endpoint
     */
    const URL = 'http://api.justyo.co';

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
     * Yo!
     */
    public function yo()
    {
        $client = new Client(['base_url' => static::URL]);
        foreach($this->users as $user) {
            $client->post('/yo', [
                'headers' => ["Content-type" => "application/x-www-form-urlencoded\r\n"],
                'body' => $this->getQuery($user),
                'future' => true
            ]);
        }
    }

    /**
     * @return array
     */
    public function getContextOptions()
    {
        $options = [];
        foreach ($this->users as $user) {
            $options[] = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => $this->getQuery($user)
                ]
            ];
        }
        return $options;
    }

    /**
     * @param $user
     * @return string
     */
    public function getQuery($user)
    {
        $data = $this->getData($user);
        $query = http_build_query($data);
        return $query;
    }

    /**
     * @param $user
     * @return array
     */
    public function getData($user)
    {
        $data = ['api_token' => $this->token, 'username' => $user];
        if (isset($this->link)) {
            if (is_callable($this->link)) {
                $data['link'] = call_user_func($this->link);
                return $data;
            } else {
                $data['link'] = $this->link;
                return $data;
            }
        }
        return $data;
    }
} 
