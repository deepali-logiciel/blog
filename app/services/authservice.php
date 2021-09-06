<?php
namespace app\services;
use Carbon\Carbon;
use DB;
class authservice
{
    public function verify($token)
    {
        $token = $this->setExpireTime($token);
        return $token;
    }
    private function setExpireTime($token)
    {
        $expireTime = Carbon::now()->addDays(100)->timestamp;
        DB::table('oauth_access_tokens')->where('id',$token['access_token'])->update(['expire_time' => $expireTime]);
        $token['expires_in'] = 100;
        return $token;
    }
}
?>