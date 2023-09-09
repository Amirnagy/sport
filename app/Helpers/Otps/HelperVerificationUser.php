<?php
namespace App\Helpers\Otps;


use Carbon\Carbon;
use App\Models\Otp;
use App\Models\User;
use App\Helpers\Otps\AbstractOtp;

class HelperVerificationUser extends AbstractOtp
{

    public function __construct(User $user , Otp $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }


    public function resendOtp()
    {
        if($this->getUserOtp()){
            $this->deleteOtp();
            $this->buildOtp();
        }
        return $this->otp;
    }

}
