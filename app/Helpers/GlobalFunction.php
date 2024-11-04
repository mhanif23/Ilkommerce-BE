<?php

/**
 * by aughyvikrii@gmail.com
 * Autoloaded file
 * always use function_exists
 *
 *   if(!function_exists('example_function')) {
 *       function example_function() {
 *
 *       }
 *   }
 */

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Models\Role;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;

$GLOBALS['__month_id'] = [
    "", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];
$GLOBALS['arr_bulan'] = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

if (!function_exists('rest_error')) {
    /**
     * Return response error formatter
     *
     * @param string $message
     *
     * @return JsonResponse
     */

    function rest_error($data = null, String $message = null): JsonResponse
    {
        return response()->json([
            'metadata' => [
                'status' => 'error',
                'message' => $message,
                'errors' => $data,
            ],
            'response' => $data,
        ], 500);
    }
}
if (!function_exists('get_arr_bulan')) {
    function get_arr_bulan()
    {
        return $GLOBALS['arr_bulan'];
    }
}

if (!function_exists('rest_success')) {
    /**
     * Return response success formatter
     *
     * @param string $message
     *
     * @return JsonResponse
     */

    function rest_success($data = null, String $message = null): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => $message,
            'data' => $data
        ]);
    }
}

// if (!function_exists('response_success')) {

//     /**
//      * return response formatter
//      *
//      * @param String $message
//      *
//      * @return JsonResponse
//      */

//     function response_success($data = null, String $message = null): JsonResponse
//     {
//         return ResponseFormatter::success($data, $message);
//     }
// }

// if (!function_exists('response_error')) {
//     /**
//      * return response formatter
//      *
//      * @param String $message
//      * @param Array $errors
//      * @param Int $code
//      *
//      * @return JsonResponse
//      */

//     function response_error($data = null, String $message = null, array $errors = [], Int $code = 400): JsonResponse
//     {
//         return ResponseFormatter::error($data, $message, $errors, $code);
//     }
// }

// if (!function_exists('is_debug')) {
//     /**
//      * return debug status
//      *
//      * @return Bool
//      */

//     function is_debug(): Bool
//     {
//         $debug = config('app.debug');
//         return $debug === true || $debug == 'true';
//     }
// }

// if (!function_exists('throw_error')) {

//     /**
//      * throw error rest exceotion
//      *
//      * @param RestException $e
//      *
//      * @return JsonResponse
//      */
//     function throw_error(RestException $e): JsonResponse
//     {
//         if ($e->rollbackTrans()) DB::rollBack();
//         return response_error($e->getData(), is_debug() ? $e->getMessage() : "Internal server error", $e->getErrors(), $e->getCode());
//     }
// }

// if (!function_exists('webapi')) {

//     /**
//      * get route with prefix webapi.
//      *
//      * @param String $routename
//      * @param Mix|Array $params
//      *
//      * @return String
//      */
//     function webapi(String $routename, Mix|array|Int $params = null): String
//     {
//         return route('webapi.' . $routename, $params);
//     }
// }

if (!function_exists('date_translate')) {
    /**
     * Translate date to indonesia format
     *
     * @param String $date Y-m-d
     * @param String $format
     *
     * @return String
     */

    function date_translate(String $date = null, String $format = 'l, d F Y'): String
    {
        if (!$date) return '-';

        return Carbon::parse(date('Y-m-d H:i:s', strtotime($date)))->translatedFormat($format);
    }
}

if (!function_exists('datetime_translate')) {
    /**
     * Translate datetime to indonesia format
     *
     * @param String $date Y-m-d
     * @param String $format
     *
     * @return String
     */

    function datetime_translate(String $date = null, String $format = 'l, d F Y - H:i'): String
    {
        return date_translate($date, $format);
    }
}

if (!function_exists('user_photo')) {
    /**
     * return user photo
     *
     * @param User $user
     *
     * @return String
     */

    function user_photo(User $user = null): String
    {
        return @$user->foto ?: '/images/profile.svg';
    }
}

if (!function_exists('select2_response')) {

    /**
     * return select2 response format
     * append other options at last page
     * append first option at first page
     *
     * @param Array[Array] $results
     * @param Bool $morePage default false
     *
     * @return JsonResponse
     */

    function select2_response(array $results, $morePage = false): JsonResponse
    {
        if (
            !$morePage
            && !request()->input('abort')
            && $other = request()->input('other_option')
        ) {
            $other_option = explode(",", $other);
            $key = @$other_option[0] ?: 'other';
            $label = @$other_option[1] ?: 'Lainnya';

            $results[] = [
                'id' => $key,
                'text' => $label
            ];
        }

        if (
            request()->input('page', 1) <= 1 // Jika halaman 1
            && $first_option = request()->input('first_option') // dan ada first option
        ) {
            $first_option = explode(",", $first_option);
            $key = @$first_option[0] ?: 'all';
            $label = @$first_option[1] ?: 'Semua Pilihan';

            array_unshift($results, [
                'id' => $key,
                'text' => $label
            ]);
        }

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $morePage
            ]
        ]);
    }
}

if (!function_exists('my_type')) {

    /**
     * return user login type
     * define by session
     *
     * @return Int
     */

    function my_type(): Int
    {
        return (int) @auth()->user()->user_type_id;
    }
}

if (!function_exists('iam_admin')) {
    /**
     * check if user login is admin
     *
     * @return Bool
     */

    function iam_admin(): Bool
    {
        return my_type() === 1;
    }
}

if (!function_exists('my_level')) {
    /**
     * return user login type
     * define by session
     *
     * @return Int
     */

    function my_level()
    {
        return (int) @auth()->user()->user_type_id;
    }
}

if (!function_exists('back_url')) {
    /**
     * return previous url
     *
     * @param String $default if previous doesn't exists then use this
     *
     * @return String
     */

    function back_url(String $default = null): String
    {
        $currentPath = '/' . request()->path();
        $prevUrl = str_replace(url('/'), '', url()->previous());

        return $prevUrl === $currentPath ? $default : $prevUrl;
    }
}

if (!function_exists('unlink_user_foto')) {
    /**
     * remove user photo from storage
     *
     * @param String $foto url on public
     *
     * @return Bool
     */

    function unlink_user_foto(String $foto): Bool
    {
        return @unlink(storage_path("app/public/" . str_replace("/storage/", "", $foto)));
    }
}

// if (!function_exists('select_option_tahun')) {
//     /**
//      * create select option for year
//      *
//      * @param Int|null $selected selected option
//      * @param Int|null $start start year default 2015
//      * @param Int|null $end end year default current year
//      *
//      * @return String
//      */

//     function select_option_tahun(Int|null $selected = null, Int|null $start = null, Int|null $end = null): String
//     {
//         if (!$start) $start = 2015;
//         if (!$end) $end = date('Y');

//         $options = "<option value='0'>-- Pilih Tahun --</option>";

//         for ($i = $end; $i >= $start; $i--) {
//             $is_selected = $selected === $i ? "selected" : "";

//             $options .= '<option value="' . $i . '" ' . $is_selected . '>' . $i . '</option>';
//         }

//         return $options;
//     }
// }


if (!function_exists('arrChangeKeyFromValue')) {
    function arrChangeKeyFromValue($array_source, $column_name)
    {
        $arr_results = [];

        foreach ($array_source as $value)
            $arr_results[$value[$column_name]] = $value;

        return $arr_results;
    }
}

if (!function_exists('numberFormat')) {
    function numberFormat($angka, $koma = 0)
    {
        return number_format($angka, $koma, ",", ".");
    }
}

if (!function_exists('encryptData')) {
    function encryptData($data)
    {
        return Crypt::encrypt($data);
    }
}
if (!function_exists('decryptData')) {
    function decryptData($encrypted_data)
    {
        $result = NULL;

        try {
            $result = Crypt::decrypt($encrypted_data);
        } catch (DecryptException $e) {
            # Invalid Payload
        }

        return $result;
    }
}

// if (!function_exists('CreateNotification')) {
//     function CreateNotification($judul, $deskripsi, $type, $url = null, $user_id = [], $role_id = [])
//     {
//         try {
//             if (!empty($user_id) && $type == 'to_user') {
//                 foreach ($user_id as $key => $value) {
//                     $notif = new Notification;
//                     $notif->judul = $judul;
//                     $notif->deskripsi = $deskripsi;
//                     $notif->type = $type;
//                     $notif->url = $url;
//                     $notif->user_id = $value;
//                     $notif->role_id = $role_id;
//                     $notif->save();
//                 }
//             }

//             if (!empty($role_id) && $type == 'to_role') {
//                 foreach ($role_id as $key => $value) {
//                     $notif = new Notification;
//                     $notif->judul = $judul;
//                     $notif->deskripsi = $deskripsi;
//                     $notif->type = $type;
//                     $notif->url = $url;
//                     $notif->user_id = $user_id;
//                     $notif->role_id = $value;
//                     $notif->save();
//                 }
//             }

//             if ($type == 'to_all') {
//                 $notif = new Notification;
//                 $notif->judul = $judul;
//                 $notif->deskripsi = $deskripsi;
//                 $notif->type = $type;
//                 $notif->url = $url;
//                 $notif->save();
//             }
//         } catch (\Throwable $th) {
//             //throw $th;
//         }
//     }
// }

if (!function_exists('getIDRoleByName')) {
    function getIDRoleByName($name)
    {
        try {
            $data = Role::where('name', $name)->first();
            if ($data) {
                return $data->id;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

if (!function_exists('getIDUserByBadanUsaha')) {
    function getIDUserByBadanUsaha($id)
    {
        try {
            $data = User::where('badan_usaha_id', $id)->first();
            if ($data) {
                return $data->id;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

if (!function_exists('setActivityLog')) {
    function setActivityLog($description = null, $custom_user_id = null, $httpMethod = null, $customNewValues = [], $customOldValues = [])
    {
        $userId = $custom_user_id ?? Auth::id();

        Audit::create([
            'user_id' => $userId,
            'event' => $httpMethod ?? 'READ',
            'auditable_type' => 'App\Models\User',
            'auditable_id' => $userId,
            'old_values' => !empty($customOldValues) ? $customOldValues : null,
            'new_values' => !empty($customNewValues) ? $customNewValues : null,
            'url' => request()->url(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'tags' => ['activity', $description],
            'created_at' => Carbon::now(),
        ]);
    }
}
