<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FController extends Controller
{
    public static $default = 'default.jpg';

    public static $diskName = [
        'profile-picture' => 'profile-pictures',
        'event' => 'banner',
        'photo' => 'others',
    ];

    public static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg', 'gif'],
        'post' => ['mp3', 'mp4', 'gif', 'png', 'jpg', 'jpeg'],
    ];

    private static function isValidType(string $type)
    {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(string $type)
    {
        return asset($type.'/'.self::$default);
    }

    private static function getFileName(string $type, int $id)
    {

        $fileName = null;
        switch ($type) {
            case 'profile':
                $fileName = User::find($id)->profile_image;
                break;
            case 'post':

                break;
        }

        return $fileName;
    }

    public static function get(string $type, int $id)
    {

        if (! self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type.'/'.$fileName);
        }

        return self::defaultAsset($type);
    }

    public static function upload(Request $request)
    {

        $file = $request->file('file');
        $type = $request->type;
        $id = $request->id;
        $extension = $file->getClientOriginalExtension();

        $fileName = $file->hashName();

        $request->file->storeAs($type, $fileName, self::$diskName);

        return redirect()->back();
    }
}
