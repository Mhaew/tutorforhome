<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CourseModel handles the interaction with the 'Course' table in the database.
 */
class PaymantModel extends Model
{
    protected $table = 'study'; // Table name in the database
    protected $primaryKey = 'ID_Study'; // Primary key of the table

    protected $allowedFields = ['ID_Terms', 'ID_Courses', 'Title_name', 'Firstname_S', 'Lastname_S', 'Phone_S', 'Firstname_P', 'Lastname_P', 'Phone_P', 'Status_Price', 'Total', 'Discount', 'Price_thai', 'balance', 	'HowToPay',]; // Fields allowed for mass assignment

    public function getStudyByTermAndCourseId(int $termId, int $courseId): array
    {
        return $this->where('ID_Terms', $termId)->where('ID_Courses', $courseId)->findAll();
    }
}
