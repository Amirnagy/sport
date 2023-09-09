<?php
namespace App\Helpers\Otps;



use Carbon\Carbon;
use App\Models\User;
use App\Models\ResetPassword;
use App\Helpers\Otps\AbstractOtp;
use App\Models\ResetPassword as ModelReset;

class HelperResetPassword extends AbstractOtp
{

    public function __construct(User $user, ModelReset $otp)
    {
        // Call the parent class constructor
        $this->user = $user;
        // Additional property initialization
        $this->otp = $otp;

    }

    public function buildOtp(): ResetPassword{
        $this->createRow();
        return $this->otp;
	}
    protected function checkExpire(): bool {
        if ( Carbon::now() > $this->otp->expire_at)
        {
            $this->deleteOtp();
            return false;
        }
        return true;
	}

    public function attempOTP($otp): mixed {
        $message = '';
            if($this->getUserOtp())
            {
                if(!$this->checkExpire())
                {
                    return $message = 'opt time out it became invailed ';
                    // send new mail
                }
                if($otp == $this->otp->otp)
                {
                    $this->deleteOtp();
                    return $message = true;
                }else{
                    return $message = 'OTP wrong';
                }
            }
            // user didn't have otp
            else{
                return $message = "user didn't have otp ";
            }

	}

}


