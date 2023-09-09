<?php
namespace App\Helpers\Otps;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendOTP;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractOtp
{
    protected User $user;
    protected Model $otp;

    /**
    * Summary of getUserOtp
    * @return bool
    */
	protected function getUserOtp(): bool {
        $otp = $this->otp->where('user_id',$this->user->id)->first();

        if ($otp){
            $this->otp = $otp;
            return true;
        }
        return false;
	}
    /**
    * Summary of deleteOtp
    * @return void
    */
	protected function deleteOtp(): void {
        $this->otp->delete();
	}

    /**
     * Summary of buildOtp
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function buildOtp(): Model{
        $checkVerify = $this->checkVerify();
        if($checkVerify == false){
            $this->createRow();
        }
        return $this->otp;
	}

    /**
     * Summary of returnExpireDate
     * @return string
     */
    public function returnExpireDate(): string {
        $date = Carbon::now()->diffInMinutes($this->otp->expire_at);
        return $date;
	}

    /**
     * Summary of makeVerify
     * @return bool
     */
    protected function makeVerify() : bool {
        $this->user->email_verified_at = Carbon::now();
        if($this->user->save()){
            return true;
        }
        return false;
    }
    /**
     * Summary of checkVerify
     * @return bool
     */
    public function checkVerify() : bool
    {
        if ($this->user->email_verified_at == null)
        {
            // not verified
            return false;
        }
        // verified
        return true;
    }

    /**
	 * @return int
	 */
	protected function setOtp(): int {
        $otp = rand(100000,999999);
		return $otp;
	}
    /**
     * Summary of createRow
     * @return void
     */
    protected function createRow(): void {
        if(!$this->getUserOtp())
        {
            $this->otp->user_id = $this->user->id;
            $this->otp->otp = $this->setOtp();
            $this->otp->expire_at = Carbon::now()->addMinutes(3);
            $this->otp->save();
        }
	}

    /**
     * Summary of checkExpire
     * @return bool
     */
    protected function checkExpire(): bool {
        if ( Carbon::now() > $this->otp->expire_at)
        {
            $this->deleteOtp();
            return false;
        }
        return true;
	}

    /**
     * Summary of attempOTP
     * @param mixed $otp
     * @return mixed
     */
    public function attempOTP($otp): mixed {
        $message = '';
        $checkVerify = $this->checkVerify();
        if($checkVerify == null)
        {
            if($this->getUserOtp())
            {
                if(!$this->checkExpire())
                {
                    return $message = 'opt time out it became invailed';
                    // send new mail
                }
                if($otp == $this->otp->otp)
                {
                    $this->makeVerify();
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
            // already verified
        return $message = [true,'user already verified'];
	}
}
?>
