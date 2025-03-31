<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * StudyModel handles the interaction with the 'study' table in the database.
 */
class StudyModel extends Model
{
    // Table name in the database
    protected $table = 'study';
    // Primary key of the table
    protected $primaryKey = 'ID_Study';

    // Fields allowed for mass assignment
    protected $allowedFields = [
        'ID_Terms',
        'ID_Courses',
        'Title_name',
        'Firstname_S',
        'Lastname_S',
        'Phone_S',
        'Firstname_P',
        'Lastname_P',
        'Phone_P',
        'Status_Price',
        'Total',
        'Discount',
        'Price_thai',
        'balance',
        'HowToPay'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get study records based on the provided term and course IDs.
     *
     * @param int $termId
     * @param int $courseId
     * @return array
     */
    public function getStudyByTermAndCourseId(int $termId, int $courseId): array
    {
        $result = $this->where('ID_Terms', $termId)
                       ->where('ID_Courses', $courseId)
                       ->findAll();
    
        log_message('debug', 'Study Data: ' . json_encode($result)); // Log ค่า balance
    
        return $result;
    }
    
    

    public function getStudentCountByTerm($termId)
    {
        $count = $this->where('ID_Terms', $termId)->countAllResults();

        log_message('debug', 'getStudentCountByTerm - Term ID: ' . $termId . ' - Count: ' . $count);

        return $count;
    }

    public function getStudiesWithCourses($length, $start, $search = '', $termId = null)
    {
        $builder = $this->db->table($this->table)
            ->select('study.ID_Study, study.Firstname_S, study.Lastname_S, study.balance, course.Course_name')
            ->join('course', 'study.ID_Courses = course.ID_Courses', 'left')
            ->orderBy('study.ID_Study', 'ASC');
    
        if ($termId) {
            $builder->where('study.ID_Terms', $termId);
        }
    
        if (!empty($search)) {
            $builder->groupStart()
                ->like('study.Firstname_S', $search)
                ->orLike('study.Lastname_S', $search)
                ->groupEnd();
        }
    
        return $builder->limit($length, $start)->get()->getResultArray();
    }
    
    public function countStudiesWithCourses($search = '', $termId = null)
    {
        $searchTerms = explode(' ', trim($search));

        $builder = $this->select('study.ID_Study')
            ->join('course', 'study.ID_Courses = course.ID_Courses', 'left');

        // Apply search filter
        if (!empty($searchTerms)) {
            foreach ($searchTerms as $term) {
                if (!empty($term)) {
                    $builder->groupStart()
                        ->like('study.Firstname_S', $term)
                        ->orLike('study.Lastname_S', $term)
                        ->groupEnd();
                }
            }
        }

        // Apply termId filter if provided
        if ($termId) {
            $builder->where('study.ID_Terms', $termId);
        }

        try {
            // Execute the query and return the count of results
            return $builder->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting studies: ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Delete a study record based on the ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStudy(int $id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting study: ' . $e->getMessage());
            return false;
        }
    }

    public function upsertStudy(array $data)
    {
        if (empty($data['ID_Courses']) || empty($data['ID_Terms'])) {
            return false; // ป้องกันการเพิ่มข้อมูลที่ไม่ครบ
        }

        if (isset($data['ID_Study']) && $this->find($data['ID_Study'])) {
            return $this->update($data['ID_Study'], $data);
        } else {
            return $this->insert($data);
        }
    }

    public function getStudentCountByCourse($courseId)
    {
        return $this->where('ID_Courses', $courseId)->countAllResults();
    }
}
