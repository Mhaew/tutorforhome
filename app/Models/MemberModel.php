<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'id_member';
    protected $allowedFields = ['name_member', 'class', 'phone', 'email'];
    protected $useTimestamps = true;
}
