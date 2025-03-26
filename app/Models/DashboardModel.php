<?php namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $table = 'term_course'; // Table name in the database
    protected $primaryKey = 'id'; // Primary key of the table

    protected $allowedFields = ['Term_name', 'user_id']; // Fields allowed for mass assignment

    // Use timestamps for created_at and updated_at fields
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

}
