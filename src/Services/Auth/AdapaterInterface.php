<?php namespace Montopolis\MagicAuth\Services\Auth;

/**
 * Interface AdapterInterface
 * 
 * Defines the contract that the magic auth system will use to interact with the authentication system.
 * 
 * @package Montopolis\MagicAuth\Services\Auth
 */
interface AdapterInterface
{
    /**
     * @param $email string Email address which should be used to identify the user in the database.
     * @return mixed The user object.
     */
    public function findByEmail($email);

    /**
     * @param $email string Email address which identifies the user that will be logged in.
     * @return boolean Returns TRUE if user is logged in successfully, FALSE otherwise.
     */
    public function loginByEmail($email);
}