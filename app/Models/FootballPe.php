<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FootballPe extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'country',
        'city',
        'age',
        'university',
        'college',
        'gender',
        'phone_number',
        'whatsapp_number',
        'cv'];
        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }



        public static function rules(): array
        {
            return [
                'country' => ['required','string'], //normal vaildation
                'city' => ['string','required'], // normal vaildation
                'age' => ['required','numeric','between:4,43'], // from 4 to 43
                'university'=> ['required','string'],
                'college' => ['required','string'],
                'gender' => ['required' , 'in:male,famle'], //male or famle
                'phone_number' => ['required','numeric','unique:football_pes,phone_number'], //
                'whatsapp_number' => ['required','numeric','unique:football_pes,whatsapp_number'], //
                'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:13312'], // required file
            ];
        }


        public static function rulesUpdate($id): array
    {

            return [
                'country' => ['required','string'], //normal vaildation
                'city' => ['required_with:country','string'], // normal vaildation
                'age' => ['required','numeric','between:4,43'], // from 4 to 43
                'university'=> ['required','string'],
                'college' => ['required','string'],
                'gender' => ['required' , 'in:male,famle'], //male or famle
                'phone_number' => ['required','numeric',Rule::unique('football_pes','phone_number')->ignore($id)], //
                'whatsapp_number' => ['required','numeric',Rule::unique('football_pes','whatsapp_number')->ignore($id)],
                'cv' => ['sometimes', 'file', 'mimes:pdf,doc,docx', 'max:13312'], // required file
            ];
    }
}
