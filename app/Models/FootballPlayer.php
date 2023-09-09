<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use App\Rules\AgeWeightHeightRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballPlayer extends Model
{
    use HasFactory;


    protected $fillable =[
        'user_id',
        'country',
        'city',
        'age',
        'weight',
        'height',
        'gender',
        'skills_level',
        'foot_dominant',
        'main_position',
        'phone_number',
        'whatsapp_number',
        // 'possibility_travel',
        'cv'];


        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public static function rules()
    {
        return [
            'country' => ['required','string'], //normal vaildation
            'city' => ['string','required'], // normal vaildation
            'age' => ['required','numeric','between:4,43'], // from 4 to 43
            'weight' => ['required', new AgeWeightHeightRule ], // from 20 to 100
            'height' => ['required', new AgeWeightHeightRule],  // 100 to 230
            'gender' => ['required' , 'in:male,famle'], //male or famle
            'skills_level' => ['required','in:beginner,professional'], // beginner or profissinal
            'foot_dominant' => ['required','in:right,left'], // reight or left
            'main_position' => ['required','in:goalkeeper,defender,midfielder,forward'], // enum 4 position
            'phone_number' => ['required','numeric','unique:football_players,phone_number'], //
            'whatsapp_number' => ['required','numeric','unique:football_players,whatsapp_number'], //
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:13312'], // required file
        ];
    }
    public static function rulesUpdate($id)
    {
        return [
            'country' => ['required','string'], //normal vaildation
            'city' => ['string','required'], // normal vaildation
            'age' => ['required','numeric','between:4,43'], // from 4 to 43
            'weight' => ['required', new AgeWeightHeightRule ], // from 20 to 100
            'height' => ['required', new AgeWeightHeightRule],  // 100 to 230
            'gender' => ['required' , 'in:male,famle'], //male or famle
            'skills_level' => ['required','in:beginner,professional'], // beginner or profissinal
            'foot_dominant' => ['required','in:right,left'], // reight or left
            'main_position' => ['required','in:goalkeeper,defender,midfielder,forward'], // enum 4 position
            'phone_number' => ['required','numeric',Rule::unique('football_players','phone_number')->ignore($id)], //
            'whatsapp_number' => ['required','numeric',Rule::unique('football_players','whatsapp_number')->ignore($id)],
            'cv' => ['sometimes', 'file', 'mimes:pdf,doc,docx', 'max:13312'], // required file
        ];
    }
}
