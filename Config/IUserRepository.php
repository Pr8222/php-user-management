<?php

namespace Config;

interface IUserRepository
{
    public function getAllUsers();
    public function getUserById($id);
    public function addNewUser(array $data);
    public function updateUser($id, array $data);
    public function deleteUser($id);
}