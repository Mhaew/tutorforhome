<?php namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'course';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_term', 'Course_name', 'Price_DC', 'id_user', 'open'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getCourseById(int $id): ?array
    {
        return $this->where('id', $id)->first();
    }

    public function getCoursesByTermId(int $termId): array
    {
        return $this->where('id_term', $termId)->findAll();
    }

    public function isCourseExists(string $courseName, int $termId): bool
    {
        return $this->where('Course_name', $courseName)
                    ->where('id_term', $termId)
                    ->countAllResults() > 0;
    }

    public function createCourse(array $data): bool
    {
        if ($this->isCourseExists($data['Course_name'], $data['id_term'])) {
            return false;
        }
        return $this->insert($data) !== false;
    }

    public function updateCourse(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deleteCourse(int $id): bool
    {
        return $this->delete($id);
    }
}
