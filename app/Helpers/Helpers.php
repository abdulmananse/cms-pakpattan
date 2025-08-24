<?php

use App\Models\Level1;
use App\Models\Level2;
use App\Models\Role;
use App\Models\School;
use App\Models\Setting;
use App\Models\SMSLog;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentPromotionHistory;
use App\Models\User;
use App\Models\UserDistrict;
use App\Models\DEODistrictsTehsil;
use App\Models\DEOClasses;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

/**
* Check Active Route
*
* @param $route
* @param $output
* @return mix
*/
if (!function_exists('isActiveRoute')) {

    function isActiveRoute($route, $output = "active") {
        if (Route::current()->uri == $route)
            return $output;
    }

}

/**
* Active Route
*
* @param $paths
* @param $class
* @return mix
*/
if (!function_exists('setActive')) {

    function setActive($paths, $class = false, $className = 'active') {
        foreach ($paths as $path) {

            if (Request::is($path . '*')) {
                if ($class)
                    return ' class=menu-item-active';
                else
                    return ' ' . $className;
            }
        }
    }

}

/**
* Active Routes
*
* @param $routes
* @param $output
* @return mix
*/
if (!function_exists('areActiveRoutes')) {

    function areActiveRoutes(Array $routes, $output = "active") {
        foreach ($routes as $route) {
            if (Route::current()->uri == $route) {
                return $output;
            }
        }
    }

}

/**
* Get Setting Value
* @param $key
* @return mix
*/
if (!function_exists('getSettingValue')) {

    function getSettingValue($key)
    {
        $setting = collect(Setting::cache())->where('key', $key)->first();
        return ($setting) ? $setting['value'] : null;
    }

}

if (!function_exists('getComplaintStatus')) {

    function getComplaintStatus($status)
    {
        $statusText = 'Pending';
        if ($status == 1) {
            $statusText = 'Resolved';
        }elseif ($status == 2) {
            $statusText = 'Rejected';
        }
        return $statusText;
    }

}

if (!function_exists('getComplaintStatusBadge')) {

    function getComplaintStatusBadge($complaint)
    {
        $badge = '<span style="overflow: visible; position: relative; width: 130px;">';
        $status = $complaint->complaint_status;
        $department = $complaint->department_id;

        if ($status == 0 && $department > 0) {
            $badge .= '<a href="#" class="badge bg-info">Assigned to Department</a>';
        } elseif ($status == 1) {
            $badge .= '<a href="#" class="badge bg-success">Resolved</a>';
        } elseif ($status == 2) {
            $badge .= '<a href="#" class="badge bg-danger">Rejected</a>';
        } else {
            $badge .= '<a href="#" class="badge bg-info">Pending</a>';
        }
        
        $badge .= '</span>';    
        return $badge;
    }

}

/**
* Get Role Name
*
* @return mix
*/
if (!function_exists('roleName')) {

    function roleName()
    {
        $roleName = '';
        $user = Auth::user();
        if (@$user) {
            if (@$user->getRoleNames()->first()) {
                $roleName = $user->getRoleNames()->first();
            }
        }
            
        return $roleName;
    }

}

/**
* Get Role Name
*
* @return mix
*/
if (!function_exists('isSuperAdmin')) {

    function isSuperAdmin()
    {
        $user = Auth::user();
        return $user->hasRole('Super Admin') ? true : false;
    }

}

/**
* Get Application Status Badge
*
* @param $status
* @return mix
*/
if (!function_exists('getStatusBadge')) {

    function getStatusBadge($status = 1)
    {
        $badge = '<span style="overflow: visible; position: relative; width: 130px;">';
                    
        switch ($status) {
            case 1:
                $badge .= '<a href="#" class="badge bg-success" > Active </a>';
                break;
            default:
                $badge .= '<a href="#" class="badge bg-danger" > In Active </a>';
        }
        
        $badge .= '</span>';    
        return $badge;
    }

}

/**
* Get Nadra Status Badge
*
* @param $status
* @return mix
*/
if (!function_exists('getNadraStatusBadge')) {

    function getNadraStatusBadge($status = 1)
    {
        $badge = '<span style="overflow: visible; position: relative; width: 130px;">';
                    
        switch ($status) {
            case 1:
                $badge .= '<a href="#" class="badge bg-success" > Verified </a>';
                break;
            case 2:
                $badge .= '<a href="#" class="badge bg-danger" > Unverified </a>';
                break;
            default:
                $badge .= '<a href="#" class="badge bg-primary" > Pending </a>';
        }
        
        $badge .= '</span>';    
        return $badge;
    }

}

/**
* Get Uuid
*
* @return mix
*/
if (!function_exists('getUuid')) {

    function getUuid()
    {
        //(string) \Uuid::generate(4);
        return \DB::raw('uuid()');
    }

}

/**
* dd with json
*
* @return mix
*/
if (!function_exists('dj')) {

    function dj($data)
    {
        return response()->json([
            'data' => $data
        ], 200);
        exit;
    }

}

/**
 * Success Response
 *
 * @param $message
 * @return JSON
 */
if (!function_exists('successResponse')) {
    function successResponse($message, $response = []) {
        $responseData = [
            'message' => $message,
            'code' => 200,
            'status' => true,
        ];

        $response = array_merge($responseData, $response);

        return response()->json($response, 200);
    }
}

/**
 * Error Response
 *
 * @param $message
 * @return JSON
 */
if (!function_exists('errorResponse')) {
    function errorResponse($message, $response = [], $status = 400) {
        $responseData = [
            'message' => $message,
            'code' => $status,
            'status' => false,
        ];

        $response = array_merge($responseData, $response);

        return response()->json($response, 200);
    }
    
}

function load_pdf($pdf) {
    header('Content-Type: application/pdf');
    $image_name = image_name_decode($pdf);
    readfile($image_name);
}

/**
* add Dashes In CNIC
*
* @return mix
*/
if (!function_exists('addDashesInCNIC')) {

    function addDashesInCNIC($cnic)
    {
        return preg_replace("/^(\d{5})(\d{7})(\d{1})$/", "$1-$2-$3", $cnic);
    }

}

/**
* add Dash In Mobile
*
* @return mix
*/
if (!function_exists('addDashInMobile')) {

    function addDashInMobile($mobile)
    {
        return preg_replace("/^(\d{4})(\d{7})$/", "$1-$2", $mobile);
    }

}


/**
* add Dash In Mobile
*
* @return mix
*/
if (!function_exists('loggedInUserId')) {

    function loggedInUserId()
    {
        return Auth::id();
    }

}


/**
* return logged in school id
*
* @return mix
*/
if (!function_exists('loggedInSchoolId')) {

    function loggedInSchoolId()
    {
        return optional(Auth::user()->school)->id;
    }

}

/**
* return school id of logged in teacher
*
* @return mix
*/
if (!function_exists('getLoggedInTeacherSchoolId')) {

    function getLoggedInTeacherSchoolId()
    {
        return optional(Auth::user()->teacher->school)->id;
    }

}

/**
* return logged in teacher id
*
* @return mix
*/
if (!function_exists('getLoggedInTeacherId')) {

    function getLoggedInTeacherId()
    {
        return optional(Auth::user()->teacher)->id;
    }

}

/**
* Get Role Name
*
* @return mix
*/
if (!function_exists('hasRole')) {

    function hasRole($role)
    {
        $user = Auth::user();
        return $user->hasRole($role) ? true : false;
        
    }

}


/**
* Get Role Name
*
* @return mix
*/
if (!function_exists('getSchoolTehsilId')) {

    function getSchoolTehsilId()
    {
        $schoolId = loggedInSchoolId();

        $school = School::select('level_4_id')
                ->with('unionCouncil')
                ->find($schoolId);

        if($school) {
            return $school->unionCouncil->level_3_id;
        }
        return false;
        
    }

}

/**
* Get Teacher related keys
*
* @return mix
*/
if (!function_exists('getTeacherRelatedKeys')) {

    function getTeacherRelatedKeys($teacherId, $keys = [])
    {
        $keys = collect($keys);
        $teacher = Teacher::where('id', $teacherId)
                        ->with('school:level_4_id,id')
                        ->first();

        $teacherData = [];
        if($teacher && $keys->contains('school_id')) {
            $teacherData['school_id'] = $teacher->school->id;
        }
        if($teacher && $keys->contains('level_4_id')) {
            $teacherData['level_4_id'] = $teacher->school->level_4_id;
        }

        return $teacherData;
    }

}


/**
* Get partition column value of students table
* @param TehsilId (level_2_id)
* @param status
* @return mix
*/
if (!function_exists('getStudentPart')) {

    function getStudentPart($tehsilId, $status = 1)
    {
        return ($status * 10000) + $tehsilId;        
    }

}

/**
* Attendance Status Colors
* @return Array
*/
if (!function_exists('getAttendanceStatusColors')) {

    function getAttendanceStatusColors () {
        return [
            'Unmarked' => '#656565',
            'Absent' => '#FB303C', 
            'Present' => '#35BB9F', 
            'Casual Leave' => '#EF9E24', 
            'Medical Leave' => '#EF9E24', 
            'Holiday' => '#FB303C'
        ];
    }

}

/**
* Get Financial Years
* @return Array
*/
if (!function_exists('getFinancialYears')) {

    function getFinancialYears () {
        for ($y = 2023; $y <= date('Y'); $y++) {
            $year = $y . '-' . substr($y+1, 2);
            $years[$year] = $year;
        }
        
        return $years;
    }

}

/**
* Get Months
* @return Array
*/
if (!function_exists('getMonths')) {

    function getMonths () {
        return ['1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'];
    }

}

/**
* Get School Levels
* @return Array
*/
if (!function_exists('getSchoolLevels')) {

    function getSchoolLevels () {
        return [
            'Primary' => 7,
            'Class 6' => 8,
            'Class 7' => 9,
            'Middle' => 10,
            'Elementary' => 10,
            'High' => 12,
            'Secondary' => 12,
            'High. Sec' => 14,
            'Higher Secondary' => 14
        ];
    }

}

/**
* Count of Licenses
* @return Number
*/
if (!function_exists('totalLicenses')) {

    function totalLicenses () {
        $totalLicenses = User::role('Licensee');

        if (request()->filled('t') && request()->t > 0) {
            $totalLicenses->with('licensee_schools.markaz');
            $totalLicenses->whereHas('licensee_schools.markaz', function (Builder $query) {
                $query->where('level_2_id', request()->t);
            });
        } else if (request()->filled('d') && request()->d > 0) {
            $totalLicenses->with('licensee_schools.markaz.tehsil');
            $totalLicenses->whereHas('licensee_schools.markaz.tehsil', function (Builder $query) {
                $query->where('level_1_id', request()->d);
            });
        }

        return $totalLicenses->count();
    }

}

/**
* Count of AEOs
* @return Number
*/
if (!function_exists('totalAEOs')) {

    function totalAEOs () {
        return User::role('AEO')->active()->count();
    }

}

/**
* Count of Schools
* @return Number
*/
if (!function_exists('totalSchools')) {

    function totalSchools () {

        $role = roleName();
        if ($role == 'District Admin') {
            $schools = School::with('union_council')
                ->whereHas('union_council', function (Builder $query) {
                $query->whereIn('level_1_id', getUserDistrictIds());
            });
        } elseif ($role == 'Licensee') {
            $schools = School::where('user_id', Auth::id());
        } else {
            $schools = School::query();
        }

        if (request()->filled('d') && request()->d > 0) {
            $schools->whereHas('union_council', function (Builder $query) {
                $query->where('level_1_id', request()->d);
            });
        }

        if (request()->filled('t') && request()->t > 0) {
            $schools->whereHas('union_council', function (Builder $query) {
                $query->where('level_2_id', request()->t);
            });
        }

        return $schools->count();
    }

}

/**
 * Get User District Ids
 * @return Number
 */
if (!function_exists('getUserDistrictIds')) {
    function getUserDistrictIds($userId = null)
    {
        $userId = ($userId) ? $userId : Auth::id();
        return UserDistrict::where('user_id', $userId)->pluck('level_1_id');
    }
}



/**
* Count of Students
* @return Number
*/
if (!function_exists('totalStudents')) {

    function totalStudents () {
        $role = roleName();
        $students = Student::status(1);
        if ($role == 'District Admin') {
            $students->whereHas('union_council', function (Builder $query) {
                $query->whereIn('level_1_id', getUserDistrictIds());
            });
        } elseif ($role == 'Licensee') {
            $schoolIds = School::where('user_id', Auth::id())->pluck('id');
            $students = $students->whereIn('school_id', $schoolIds);
        } else if ($role == 'School') {
            $schoolIds = School::where('code', Auth::user()->username)->pluck('id');
            $students = $students->whereIn('school_id', $schoolIds);
        }

        if (request()->filled('d') && request()->d > 0) {
            $students->whereHas('union_council', function (Builder $query) {
                $query->where('level_1_id', request()->d);
            });
        }

        if (request()->filled('t') && request()->t > 0) {
            $students->whereHas('union_council', function (Builder $query) {
                $query->where('level_2_id', request()->t);
            });
        }

        return $students->count();
    }
}

/**
 * Get Inspection Flags
 * @return Array
 */
if (!function_exists('getInspectionFlags')) {

    function getInspectionFlags()
    {
        return [
            'monthly_attendance' => getSettingValue('monthly_inspection') == 'open' ? true : false,
            'physical_attendance' => getSettingValue('physical_inspection') == 'open' ? true : false
        ];
    }
}

/**
* Send Message to Mobile Number
*
* @param $number
* @param $message
* @param $language
* @return mix
*/
if (!function_exists('sendSms')) {

    function sendSms($number, $message, $type = null, $language = 'english')
    {
        if($number == '' || $number == null) {
            return false;
        }

        if (config('app.sms_enable')) {
            $firstDigit = substr($number, 0, 1);
            if ($firstDigit != 0) {
                $number = '0' . substr($number, 1);
            }

            $request = [
                'phone_no' => $number,
                'sms_text' => $message,
                'sec_key' => config('app.sms_key'),
                'sms_language' => $language
            ];

            $smsLog = SMSLog::create([
                'mobile' => $number,
                'request' => json_encode($request),
                'request_time' => date('Y-m-d H:i:s'),
                'type' => $type
            ]);

            try {
                $response = Http::withOptions([
                    'debug' => false,
                    'verify' => false,
                ])->asForm()
                    ->post(config('app.sms_url'), $request);

                $res = $response->object();
                $smsLog->response = $response->json();
                $smsLog->response_time = date('Y-m-d H:i:s');
                $smsLog->status = ($response->ok() && $res->status == 'success') ? 1 : 2;
                $smsLog->save();

                return $response->json();
            } catch (Exception $e) {

                $smsRes = json_encode([
                    'code' => $e->getCode(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'message' => $e->getMessage(),
                ]);

                $smsLog->response = $smsRes;
                $smsLog->response_time = date('Y-m-d H:i:s');
                $smsLog->status = 2;
                $smsLog->save();

                return false;
            }
        } 
    }

}

/**
 * Send Message to Mobile Number
 *
 * @param $number
 * @param $message
 * @return mix
 */
if (!function_exists('sendPasswordMessage')) {

    function sendPasswordMessage($number, $password)
    {
        $message = 'Your password is ' . $password;
        sendSms($number, $message, "reset_password");
    }
}

/**
* return school id of logged in teacher
*
* @return mix
*/
if (!function_exists('getLoggedInUserMobileNumber')) {

    function getLoggedInUserMobileNumber($user)
    {

        $userMobile = '';

        $role = roleName();

        $user = Auth::user();

        if($role == 'School') {
            $userMobile = $user->school->mobile;
        }
        if($role == 'Teacher') {
            $userMobile = $user->teacher->mobile;
        }

        return $userMobile;
    }

}


/**
* return returns the permissions of loggedin user role.
*
* @return mix
* @param $roleName
*/
if (!function_exists('getPermissionsOfLoggedInUser')) {

    function getPermissionsOfLoggedInUser($roleName)
    {

        $role = Role::findByName($roleName);
        $permissions = $role->getAllPermissions()->pluck('name')->toArray();

        return $permissions;
    }

}


/**
* Create Student Promotion Log
*
* @return mix
* @param $roleName
*/
if (!function_exists('createStudentPromotionHistory')) {

    function createStudentPromotionHistory($student, $part, $reason = 'Promote', $portal = 'Web')
    {
        if ($student) {
            StudentPromotionHistory::create([
                'student_id' => $student->id,
                'level_4_id' => $student->level_4_id,
                'school_id' => $student->school_id,
                'class_id' => $student->class_id,
                'section_id' => $student->section_id,
                'session_year_from' => date('Y') - 1,
                'session_year_to' => date('Y'),
                'reason' => $reason,
                'portal' => $portal,
                'part' => $part,
            ]);
            return true;
        }

        return false;
    }

}

/**
* Read file data
*
* @param $directory
* @param $fileName
* @return mix
*/
if (!function_exists('read_file')) {

    function read_file($fileName = false) {
        if (!$fileName) {
            return null;
        }
        

        if (strpos($fileName, '.pdf')) {
            return asset('images/pdf_icon.png');
        }
        
        return $fileName;
    }

}

/**
* Show Media Liabrary Pdf
*
* @param $directory
* @param $fileName
* @return mix
*/
if (!function_exists('showMediaLiabraryPdf')) {

    function showMediaLiabraryPdf($model = false, $fileName = null) {
        if ($model) {
            $mediaedia = $model->getMedia($fileName)->first();
            if($mediaedia) {
                $url = $mediaedia->getUrl();
                if (strpos($url, '.pdf')) {
                    return '<a target="_blank" href="'.$url.'"><img src="'.asset('images/pdf_icon.png').'" style="margin-top: 10px;" width="40" /></a>';
                }

                return $url;
            }
        }

        return null;
    }

}

if (!function_exists('getStudentSchoolLevel')) {

    function getStudentSchoolLevel ($school_type = null) {
        $level = 0;
        $school_levels = getSchoolLevels();
        if (array_key_exists($school_type,$school_levels))
        {
            $level = $school_levels[$school_type];
        }

        return $level;
    }

}

/**
 * Get DEO District Ids
 * @return Number
 */
if (!function_exists('getDEODistrictIds')) {
    function getDEODistrictIds($userId = null)
    {
        $userId = ($userId) ? $userId : Auth::id();
        return DEODistrictsTehsil::where('user_id', $userId)->pluck('level_1_id');
    }
}

/**
 * Get DEO Tehsils Ids
 * @return Number
 */
if (!function_exists('getDEOTehsilIds')) {
    function getDEOTehsilIds($userId = null)
    {
        $userId = ($userId) ? $userId : Auth::id();
        return DEODistrictsTehsil::where('user_id', $userId)->pluck('level_2_id');
    }
}


if (!function_exists('complaintSources')) {
    function complaintSources()
    {
        return ['Control Room','Online Form','Complaint Cell','SMU','Social Media','CM Complaint Cell','Email','Mobile App'];
    }
}

