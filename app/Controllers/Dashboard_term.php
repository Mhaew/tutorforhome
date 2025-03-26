<?php namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\DashboardModel;
use CodeIgniter\Controller;

class Dashboard_term extends Controller
{
    /**
     * Displays the dashboard with terms and courses related to the logged-in user.
     *
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function dashboard_term()
    {
        // Retrieve the session instance
        $session = session();

        // Get the user ID from the session
        $user_id = $session->get('id');

        // Redirect to login page if user is not logged in
        if (!$user_id) {
            return redirect()->to('/login');
        }

        // Create an instance of the DashboardModel to fetch terms
        $dashboardModel = new DashboardModel();

        // Create an instance of the CourseModel to fetch courses
        $courseModel = new CourseModel();

        // Fetch terms associated with the logged-in user
        // Fetching all terms from the system (not just the user's terms)
        $data['terms'] = $dashboardModel->findAll(); // ดึงข้อมูลเทอมทั้งหมดจากระบบ

        // Fetch courses associated with the logged-in user
        $data['courses'] = $courseModel->where('id_user', $user_id)->findAll();

        // Check if the user is logged in
        $data['isLoggedIn'] = $session->get('isLoggedIn') ?? false;

        // Set a message if no terms are found
        if (empty($data['terms'])) {
            $data['terms_message'] = 'ยังไม่มีข้อมูลเทอม';
        }

        // Set a message if no courses are found
        if (empty($data['courses'])) {
            $data['courses_message'] = 'ยังไม่มีข้อมูลคอร์สเรียน';
        }

        // Load the view with the data
        return view('dashboard_term', $data);
    }
}