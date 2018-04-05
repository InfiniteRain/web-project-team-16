<?php

require_once __DIR__ . '/Database.php';

class User
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $passwordHash;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var integer
     */
    public $type;

    /**
     * @var integer
     */
    public $specialty;

    /**
     * User constructor.
     * @param $id
     * @throws Exception
     */
    public function __construct($id)
    {
        $result = Database::query('SELECT * FROM SYSUSER WHERE id=?', $id);

        if (!isset($result[0])) {
            throw new Exception('User not found.');
        }

        $user = $result[0];

        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->passwordHash = $user['password'];
        $this->firstName = $user['first_name'];
        $this->lastName = $user['last_name'];
        $this->email = $user['email'];
        $this->type = $user['type'];
        $this->specialty = $user['specialty'];
    }
}