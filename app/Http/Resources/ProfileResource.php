<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function player()
    {
        return [
            "country"=> $this->country,
            "city"=> $this->city,
            "age"=> $this->age,
            "weight"=> $this->weight,
            "height"=> $this->height,
            "gender"=> $this->gender,
            "skills_level"=> $this->skills_level,
            "foot_dominant"=> $this->foot_dominant,
            "main_position"=> $this->main_position,
            "phone_number"=> $this->phone_number,
            "whatsapp_number"=> $this->whatsapp_number ,
            "cv"=> env('APP_URL').'/public/'. $this->cv,
        ];
    }
    public function coach(){
        return  [
            "country"=> $this->country,
            "city"=> $this->city,
            "age"=> $this->age,
            "jop_title" => $this->jop_title,
            "gender"=> $this->gender,
            "phone_number"=> $this->phone_number,
            "whatsapp_number"=> $this->whatsapp_number ,
            "cv"=> env('APP_URL').'/public/'. $this->cv,
        ];
    }

    public function pe(){
        return[
            "country"=> $this->country,
            "city"=> $this->city,
            "age"=> $this->age,
            "gender"=> $this->gender,
            'university'=> $this->university,
            'college' => $this->college,
            "phone_number"=> $this->phone_number,
            "whatsapp_number"=> $this->whatsapp_number ,
            "cv"=> env('APP_URL').'/public/'. $this->cv,
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        switch ($request->role) {
            case 'player':
                $result = $this->player();
                break;
            case 'coach':
                $result = $this->coach();
                break;
            case 'pe':
                $result = $this->pe();
                break;
        }
        return $result;

    }



}
